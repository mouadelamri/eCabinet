<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                    => 'required|string|max:255',
            'email'                   => 'required|string|email|max:255|unique:users,email',
            'password'                => 'required|string|min:8|confirmed',
            'date_naissance'          => 'required|date',
            'adresse'                 => 'required|string',
            'telephone'               => 'required|string|max:20',
            'numero_securite_sociale' => 'nullable|string',
            'groupe_sanguin'          => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'antecedents_medicaux'    => 'nullable|string',
            'allergies'               => 'nullable|string',
        ];
    }
}
