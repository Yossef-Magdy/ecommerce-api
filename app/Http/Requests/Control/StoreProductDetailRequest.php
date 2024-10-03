<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductDetailRequest extends FormRequest
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
            'product_id' => ['required', 'exists:products,id'],
            'color' => ['required', 'string'],
            'size' => ['required', 'string'],
            'material' => ['required', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product ID is required',
            'product_id.exists' => 'Product not found',
            'color.required' => 'Color is required',
            'color.string' => 'Color must be a string',
            'size.required' => 'Size is required',
            'size.string' => 'Size must be a string',
            'material.required' => 'Material is required',
            'material.string' => 'Material must be a string',
            'stock.required' => 'Stock is required',
            'stock.integer' => 'Stock must be an integer',
            'stock.min' => 'Stock must be at least 0',
            'price.numeric' => 'Price must be a number',
        ];
    }
}
