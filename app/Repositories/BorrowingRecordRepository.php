<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\BorrowingRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BorrowingRecordRepository extends BaseRepository
{
    /**
     * Method __construct
     *
     * @param BorrowingRecord $model [explicite description]
     *
     */
    /**
     * Method __construct
     *
     * @param BorrowingRecord $model [explicite description]
     *
     */
    public function __construct(BorrowingRecord $model)
    {
        $this->model = $model;
    }


    /**
     * Method hasUserBorrowedBook
     *
     * @param $userId $userId [explicite description]
     * @param $bookId $bookId [explicite description]
     *
     */
    public function hasUserBorrowedBook($userId, $bookId)
    {
        return $this->model->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('returned', false)
            ->exists();
    }

    /**
     * countUserBorrowedBooks
     *
     * @param  mixed $userId
     * @return int
     */
    public function countUserBorrowedBooks(int $userId): int
    {
        return $this->model->where('user_id', $userId)->where('returned', false)->count();
    }


    /**
     * getAll
     *
     * @param  mixed $user
     */
    public function getAll(User $user = null)
    {
        if ($user) {
            return $this->model->with(['user', 'book'])
                ->where('user_id', $user->id)
                ->get();
        }
        return $this->model->with(['user', 'book'])->get();
    }

    /**
     * findActiveById
     *
     * @param  mixed $bookId
     */
    public function  findActiveById($bookId)
    {
        return Book::where('id', $bookId)->whereNull('deleted_at')->first();
    }

    /**
     * getOverdueBorrows
     *
     */
    public function getOverdueBorrows()
    {
        $today = Carbon::now();
        return $this->model->where('due_date', '<', $today)
            ->where('returned', false)
            ->with('user')
            ->get();
    }


    /**
     * getBorrowingsByUserId
     *
     * @param  mixed $userId
     */
    public function getBorrowingsByUserId($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * getBorrowingsByBookId
     *
     * @param  mixed $bookId
     */
    public function getBorrowingsByBookId($bookId)
    {
        return $this->model
            ->where('book_id', $bookId)
            ->get();
    }

    /**
     * getReturnHistoryByBookIdAndUserId
     *
     * @param  mixed $bookId
     * @param  mixed $userId
     */
    public function getReturnHistoryByBookIdAndUserId($bookId, $userId)
    {
        return $this->model->with(['user', 'book'])
            ->where('book_id', $bookId)
            ->where('user_id', $userId)
            ->where('returned', true)
            ->whereNotNull('due_date')
            ->get();
    }


    /**
     * getUserBorrowedBook
     *
     * @param  mixed $bookId
     * @param  mixed $userId
     */
    public function getUserBorrowedBook($bookId, $userId)
    {
        return $this->model->where('book_id', $bookId)
            ->where('user_id', $userId)
            ->where('returned', false)
            ->firstOrFail();
    }
    /**
     * Method getUserBorrowingCount
     *
     * @return void
     */
    public function getUserBorrowingCount()
    {
        return DB::table('borrowing_records')
            ->select(DB::raw('user_id, count(*) as borrowed_count'))
            ->groupBy('user_id')
            ->orderByDesc('borrowed_count')
            ->get();
    }
    /**
     * Method getReturnStats
     *
     * @return void
     */
    public function getReturnStats()
    {
        $borrowings = BorrowingRecord::all();

        $returnedCount = 0;
        $notReturnedCount = 0;
        $penaltyCount = 0;
        foreach ($borrowings as $borrow) {
            if ($borrow->returned) {
                $returnedCount++;
                if ($borrow->total_penalty > 0) {
                    $penaltyCount++;
                }
            } else {
                $notReturnedCount++;
            }
        }
        return [
            'returned' => $returnedCount,
            'notReturned' => $notReturnedCount,
        ];
    }
}
