<?php

namespace App\Repositories;

use App\Exports\BooksExport;
use App\Models\Book;
use Maatwebsite\Excel\Facades\Excel;

class BookRepository extends BaseRepository
{
    /**
     * Method __construct
     *
     * @param User $model [explicite description]
     *
     */
    public function __construct(Book $model)
    {
        $this->model = $model;
    }
    /**
     * Method findAllByAvailability
     *
     * @param $availability $availability [explicite description]
     *
     */
    public function findAllByAvailability($availability)
    {
        return $this->model->where('availability_status', $availability)->get();
    }
    /**
     * Method export
     *
     * @param $filePath $filePath [explicite description]
     *
     */
    public function export($filePath)
    {
        Excel::store(new BooksExport, $filePath);
    }
    /**
     * Method searchFunction
     *
     * @param $input $input [explicite description]
     * @param $availability_status $availability_status [explicite description]
     *
     */
    public function searchFunction($input, $availability_status =  null)
    {
        return $this->model->search($input, $availability_status);
    }
    /**
     * Method updateAvailabilityStatus
     *
     * @param $bookId $bookId [explicite description]
     * @param $status $status [explicite description]
     *
     */
    public function updateAvailabilityStatus($bookId, $status)
    {
        return $this->model->where('id', $bookId)->update(['availability_status' => $status]);
    }

    /**
     * Method getBookById
     *
     * @param $bookId $bookId [explicite description]
     *
     */
    public function getBookById($bookId)
    {
        return  $this->model->findOrFail($bookId);
    }
}
