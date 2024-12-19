<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users,email',
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:1,2,3',
        ];
    }

    /**
     * Method messages
     *
     * @return void
     */
    public function messages()
    {
        return [
            'name.required' => 'The name field is requireds.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name may not be greater than 50 characters.',

            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a valid string.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 50 characters.',
            'email.unique' => 'The email has already been taken.',

            'password.nullable' => 'The password may be null.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 6 characters.',

            'role.required' => 'The role field is required.',
            'role.string' => 'The role must be a valid string.',
            'role.in' => 'The selected role is invalid. Please choose a valid role (1, 2, or 3).',
        ];
    }
}
