<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Certificat;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificatRequest extends FormRequest
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
            'date_certificat' => ['required', 'date'],
            'statut' => ['required', 'in:'.implode(',', [
                Certificat::STATUT_EN_ATTENTE,
                Certificat::STATUT_PAYE,
                Certificat::STATUT_DELIVRE,
            ])],
            'montant' => ['required', 'integer', 'min:0'],
            'reference_paiement' => ['nullable', 'string', 'max:255'],
        ];
    }
}
