<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

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
            'token' => ['required', 'unique:orders,token'],
            'shipping_detail_id' => ['required' ,'exists:shipping_details,id'],
            'payment_method' => ['required'],
            'items' => ['required', 'array'],
            'items.*.product_detail_id' => ['required', 'exists:product_details,id'],
            'items.*.quantity' => ['required', 'numeric'],
            'coupon' => ['nullable', 'exists:coupons,coupon_code'],
            'stripeToken' => ['required', 'string'],
            'currency' => ['required', 'string', 'in:usd,eur,egp'],
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
            'token.required' => 'Token is required',
            'token.unique' => 'Token must be unique',
            'shipping_detail_id.required' => 'Shipping details is required', 
            'shipping_detail_id.exists' => 'Shipping details not found',
            'payment_method.required' => 'Payment method is required',
            'items.required' => 'Items are required',
            'items.array' => 'Items must be an array',
            'items.exists' => 'Item not found',
            'items.quantity.required' => 'Quantity is required',
            'items.quantity.numeric' => 'Quantity must be a number',
            'coupon.exists' => 'Coupon not found',
            'stripeToken.required' => 'Stripe token is required',
            'currency.required' => 'Currency is required',
            'currency.in' => 'Currency must be usd,eur,egp',
        ];
    }
}
