<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'string|max:50',
            'author' => 'string|max:50',
            'isbn' => 'string|max:13',
            'publication_date' => 'date',
            'availability_status' => 'boolean',
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
            'title.required' => 'The book title is required.',
            'title.string' => 'The book title must be a string.',
            'title.max' => 'The book title may not be greater than 50 characters.',
            'author.required' => 'The author name is required.',
            'author.string' => 'The author name must be a string.',
            'author.max' => 'The author name may not be greater than 50 characters.',
            'isbn.required' => 'The ISBN number is required.',
            'isbn.string' => 'The ISBN number must be a string.',
            'isbn.unique' => 'The ISBN number must be unique.',
            'isbn.max' => 'The ISBN number may not be greater than 13 characters.',
            'publication_date.required' => 'The publication date is required.',
            'publication_date.date' => 'The publication date must be a valid date.',
            'availability_status.boolean' => 'The availability status must be true or false.',
        ];
    }
}
