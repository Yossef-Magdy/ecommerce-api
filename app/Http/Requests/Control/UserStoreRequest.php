<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
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
            'first_name'=>['required', 'string'],
            'last_name'=>['required', 'string'],
            'email'=>['required', 'email'],
            'password'=>['required'],
            'roles'=>['array', 'exists:roles,id'],
            'roles.*'=>['numeric'],
            'permissions'=>['array', 'exists:permissions,id'],
            'permissions.*'=>['numeric'],
        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required'=>'First name is required',
            'first_name.string'=>'First name must be only characters',
            'last_name.required'=>'Last name is required',
            'last_name.string'=>'Last name must be only characters',
            'email.required'=>'Email is required',
            'email.email'=>'Enter a valid email',
            'password.required'=>'Password is required ',
            'roles.array'=>'Please enter an array',
            'roles.exists'=>'Role id does not exists',
            'roles.*.numeric'=>'Role id should be an integer',
            'permissions.array'=>'Please enter an array',
            'permissions.exists'=>'Permission id does not exists',
            'permissions.*.numeric'=>'Permission id should be an integer',
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
