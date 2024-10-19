<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
            'items' => ['required', 'array'],
            'items.*.productDetailId' => ['required', 'exists:product_details,id'],
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
            'items.required' => 'Items are required',
            'items.*.productDetailId.required' => 'Product detail ID is required',
            'items.*.productDetailId.exists' => 'Product detail not found',
        ];
    }
}
