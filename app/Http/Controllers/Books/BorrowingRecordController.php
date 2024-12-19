<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBorrowBookRequest;
use App\Models\BorrowingRecord;
use App\Models\User;
use App\Services\BorrowingRecordService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Gate;

class BorrowingRecordController extends Controller
{

    use JsonResponseTrait;
    /**
     * borrowingRecordService
     *
     * @var mixed
     */
    protected $borrowingRecordService;


    /**
     * __construct
     *
     * @param  mixed $borrowingRecordService
     * @return void
     */
    public function __construct(BorrowingRecordService $borrowingRecordService)
    {
        $this->borrowingRecordService = $borrowingRecordService;
    }

    /**
     * createBorrowBook
     *
     * @param  mixed $request
     * @return void
     */
    public function createBorrowBook(CreateBorrowBookRequest $request)
    {
        $bookData = $request->validated();
        return $this->borrowingRecordService->borrowBook($bookData);
    }

    /**
     * show
     *
     * @param  mixed $uuid
     * @param  mixed $model
     * @return void
     */
    public function show($uuid, User $model)
    {
        $user = auth()->user();
        $borrowRecord = BorrowingRecord::where('uuid', $uuid)
            ->where('user_id', $user->id)
            ->first();
        if (!$borrowRecord) {
            return $this->errorResponse('messages.user.unauthenticated', 403);
        }
        if (!Gate::allows('curd',  [$user, $borrowRecord]) && ($borrowRecord->user_id !== $user->id)) {
            return $this->errorResponse('messages.user.unauthenticated', 403);
        }

        return $this->borrowingRecordService->show($uuid);
    }
    /**
     * getAllBorrowingRecords
     *
     * @param  mixed $model
     * @return void
     */
    public function getAllBorrowingRecords(User $model)
    {

        $user = auth()->user();
        if (Gate::allows('curd', $model)) {
            $borrowingRecords = $this->borrowingRecordService->getAllBorrowingRecords();
        } else {
            $borrowingRecords = $this->borrowingRecordService->getAllBorrowingRecords($user);
        }
        return response()->json($borrowingRecords);
    }

    /**
     * search
     *
     * @param  mixed $request
     * @return void
     */
    public function search(Request $request)
    {
        return $this->borrowingRecordService->searchBorrowBooks($request);
    }

    /**
     * return
     *
     * @param  mixed $id
     * @return void
     */
    public function return($id)
    {
        $user = auth()->user();
        return $this->borrowingRecordService->borrowreturnedBooks($id, $user);
    }

    /**
     * reportByUser
     *
     * @param  mixed $userId
     * @param  mixed $model
     * @return void
     */
    public function reportByUser($userId, User $model)
    {
        $this->authorize('curd', $model);
        return $this->borrowingRecordService->getReportByUser($userId);
    }

    /**
     * reportByBook
     *
     * @param  mixed $bookId
     * @param  mixed $model
     * @return void
     */
    public function reportByBook($bookId, User $model)
    {
        $this->authorize('curd', $model);
        return $this->borrowingRecordService->getReportByBook($bookId);
    }

    /**
     * returnHistory
     *
     * @param  mixed $bookId
     * @return void
     */
    public function returnHistory($bookId)
    {
        return $this->borrowingRecordService->getReturnHistory($bookId, auth()->id());
    }

    /**
     * Method userStats
     *
     * @param BorrowingRecordService $borrowingRecordService [explicite description]
     *
     */
    public function userStats(BorrowingRecordService $borrowingRecordService)
    {
        $userStats = $borrowingRecordService->getUserBorrowingStats();

        return view('borrowings.userStats', compact('userStats'));
    }

    /**
     * Method showReturnStats
     *
     */
    public function showReturnStats()
    {
        $stats = $this->borrowingRecordService->getReturnStats();
        return view('books.return_stats', [
            'returned' => $stats['returned'],
            'notReturned' => $stats['notReturned'],

        ]);
    }
}
