<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        // Check if user authorized and is admin
        return Auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status.in:active,closed',
            'expiry_date.date/after:today',
            'discount_type.required_if:status,active',
            'discount_type.in:fixed,percentage',
            'discount_value.required',
            'product_id.exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'discount_type.required_if' => 'Discount type is required when status is active',
            'discount_type.in' => 'Discount type must be one of: fixed, percentage',
            'discount_value.required' => 'Discount value is required',
            'product_id.exists:products,id' => 'Product not found',
            'status.in' => 'Status must be one of: active, closed',
            'expiry_date.after' => 'Expire date must be after today',
            'expiry_date.date' => 'Expire date must be a date',
        ];
    }
}
