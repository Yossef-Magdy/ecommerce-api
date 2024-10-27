<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
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
            'coupon_code' => ['alpha_dash', 'unique:coupons,coupon_code,' . $this->id . ',id'],
            'uses_count' => ['integer', 'min:50'],
            'discount_type' => ['in:fixed,percentage'],
            'discount_value' => [
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->discount_type === 'percentage' && $value > 100) {
                        $fail('The discount value must be less than or equal to 100 when type is percentage.');
                    }
                },
            ],
            'expiry_date' => ['date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_code.alpha_dash' => 'Coupon code must be alpha dash',
            'uses_count.integer' => 'Uses count must be an integer',
            'discount_type.in' => 'Discount type must be one of: fixed, percentage',
            'discount_value.numeric' => 'Discount value must be a number',
            'expiry_date.date' => 'Expiry date must be a date',
            'expiry_date.after_or_equal' => 'Expiry date must be today or a future date',
            'coupon_code.unique' => 'Coupon code already exists',
        ];
    }
}
