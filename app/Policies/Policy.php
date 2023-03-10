<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class Policy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->id == 1) {
            return true;
        }
    }
}
