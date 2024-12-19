<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\JsonResponseTrait;
use App\Http\Requests\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use JsonResponseTrait;

    /**
     * userService
     *
     * @var mixed
     */
    protected $userService;

    /**
     * Method __construct
     *
     * @param UserService $userService [explicite description]
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Method update
     *
     * @param UpdateRequest $request [explicite description]
     * @param $uuid $uuid [explicite description]
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function update(UpdateRequest $request, $uuid, User $model)
    {

        $this->authorize('curd', $model);
        return $this->userService->updateUser($uuid, $request->validated());
    }

    /**
     * Method destroy
     *
     * @param $uuid $uuid [explicite description]
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function destroy($uuid, User $model)
    {
        $this->authorize('curd',  $model);
        return $this->userService->destroy($uuid);
    }

    /**
     * Method show
     *
     * @param $uuid $uuid [explicite description]
     *
     * @return void
     */
    public function show($uuid, User $model)
    {
        $user = auth()->user();
        if (Gate::allows('curd', $model)) {
            return $this->userService->show($uuid);
        }
        if ($user->uuid === $uuid) {
            return $this->userService->show($uuid);
        }
    }


    /**
     * Method UserRecords
     *
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function UserRecords(User $model)
    {

        if (Gate::allows('curd', $model)) {
            Log::info("User is authorized (admin or superadmin).");
            $userRecords = $this->userService->getUserRecords();
        } else {
            $userRecords = $this->userService->getUserRecord(auth()->id());
        }

        return response()->json($userRecords);
    }

    /**
     * Method search
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function search(Request $request, User $model)
    {
        $this->authorize('curd', $model);
        return $this->userService->searchUsers($request);
    }
}
