<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePasswordPolicy
{
    use HandlesAuthorization;

    public function editProfile(User $user)
    {
        return $user->name != 'Administrator';
    }
}
