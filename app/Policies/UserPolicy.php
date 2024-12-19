<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class UserPolicy
{
    /**
     * Method curd
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function curd(User $user)
    {
        return in_array($user->role, [
            RoleEnum::superadmin->value,
            RoleEnum::admin->value
        ]);
    }
}
