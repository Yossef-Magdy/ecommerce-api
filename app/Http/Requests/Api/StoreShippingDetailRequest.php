<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreShippingDetailRequest extends FormRequest
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
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'apartment' => ['string'],
            'postal_code' => ['integer', 'digits:5'],
            'phone_number' => ['required', 'string', 'regex:/^(?:\+20)?(010|011|012|015)\d{8}$/'],
            'governorate_id' => ['required', 'exists:governorates,id'],
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
            'city.required' => 'City is required',
            'city.string' => 'City must be a string',
            'address.required' => 'Address is required',
            'apartment.string' => 'Apartment must be a string',
            'postal_code.integer' => 'Postal code must be an integer',
            'postal_code.digits' => 'Postal code must be 5 digits',
            'phone_number.required' => 'Phone number is required',
            'phone_number.string' => 'Phone number must be an string',
            'phone_number.regex' => 'Phone number must be a Egyptian phone number',
            'governorate_id.required' => 'Governorate ID is required',
            'governorate_id.exists' => 'Governorate not found',
            'is_default.boolean' => 'Is default must be a boolean',
        ];
    }
}
