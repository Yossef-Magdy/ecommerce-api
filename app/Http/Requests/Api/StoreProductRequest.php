<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
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
            'name',
            'description',
            'cover_image',
            'product_images',
            'attributes'
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
            'product_images.image' => 'Product images should be an image',
            'product_images.mimes' => 'Product images must be a file of type: jpg, png.',
            'product_images.max' => 'Product images may not be greater than 5 MB.',
            'attributes.required' => 'Product attributes are required',
            'attributes.array' => 'Product attributes must be an array',
            'attributes.*.name.required' => 'Product attribute name is required',
            'attributes.*.options.required' => 'Product attribute value is required',
            'attributes.*.options.array' => 'Product attribute values must be an array',
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
