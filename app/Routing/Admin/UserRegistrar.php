<?php

namespace App\Routing\Admin;

use App\Http\Controllers\Admin\UserController;
use Illuminate\Contracts\Routing\Registrar;
use Route;

class UserRegistrar implements \App\Routing\Contracts\RouteRegistrar
{

    protected string $prefix = 'user';
    public function map(Registrar $registrar): void
    {

        Route::prefix($this->prefix)->name($this->prefix.'.')->group(function() {
            Route::post('/info', [UserController::class, 'info'])->name('info');
            Route::post('/logout', [UserController::class, 'logout'])->name('logout');
            Route::post('/avatar-upload', [UserController::class, 'avatarUpload'])->name('avatarUpload');
            Route::post('/password-modify', [UserController::class, 'modifyPassword'])->name('passwordModify');
        });
    }
}
