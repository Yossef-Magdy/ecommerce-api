<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'shipping_status' => ['nullable', 'string', 'in:pending,processing,shipped,delivered,canceled'],
            'payment_status' => ['nullable', 'string', 'in:pending,processing,completed,canceled,incomplete'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_status.in' => 'Shipping status must be [ pending, processing, shipped, delivered, canceled ]',
            'payment_status.in' => 'Payment status must be [ pending, processing, completed, canceled ]',
        ];
    }
}
