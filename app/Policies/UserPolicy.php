<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }



    public function update(User $authenticatedUser , User $user)

    {
        return $authenticatedUser->id ===$user->id;
    }
}
