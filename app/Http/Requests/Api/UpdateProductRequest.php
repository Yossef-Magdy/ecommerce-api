<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user authorized
        // return Auth::check();

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
            'name', 'description', 'stock', 'price', 'color', 'size', 'cover_image'
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Product name is required',
            'description' => 'Product description is required',
            'stock' => 'Product stock is required',
            'stock' => 'Product stock should be an integer',
            'price' => 'Product price is required',
            'price.numeric' => 'Product price should be a number',
            'color' => 'Product color is required',
            'color.string' => 'Product color should be a string',
            'size' => 'Product size is required',
            'size.in' => 'Product size must be one of: m, l, xl, xxl, xxxl',
            'cover_image.image' => 'Product cover image should be an image',
            'cover_image.mimes' => 'Product cover image must be a file of type: jpg, png.',
            'cover_image.max' => 'Product cover image may not be greater than 5 MB.',
        ];        
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
