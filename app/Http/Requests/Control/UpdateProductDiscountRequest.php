<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductDiscountRequest extends FormRequest
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
            'status' => ['in:active,closed'],
            'expiry_date' => ['date'],
            'type' => ['in:fixed,percentage'],
            'value' => ['numeric'],
            'product_id' => ['required', 'exists:products,id']
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Discount type must be one of: fixed, percentage',
            'product_id.exists:products,id' => 'Product not found',
            'status.in' => 'Status must be one of: active, closed',
            'expiry_date.date' => 'Expire date must be a date',
        ];
    }
}
