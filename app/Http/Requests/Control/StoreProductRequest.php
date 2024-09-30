<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'cover_image' => ['image', 'mimes:jpg,png'],
            'product_images' => ['array'],
            'product_images.*' => ['image'],
            'categories' => ['array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'subcategories' => ['array'],
            'subcategories.*' => ['integer', 'exists:subcategories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'description.required' => 'Product description is required',
            'cover_image.image' => 'Product cover image should be an image',
            'cover_image.mimes' => 'Product cover image must be a file of type: jpg, png.',
            'cover_image.max' => 'Product cover image may not be greater than 5 MB.',
            'product_images.*.image' => 'Product images should be an image',
            'product_images.*.mimes' => 'Product images must be a file of type: jpg, png.',
            'product_images.*.max' => 'Product images may not be greater than 5 MB.',
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
