<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
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
     * Method register
     *
     * @param UserRequest $request [explicite description]
     *
     * @return void
     */
    public function register(UserRequest $request)
    {
        return $this->userService->register($request->all());
    }

    /**
     * Method login
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function login(Request $request)
    {
        return $this->userService->login($request->only('email', 'password'));
    }

    /**
     * Method logout
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function logout()
    {
        return  $this->userService->logout();
    }
}
