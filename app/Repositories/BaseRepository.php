<?php

namespace App\Repositories;

use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    use JsonResponseTrait;

    /**
     * model
     *
     * @var mixed
     */
    protected $model;

    /**
     * Method __construct
     *
     * @param Model $model [explicite description]
     *
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Method all
     *
     */
    public function all()
    {
        try {
            return $this->model->all();
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }

    /**
     * Method create
     *
     * @param array $data [explicite description]
     *
     */
    public function create(array $data)
    {
        try {

            return $this->model->create($data);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }


    /**
     * Method findByUuid
     *
     * @param $uuid $uuid [explicite description]
     *
     */
    public function findByUuid($uuid)
    {
        try {
            return $this->model->where('uuid', $uuid)->first();
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }


    /**
     * Method findById
     *
     * @param $id $id [explicite description]
     *
     */
    public function findById($id)
    {
        try {
            return $this->model->where('id', $id)->first();
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }

    /**
     * Method updateByUuid
     *
     * @param $uuid $uuid [explicite description]
     * @param array $attributes [explicite description]
     *
     */
    public function updateByUuid($uuid, array $attributes)
    {
        try {
            return $this->model->where('uuid', $uuid)->update($attributes);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }

    /**
     * Method softDeleteByUuid
     *
     * @param $uuid $uuid [explicite description]
     *
     */
    public function softDeleteByUuid($uuid)
    {
        try {
            return $this->model->where('uuid', $uuid)->delete();
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }

    /**
     * Method searchFunction
     *
     * @param $input $input [explicite description]
     *
     */
    public function searchFunction($input)
    {
        try {
            return $this->model->search($input);
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }

    /**
     * Method calculatePenalty
     *
     * @param $dueDate $dueDate [explicite description]
     *
     */
    public function calculatePenalty($dueDate)
    {
        try {
            $now = Carbon::now();
            $dueDate = Carbon::parse($dueDate);

            if ($now->greaterThan($dueDate)) {
                $daysOverdue = $now->diffInDays($dueDate);

                return $daysOverdue * 10;
            }

            return 0;
        } catch (\Exception $e) {
            return $this->errorResponse('messages.user.notfound', 404);
        }
    }
}
