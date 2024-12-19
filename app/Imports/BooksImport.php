<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow
{
    /**
     * Method model
     *
     * @param array $row [explicite description]
     *
     */
    public function model(array $row)
    {
        return new Book([
            'title'            => $row[0],
            'author'           => $row[1],
            'isbn'             => $row[2],
            'publication_date' => $row[3],
            'availability_status'  => $row[4],

        ]);
    }

    /**
     * Method rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '0' => 'required|string|max:50',
            '1' => 'required|string|max:50',
            '2' => 'required|string|size:13',
            '3' => 'required|date',
            '4' => 'required|in:true,false',

        ];
    }
}
