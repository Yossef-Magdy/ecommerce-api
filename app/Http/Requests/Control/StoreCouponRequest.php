<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
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
            'coupon_code' => ['required', 'string', 'unique:coupons,coupon_code'],
            'uses_count' => ['integer'],
            'discount_type' => ['in:fixed,percentage'],
            'discount_value' => ['required', 'numeric'],
            'expiry_date' => ['date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_code.required' => 'Coupon code is required',
            'coupon_code.string' => 'Coupon code must be a string',
            'uses_count.integer' => 'Uses count must be an integer',
            'discount_type.in' => 'Discount type must be one of: fixed, percentage',
            'discount_value.required' => 'Discount value is required',
            'discount_value.numeric' => 'Discount value must be a number',
            'expiry_date.date' => 'Expiry date must be a date',
            'expiry_date.after' => 'Expiry date must be after today',
        ];
    }
}
