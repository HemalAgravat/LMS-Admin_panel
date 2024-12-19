<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\BorrowingRecord;
use Carbon\Carbon;


class NotificationRepository extends BaseRepository
{
    /**
     * Method __construct
     *
     * @param User $model [explicite description]
     *
     */
    public function __construct(Notification $model)
    {
        $this->model = $model;
    }
    /**
     * getOverdueBorrows
     *
     */
    public function getOverdueBorrows()
    {
        $today = Carbon::now();
        return BorrowingRecord::where('due_date', '<', $today)
            ->with('user', 'book')
            ->get();
    }
}
