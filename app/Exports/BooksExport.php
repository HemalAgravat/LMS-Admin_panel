<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Book::all();
    }

    /**
     * Method headings
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'id',
            'uuid',
            'title',
            'author',
            'isbn',
            'publication_date',
            'availability_status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
