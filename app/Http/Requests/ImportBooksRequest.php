<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportBooksRequest extends FormRequest
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

    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.title' => 'required|string|max:50',
            'data.*.author' => 'required|string|max:50',
            'data.*.isbn' => 'required|string|size:13',
            'data.*.publication_date' => 'required|date',
            'data.*.availability_status' => 'required|boolean',
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
            'books.required' => 'The books field is required.',
            'books.array' => 'The books field must be an array.',
            'books.*.title.required' => 'The title is required for each book.',
            'books.*.author.required' => 'The author is required for each book.',
            'books.*.isbn.required' => 'The ISBN is required for each book.',
            'books.*.isbn.size' => 'The ISBN must be exactly 13 characters.',
            'books.*.publication_date.required' => 'The publication date is required for each book.',
            'books.*.publication_date.date' => 'The publication date must be a valid date.',
            'books.*.availability_status.required' => 'The availability status is required for each book.',
            'books.*.availability_status.boolean' => 'The availability status must be true or false.',
        ];
    }
}
