<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'first_name'=>['required'],
            'last_name'=>['required'],
            'email'=>['required', 'email', 'unique:users,email'],
            'password'=>['required'],
        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required'=>'First name is required',
            'last_name.required'=>'Last name is required',
            'email.required'=>'Email is required',
            'email.email'=>'Enter a valid email',
            'password.required'=>'Password is required ',
        ];   
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['errors' => $errors], 400)
        );
    }
}
