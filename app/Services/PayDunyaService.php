<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Certificat;
use Illuminate\Support\Facades\Http;

class PayDunyaService
{
    /**
     * Build the base URL depending on the mode (sandbox or live).
     */
    protected function baseUrl(): string
    {
        $mode = env('PAYDUNYA_MODE', 'sandbox');

        return $mode === 'live'
            ? 'https://app.paydunya.com/api/v1'
            : 'https://app.paydunya.com/sandbox-api/v1';
    }

    /**
     * Common headers required by PayDunya.
     *
     * @return array<string, string>
     */
    protected function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'PAYDUNYA-MASTER-KEY' => (string) env('PAYDUNYA_MASTER_KEY'),
            'PAYDUNYA-PRIVATE-KEY' => (string) env('PAYDUNYA_PRIVATE_KEY'),
            'PAYDUNYA-TOKEN' => (string) env('PAYDUNYA_TOKEN'),
        ];
    }

    /**
     * Create a checkout invoice on PayDunya for the given certificat.
     *
     * @return array<string, mixed>
     */
    public function createCheckoutInvoice(
        Certificat $certificat,
        string $returnUrl,
        string $cancelUrl,
        string $callbackUrl
    ): array {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl().'/checkout-invoice/create', [
                'invoice' => [
                    'total_amount' => $certificat->montant,
                    'description' => sprintf('Certificat de résidence #%d', $certificat->id),
                    'customer' => [
                        'name' => $certificat->habitant->nom_complet,
                        'email' => $certificat->habitant->email,
                        'phone' => $certificat->habitant->telephone,
                    ],
                    'custom_data' => [
                        'certificat_id' => $certificat->id,
                    ],
                ],
                'store' => [
                    'name' => config('app.name', 'Gestion Habitants'),
                ],
                'actions' => [
                    'cancel_url' => $cancelUrl,
                    'return_url' => $returnUrl,
                    'callback_url' => $callbackUrl,
                ],
            ]);

        if (! $response->successful() || $response->json('response_code') !== '00') {
            throw new \RuntimeException(
                'Erreur PayDunya: '.$response->json('response_text', 'Erreur inconnue')
            );
        }

        /** @var array<string, mixed> $data */
        $data = $response->json();

        return $data;
    }

    /**
     * Confirm a checkout invoice on PayDunya using its token.
     *
     * @return array<string, mixed>
     */
    public function confirmCheckoutInvoice(string $token): array
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl().'/checkout-invoice/confirm/'.$token);

        if (! $response->successful() || $response->json('response_code') !== '00') {
            throw new \RuntimeException(
                'Erreur PayDunya (confirm): '.$response->json('response_text', 'Erreur inconnue')
            );
        }

        /** @var array<string, mixed> $data */
        $data = $response->json();

        return $data;
    }
}

