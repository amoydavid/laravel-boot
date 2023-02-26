<?php

namespace App\Routing\Api;

use App\Http\Controllers\Api\SystemController;
use Illuminate\Contracts\Routing\Registrar;
use Route;

class SystemRegistrar implements \App\Routing\Contracts\RouteRegistrar
{
    protected string $prefix = 'system';

    public function map(Registrar $registrar): void
    {
        //分成访客和用户两类路由，需要写自己的prefix和name
        Route::prefix($this->prefix)->name($this->prefix.'.')->group(function(){
            $this->guestRoute();
        });

        Route::prefix($this->prefix)->name($this->prefix.'.')->middleware(['auth:sanctum'])->group(function(){
            $this->userRoute();
        });
    }

    protected function guestRoute() {
        Route::get('scene', [SystemController::class, 'parseScene']); //解析小程序码
    }

    protected function userRoute() {
        Route::get('init', [SystemController::class, 'init']);
        Route::name('upload')->post('/upload', [SystemController::class, 'upload']);
    }
}
