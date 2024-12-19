<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => 'string|max:50',
            'email' => [
                'email',
                'max:50',
                Rule::unique('users')->ignore($this->uuid, "uuid"),
            ],
            'password' => 'string|min:6',
            'role' => 'string|in:1,2,3',
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
            'name.string' => 'The name must be a valid strings.',
            'name.max' => 'The name may not be greater than 50 characters.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 50 characters.',
            'email.unique' => 'The email has already been taken.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 6 characters.',
            'role.string' => 'The role must be a valid string.',
            'role.in' => 'The selected role is invalid. Please choose a valid role.',
        ];
    }
}
