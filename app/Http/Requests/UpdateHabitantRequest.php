<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHabitantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $dateMajorite = now()->subYears(18)->toDateString();

        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('habitants', 'email')->ignore($this->route('habitant')),
            ],
            'telephone' => ['nullable', 'string', 'max:30'],
            'date_naissance' => ['required', 'date', 'before_or_equal:'.$dateMajorite],
            'quartier' => ['required', 'string', 'max:255'],
        ];
    }
}

