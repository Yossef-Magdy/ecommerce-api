<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductDetailRequest extends FormRequest
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
            'color' => ['string'],
            'size' => ['string'],
            'material' => ['string'],
            'stock' => ['integer', 'min:0'],
            'price' => ['numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'color.string' => 'Color must be a string',
            'size.string' => 'Size must be a string',
            'material.string' => 'Material must be a string',
            'stock.integer' => 'Stock must be an integer',
            'stock.min' => 'Stock must be at least 0',
            'price.numeric' => 'Price must be a number',
        ];
    }
}
