<?php

namespace App\Routing\Api;

use Illuminate\Contracts\Routing\Registrar;
use Route;

class UserRegistrar implements \App\Routing\Contracts\RouteRegistrar
{

    public function map(Registrar $registrar): void
    {
        //分成访客和用户两类路由，需要写自己的prefix和name
        Route::prefix('user')->name('user.')->group(function(){
            $this->guestRoute();
        });

        Route::prefix('user')->name('user.')->middleware(['auth:sanctum'])->group(function(){
            $this->userRoute();
        });
    }

    protected function guestRoute() {
        Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login'])->name('login');
    }

    protected function userRoute() {

    }
}
