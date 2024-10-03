<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
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
            'paid_amount' => ['required', 'numeric'],
            'outstanding_amount' => ['required', 'numeric'],
            'token' => ['required', 'unique:orders,token'],
            'shipping' => ['required'],
            'shipping.method' => ['required'],
            'shipping.status' => ['required'],
            'shipping.fee' => ['required', 'numeric'],
            'shipping.shipping_detail_id' => ['exists:shipping_details,id'],
            'payment' => ['required', 'array'],
            'payment.amount' => ['required', 'numeric'],
            'payment.method' => ['required'],
            'payment.status' => ['required'],
            'items' => ['required', 'array'],
            'items.*.product_detail_id' => ['required', 'exists:product_details,id'],
            'items.*.total_price' => ['required', 'numeric'],
            'items.*.quantity' => ['required', 'numeric'],
            'coupon' => ['nullable', 'exists:coupons,coupon_code'],
            'coupon.status' => ['nullable', 'in:active,closed'],
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
            'paid_amount.required' => 'Paid amount is required',
            'paid_amount.numeric' => 'Paid amount must be a number',
            'outstanding_amount.required' => 'Outstanding amount is required',
            'outstanding_amount.numeric' => 'Outstanding amount must be a number',
            'token.required' => 'Token is required',
            'token.unique' => 'Token must be unique',
            'shipping.required' => 'Shipping is required',
            'shipping.method.required' => 'Shipping method is required',
            'shipping.status.required' => 'Shipping status is required',
            'shipping.fee.required' => 'Shipping fee is required',
            'shipping.shipping_detail_id.exists' => 'Shipping details not found',
            'payment.required' => 'Payment is required',
            'payment.amount.required' => 'Payment amount is required',
            'payment.amount.numeric' => 'Payment amount must be a number',
            'payment.method.required' => 'Payment method is required',
            'payment.status.required' => 'Payment status is required',
            'items.required' => 'Items are required',
            'items.array' => 'Items must be an array',
            'items.exists' => 'Item not found',
            'items.total_price.required' => 'Total price is required',
            'items.total_price.numeric' => 'Total price must be a number',
            'items.quantity.required' => 'Quantity is required',
            'items.quantity.numeric' => 'Quantity must be a number',
            'coupon.exists' => 'Coupon not found',
            'coupon.status.active' => 'Coupon status must be active',
        ];
    }
}
