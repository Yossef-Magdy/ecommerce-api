<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingDetailRequest extends FormRequest
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
        return [
            'city' => ['string'],
            'address' => ['string'],
            'apartment' => ['string'],
            'postal_code' => ['integer', 'digits:5'],
            'phone_number' => ['string', 'regex:/^(?:\+20)?(010|011|012|015)\d{8}$/'],
            'governorate_id' => ['exists:governorates,id'],
            'is_default' => ['boolean'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'apartment.string' => 'Apartment must be a string',
            'postal_code.integer' => 'Postal code must be an integer',
            'postal_code.digits' => 'Postal code must be 5 digits',
            'phone_number.integer' => 'Phone number must be an integer',
            'phone_number.min' => 'Phone number must be at least 11 digits',
            'governorate_id.exists' => 'Governorate not found',
            'is_default.boolean' => 'Is default must be a boolean',
        ];
    }
}
