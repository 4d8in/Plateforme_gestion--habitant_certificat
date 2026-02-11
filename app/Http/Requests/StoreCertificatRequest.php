<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Certificat;
use Illuminate\Foundation\Http\FormRequest;

class StoreCertificatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'montant' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            /** @var \App\Models\Habitant|null $habitant */
            $habitant = $this->route('habitant');

            if (! $habitant) {
                return;
            }

            $pendingExists = $habitant->certificats()
                ->where('statut', Certificat::STATUT_EN_ATTENTE)
                ->exists();

            if ($pendingExists) {
                $validator->errors()->add(
                    'certificat',
                    'Cet habitant a déjà un certificat en attente. Validez-le ou supprimez-le avant d\'en créer un autre.'
                );
            }
        });
    }
}
