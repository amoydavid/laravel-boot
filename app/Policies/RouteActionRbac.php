<?php

namespace App\Policies;

use App\Models\User;

class RouteActionRbac extends Policy
{
    public function visitAction(User $user)
    {
        $baseControllerPath = "App\Http\Controllers\Admin\\";
        $action = str_replace($baseControllerPath, '', \Request::route()->action['controller']);
        return true;
    }
}
