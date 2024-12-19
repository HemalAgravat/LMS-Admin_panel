<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Traits\JsonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    use JsonResponseTrait;

    /**
     * userRepository
     *
     * @var mixed
     */
    protected $userRepository;

    /**
     * Method __construct
     *
     * @param UserRepository $userRepository
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Method register
     *
     * @param array $data
     *
     * @return void
     */
    public function register(array $data)
    {
        try {
            $user = $this->userRepository->create($data);
            Log::channel('create_user')->info('New user created', [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]);
            return $this->successResponse($user, 'messages.user.register', 200);
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return $this->errorResponse('messages.user.registration', 500);
        }
    }

    /**
     * Method login
     *
     * @param array $credentials
     *
     */
    public function login(array $credentials)
    {
        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('Login')->accessToken;
                return $this->successResponse(
                    ['token' => $token],
                    'messages.user.login',
                    200
                );
            } else {
                return $this->errorResponse('messages.user.failed', 401);
            }
        } catch (Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return $this->errorResponse('messages.user.failed', 500);
        }
    }


    /**
     * Method logout
     *
     * @return void
     */
    public function logout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->token()->revoke();
                return $this->successResponse(null, 'messages.logout.success', 200);
            }
        } catch (Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return $this->errorResponse('messages.user.failed', 500);
        }
    }

    /**
     * Method updateUser
     *
     * @param string $uuid
     * @param array  $data
     *
     */
    public function updateUser($uuid, array $data)
    {
        try {
            $user = $this->userRepository->findByUuid($uuid);

            if (!$user) {
                return $this->errorResponse('messages.user.notfound', 404);
            }

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $this->userRepository->updateByUuid($uuid, $data);

            $updatedUser = $this->userRepository->findByUuid($uuid);
            Log::channel('update_user')->info('User updated', [
                'user_id' => $updatedUser->id,
                'name' => $updatedUser->name,
                'email' => $updatedUser->email,
            ]);

            return $this->successResponse($updatedUser, 'messages.user.update', 200);
        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return $this->errorResponse('messages.user.update_failed', 500);
        }
    }

    /**
     * Method destroy
     *
     * @param string $uuid
     *
     * @return void
     */
    public function destroy($uuid)
    {
        try {
            $user = $this->userRepository->findByUuid($uuid);

            if (!$user) {
                return $this->errorResponse('messages.user.notfound', 404);
            }
            $user->delete();
            return $this->successResponse(null, 'messages.user.deleted', 200);
        } catch (Exception $e) {
            Log::error('Error deletingdeletingdeleting user: ' . $e->getMessage());
            return $this->errorResponse('messages.user.delete_failed', 500);
        }
    }

    /**
     * Method show
     *
     * @param string $uuid
     *
     * @return void
     */
    public function show($uuid)
    {
        try {
            $user = $this->userRepository->findByUuid($uuid);

            if (!$user) {
                return $this->errorResponse('messages.user.notfound', 404);
            }
            return $this->successResponse($user);
        } catch (Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return $this->errorResponse('messages.user.failed', 500);
        }
    }
    /**
     * getUserRecord
     *
     * @param  mixed $userId
     * @return void
     */
    public function getUserRecord($userId)
    {
        try {
            return $this->userRepository->findById($userId);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }

    /**
     * Method getUserRecords
     *
     * @return void
     */
    public function getUserRecords()
    {
        try {
            $user = $this->userRepository->all();
            return $this->successResponse($user, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.books.NoFound', 404);
        }
    }

    /**
     * Method searchUsers
     *
     * @param Request $request
     *
     * @return void
     */
    public function searchUsers(Request $request)
    {
        try {
            $input = $request->input('input');

            if (!$input) {
                return $this->errorResponse('messages.search_input_missing', 400);
            }

            $users = $this->userRepository->searchFunction($input)->get();

            if ($users->isEmpty()) {
                return $this->errorResponse('messages.search_no_results', 404);
            }
            return $this->successResponse($users);
        } catch (Exception $e) {
            Log::error('Error searching users: ' . $e->getMessage());
        }
    }
}
