<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookavailAbilityRequest extends FormRequest
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
            'availability_status' => 'required|boolean',
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
            'availability_status.required' => 'The availability status is required.',
            'availability_status.boolean' => 'The availability status must be true or false.',
        ];
    }
}
