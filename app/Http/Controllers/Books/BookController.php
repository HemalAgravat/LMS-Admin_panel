<?php

namespace App\Http\Controllers\Books;


use App\Http\Controllers\Controller;
use App\Http\Requests\BookavailAbilityRequest;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\ImportBooksRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\User;
use App\Services\BookService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class BookController extends Controller
{

    use JsonResponseTrait;
    /**
     * bookService
     *
     * @var mixed
     */
    protected $bookService;

    /**
     * Method __construct
     *
     * @param BookService $bookService [explicite description]
     *
     * @return void
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Method createBook
     *
     * @param CreateBookRequest $request [explicite description]
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function createBook(CreateBookRequest $request, User $model)
    {

        $this->authorize('curd', $model);
        $bookData = $request->validated();
        return $this->bookService->createBook($bookData);
    }


    /**
     * Method updateBook
     *
     * @param UpdateBookRequest $request [explicite description]
     * @param $uuid $uuid [explicite description]
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function updateBook(UpdateBookRequest $request, $uuid, User $model)
    {

        $this->authorize('curd', $model);
        return $this->bookService->updateBook($uuid, $request->all());
    }

    /**
     * Method show
     *
     * @param $uuid $uuid [explicite description]
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function show($uuid, User $model)
    {
        return $this->bookService->show($uuid);
    }



    /**
     * Method deleteBook
     *
     * @param $uuid $uuid [explicite description]
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function deleteBook($uuid, User $model)
    {
        $this->authorize('curd',  $model);
        return  $this->bookService->destroy($uuid);
    }
    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        return $this->bookService->getAllBooks();
    }
    /**
     * Method updateAvailability
     *
     * @param BookAvailabilityRequest $request [explicite description]
     *
     * @return void
     */
    public function updateAvailability(BookAvailabilityRequest $request)
    {

        return $this->bookService->fetchBooksByAvailability($request);
    }

    /**
     * Method import
     *
     * @param ImportBooksRequest $request [explicite description]
     *
     * @return void
     */
    public function import(ImportBooksRequest $request, User $model)
    {
        $userId = auth()->id();
        $this->authorize('curd',  $model);
        return $this->bookService->importBooks($request->input('data'), $userId);
    }

    /**
     * Method export
     *
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function export(User $model)
    {
        $this->authorize('curd',  $model);
        return $this->bookService->exportBooks();
    }


    /**
     * Method search
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function search(Request $request)
    {
        return $this->bookService->searchBooks($request);
    }

    /**
     * Method showPublicationStats
     *
     * @return void
     */
    public function showPublicationStats()
    {
        $stats = $this->bookService->getPublicationStats();
        return view('books.publication-stats', ['stats' => $stats]);
    }
}
