<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotificationService;
use App\Traits\JsonResponseTrait;


class NotificationController extends Controller
{
    use JsonResponseTrait;
    /**
     * notificationService
     *
     * @var mixed
     */
    protected $notificationService;

    /**
     * Method __construct
     *
     * @param NotificationService $notificationService [explicite description]
     *
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Method sendOverdueNotifications
     *
     * @return void
     */
    public function sendOverdueNotifications(User $model)
    {
        $this->authorize('curd',  $model);
        $this->notificationService->sendOverdueNotifications();
        return $this->successResponse(null, 'messages.borrow.notifications');
    }
}
