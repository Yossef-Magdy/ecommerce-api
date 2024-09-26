<?php

namespace App\Http\Requests\Control;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'roles'=>['array', 'exists:roles,id'],
            'roles.*'=>['numeric'],
            'permissions'=>['array', 'exists:permissions,id'],
            'permissions.*'=>['numeric'],
        ];
    }
    public function messages(): array
    {
        return [
            'roles.array'=>'Please enter an array',
            'roles.exists'=>'Role id does not exists',
            'roles.numeric'=>'Role id should be an integer',
            'permissions.array'=>'Please enter an array',
            'permissions.exists'=>'Permission id does not exists',
            'permissions.numeric'=>'Permission id should be an integer',
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
