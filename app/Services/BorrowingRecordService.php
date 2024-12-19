<?php

namespace App\Services;

use App\Repositories\BookRepository;
use App\Repositories\BorrowingRecordRepository;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BorrowingRecordService
{
    use JsonResponseTrait;
    /**
     * borrowingRecordRepository
     *
     * @var mixed
     */
    protected $borrowingRecordRepository;
    /**
     * bookRepository
     *
     * @var mixed
     */
    protected $bookRepository;


    /**
     * __construct
     *
     * @param  mixed $borrowingRecordRepository
     * @param  mixed $bookRepository
     * @return void
     */
    public function __construct(BorrowingRecordRepository $borrowingRecordRepository, BookRepository $bookRepository)
    {
        $this->borrowingRecordRepository = $borrowingRecordRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * borrowBook
     *
     * @param  mixed $data
     * @return void
     */
    public function borrowBook(array $data)
    {
        try {
            $userId = auth()->id();
            $bookId = $data['book_id'];
            $errors = [];

            if ($this->borrowingRecordRepository->countUserBorrowedBooks($userId) >= 5) {
                $errors[] = ['message' => 'messages.borrow.borrowlimit', 'code' => 400];
            } elseif (!$this->borrowingRecordRepository->findActiveById($bookId)) {
                $errors[] = ['message' => 'messages.books.NoFound', 'code' => 404];
            } elseif ($this->borrowingRecordRepository->findActiveById($bookId)->availability_status === false) {
                $errors[] = ['message' => 'messages.borrow.book_not_available', 'code' => 409];
            } elseif ($this->borrowingRecordRepository->hasUserBorrowedBook($userId, $bookId)) {
                $errors[] = ['message' => 'messages.borrow.borrowedBook', 'code' => 500];
            }
            if (!empty($errors)) {
                return $this->errorResponse($errors[0]['message'], $errors[0]['code']);
            }
            $data['due_date'] = now()->addDays(14);
            $data['user_id'] = $userId;
            $data['borrow_date'] = now();
            $data['returned'] = false;


            Log::channel('borrow_record')->info('Borrow Book', [
                'user_id' => $data['user_id'],
                'book_id' => $data['book_id'],
                'borrow_date' => $data['borrow_date'],
                'due_date' => $data['due_date'],
                'returned' => $data['returned'],
            ]);
            $this->borrowingRecordRepository->create($data);
            $this->bookRepository->updateAvailabilityStatus($bookId, false);
            return $this->successResponse($data, 'messages.borrow.create', 201);
        } catch (\Exception $e) {
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

            $user = $this->borrowingRecordRepository->findByUuid($uuid);

            if (!$user) {
                return $this->errorResponse('messages.books.NoFound', 404);
            }

            return $user;
        } catch (\Exception $e) {
            return $this->errorResponse('messages.books.NoFound', 404);
        }
    }

    /**
     * getAllBorrowingRecords
     *
     * @param  mixed $user
     * @return void
     */
    public function getAllBorrowingRecords($user = null)
    {
        try {
            $user = $this->borrowingRecordRepository->getAll($user);
            return $this->successResponse($user, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.books.NoFound', 404);
        }
    }

    /**
     * searchBorrowBooks
     *
     * @param  mixed $request
     * @return void
     */
    public function searchBorrowBooks(Request $request)
    {
        $input = $request->input('input');
        if (!$input) {
            return $this->errorResponse('messages.search', 400);
        }
        $request->validate([
            'input' => 'required|string|max:255',
        ]);
        $result = $this->borrowingRecordRepository->searchFunction($input);
        if ($result->isEmpty()) {
            return $this->errorResponse('messages.user,notfound', 400);
        }
        return response()->json($result);
    }


    /**
     * borrowreturnedBooks
     *
     * @param  mixed $id
     * @param  mixed $user
     * @return void
     */
    public function borrowreturnedBooks($id, $user)
    {
        try {
            $borrow = $this->borrowingRecordRepository->getUserBorrowedBook($id, $user->id);
            $errors = [];
            if ($borrow->returned) {
                $errors[] = ['message' => 'messages.borrow.returned', 'code' => 400];
            }

            $now = Carbon::now();
            $penalty = $this->borrowingRecordRepository->calculatePenalty($borrow->due_date);
            if ($penalty > 0) {
                $borrow->total_penalty = $penalty;
                $borrow->save();
                if (!$borrow->penalty_paid) {
                    $errors[] = ['message' => 'messages.borrow.Penalty_paid', 'code' => 500];
                }
            }
            if (!empty($errors)) {
                return $this->errorResponse($errors[0]['message'], $errors[0]['code']);
            }
            $borrow->returned = true;
            $borrow->return_date = $now;
            $this->bookRepository->updateAvailabilityStatus($borrow->book_id, true);
            $borrow->save();

            Log::channel('return')->info('Book returned successfully', [
                'book_id' => $borrow->book_id,
                'user_id' => $borrow->user_id,
                'return_date' => now()->format('Y-m-d H:i:s')
            ]);
            return $this->successResponse($borrow, 200);
        } catch (Exception $e) {
            return $this->errorResponse('messages.borrow.error', 500);
        }
    }

    /**
     * getReportByUser
     *
     * @param  mixed $userId
     * @return void
     */
    public function getReportByUser($userId)
    {
        try {
            $borrowings = $this->borrowingRecordRepository->getBorrowingsByUserId($userId);
            if ($borrowings->isEmpty()) {
                return $this->errorResponse('messages.borrow.userborrowings', 404);
            }
            $user = $this->borrowingRecordRepository->findById($userId);
            return [
                'user' => $user,
                'borrowings' => $borrowings,
                'status' => 200
            ];
        } catch (Exception $e) {
            return $this->errorResponse('messages.borrow.error', 500);
        }
    }

    /**
     * getReportByBook
     *
     * @param  mixed $bookId
     * @return void
     */
    public function getReportByBook($bookId)
    {
        try {
            $borrowings = $this->borrowingRecordRepository->getBorrowingsByBookId($bookId);

            if ($borrowings->isEmpty()) {
                return $this->errorResponse('messages.borrow.bookborrowings', 500);
            }
            $book = $this->borrowingRecordRepository->findById($bookId);

            return [
                'book' => $book,
                'borrowings' => $borrowings,
                'status' => 200
            ];
        } catch (Exception $e) {
            return $this->errorResponse('messages.borrow.error', 500);
        }
    }


    /**
     * getReturnHistory
     *
     * @param  mixed $bookId
     * @param  mixed $userId
     * @return void
     */
    public function getReturnHistory($bookId, $userId)
    {
        try {
            $returnHistory = $this->borrowingRecordRepository->getReturnHistoryByBookIdAndUserId($bookId, $userId);

            if ($returnHistory->isEmpty()) {
                return $this->errorResponse('messages.borrow.returnhistory', 404);
            }
            return ['book' => $returnHistory->first()->book, 'return_history' => $returnHistory, 'status' => 200];
        } catch (Exception $e) {
            return $this->errorResponse('messages.borrow.error', 500);
        }
    }
    /**
     * Method getUserBorrowingStats
     *
     * @return void
     */
    public function getUserBorrowingStats()
    {
        try {
            // Fetch borrowing records grouped by user
            $userStats = $this->borrowingRecordRepository->getUserBorrowingCount();

            if ($userStats->isEmpty()) {
                return $this->errorResponse();
            }

            // Return data to be used in the view
            return $userStats;
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }
    /**
     * Method getReturnStats
     *
     */
    public function getReturnStats()
    {
        try {
            return $this->borrowingRecordRepository->getReturnStats();
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }
}
