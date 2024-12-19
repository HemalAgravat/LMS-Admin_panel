<?php

namespace App\Services;

use App\Notifications\OverdueNotification;
use App\Repositories\NotificationRepository;
use App\Traits\JsonResponseTrait;

class NotificationService
{

    use JsonResponseTrait;
    /**
     * notificationRepository
     *
     * @var mixed
     */
    protected $notificationRepository;

    /**
     * __construct
     *
     * @param  mixed $notificationRepository
     * @return void
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * sendOverdueNotifications
     *
     * @return void
     */
    public function sendOverdueNotifications()
    {
        $overdueBorrows = $this->notificationRepository->getOverdueBorrows();

        foreach ($overdueBorrows as $borrowing) {
            $penalty = $this->notificationRepository->calculatePenalty($borrowing->due_date);

            $borrowing->user->notify(new OverdueNotification($borrowing, $penalty));
            if ($penalty > 0) {
                $borrowing->total_penalty = $penalty;
                $borrowing->save();
            $this->notificationRepository->create([
                'user_id' => $borrowing->user->id,
                'book_id' => $borrowing->book->id,
                'due_date' => $borrowing->due_date,
                'penalty' => $penalty,
                'is_sent' => now(),
            ]);
        }
    }
}
}
