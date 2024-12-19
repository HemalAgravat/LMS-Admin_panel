<?php

namespace App\Repositories;

use App\Models\User;


class UserRepository extends BaseRepository
{
    /**
     * Method __construct
     *
     * @param User $model [explicite description]
     *
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
