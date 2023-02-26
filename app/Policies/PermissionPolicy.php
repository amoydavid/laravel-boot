<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy extends Policy
{
    public function create(User $user) {
        return true;
    }

    public function update(User $user) {
        return true;
    }

    public function destroy(User $user) {
        return true;
    }
}
