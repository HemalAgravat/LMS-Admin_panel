<?php

namespace App\Services;


use App\Repositories\BookRepository;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class BookService
{

    use JsonResponseTrait;
    /**
     * bookRepository
     *
     * @var mixed
     */
    protected $bookRepository;

    /**
     * __construct
     *
     * @param  mixed $bookRepository
     * @return void
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * createBook
     *
     * @param  mixed $data
     * @return void
     */
    public function createBook(array $data)
    {
        try {
            $book = $this->bookRepository->create($data);
            Log::channel('create_book')->info(
                'Book Created',
                [
                    'book_id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'publication_date' => $book->publication_date,
                    'availability_status' => $book->availability_status
                ]
            );

            return $this->successResponse($book, 'messages.books.create', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.books.Failed', 500);
        }
    }


    /**
     * updateBook
     *
     * @param  mixed $uuid
     * @param  mixed $data
     * @return void
     */
    public function updateBook($uuid, array $data)
    {
        try {
            $this->bookRepository->updateByUuid($uuid, $data);
            $books = $this->bookRepository->findByUuid($uuid);
            Log::channel('update_book')->info('Book Created', [
                'book_id' => $books->id,
                'title' => $books->title,
                'author' => $books->author,
                'isbn' => $books->isbn,
                'publication_date' => $books->publication_date,
                'availability_status' => $books->availability_status
            ]);
            return $this->successResponse($books, 'messages.books.update', 200);
        } catch (Exception $e) {
            return $this->errorResponse('messages.books.NoFound', 404);
        }
    }


    /**
     * show
     *
     * @param  mixed $uuid
     * @return void
     */
    public function show($uuid)
    {
        try {
            $user = $this->bookRepository->findByUuid($uuid);
            if (!$user) {
                return $this->errorResponse('messages.books.NoFound', 404);
            }
            return $user;
        } catch (\Exception $e) {
            return $this->errorResponse('messages.books.NoFound', 404);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $uuid
     * @return void
     */
    public function destroy($uuid)
    {
        try {
            $book = $this->bookRepository->findByUuid($uuid);
            if (!$book) {
                return $this->errorResponse('messages.books.NoFound', 404);
            }
            $book->delete();
            return $this->successResponse(null, 'messages.books.deleted', 200);
        } catch (Exception $e) {
            return $this->errorResponse('messages.books.NoFound', 404);
        }
    }

    /**
     * getAllBooks
     *
     * @return void
     */
    public function getAllBooks()
    {
        try {
            $books = $this->bookRepository->all();
            return $this->successResponse($books, 'messages.books.found', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.books.FailedFetch', 500);
        }
    }

    /**
     * fetchBooksByAvailability
     *
     * @param  mixed $request
     * @return void
     */
    public function fetchBooksByAvailability(Request $request)
    {
        try {
            $status = $request->input('availability_status');
            if (!is_bool($status)) {
                return $this->errorResponse('messages.books.FailedFetch', 500);
            }
            $books = $this->bookRepository->findAllByAvailability($status);
            return $this->successResponse($books, 'messages.books.found', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.books.FailedFetch', 500);
        }
    }


    /**
     * importBooks
     *
     * @param  mixed $data
     * @return void
     */
    public function importBooks($data)
    {
        try {
            $importedBooks = [];
            foreach ($data as $item) {
                $importedBooks[] = $this->bookRepository->create([
                    'title'               => $item['title'],
                    'author'              => $item['author'],
                    'isbn'                => $item['isbn'],
                    'publication_date'    => $item['publication_date'],
                    'availability_status' => $item['availability_status'],

                ]);
            }
            return $this->successResponse($importedBooks, 'messages.books.import', 201);
        } catch (Exception $e) {
            return $this->errorResponse('messages.books.importFailed', 500);
        }
    }

    /**
     * exportBooks
     *
     * @return void
     */
    public function exportBooks()
    {
        try {
            $fileName = 'books_' . date('D, d M Y H:i:s') . '.csv';
            $filePath = 'public/' . $fileName;
            $this->bookRepository->export($filePath);
            return $this->successResponse(null, 'messages.books.export');
        } catch (\Exception $e) {

            return $this->errorResponse('messages.books.exportFailed', 500);
        }
    }
    /**
     * searchBooks
     *
     * @param  mixed $request
     * @return void
     */
    public function searchBooks(Request $request)
    {
        $input = $request->input('input');
        $availability_status =  $request->input('availability_status');
        $request->validate([
            'input' => 'required|string|max:50',
            'availability_status' => 'in:true,false',
        ]);
        if (!$input) {
            return $this->errorResponse('messages.search', 400);
        }
        $book = $this->bookRepository->searchFunction($input, $availability_status);
        return $this->successResponse($book);
    }

    /**
     * Method getPublicationStats
     *
     */
    public function getPublicationStats()
    {
        // Fetch all books from the repository
        $books = $this->bookRepository->all();

        $yearCount = [];

        // Loop through the books and process their publication year
        foreach ($books as $book) {
            $year = Carbon::parse($book->publication_date)->year;
            $yearCount[$year] = isset($yearCount[$year]) ? $yearCount[$year] + 1 : 1;
        }

        // Format the stats into an array
        $formattedStats = [];
        foreach ($yearCount as $year => $count) {
            $formattedStats[] = ['year' => $year, 'count' => $count];
        }

        // Sort stats by year
        usort($formattedStats, function ($a, $b) {
            return $a['year'] - $b['year'];
        });

        return $formattedStats;
    }
}
