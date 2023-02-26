<?php

namespace App\Routing\Admin;

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SystemController;
use Illuminate\Contracts\Routing\Registrar;
use Route;

class SystemRegistrar implements \App\Routing\Contracts\RouteRegistrar
{
    protected string $prefix = 'system';

    public function map(Registrar $registrar): void
    {
        Route::prefix($this->prefix)->name($this->prefix.'.')->group(function() {
            Route::get('option', [SystemController::class, 'fetchOption']);
            Route::post('option', [SystemController::class, 'saveOption']);
            Route::post('upload', [SystemController::class, 'upload']);
            Route::get('fetch-area', [SystemController::class, 'fetchArea']);

            Route::prefix('permission')->name('permission.')->group(function() {
                Route::get('', [PermissionController::class, 'tree']);
                Route::post('create', [PermissionController::class, 'create']);
                Route::post('update/{permission}', [PermissionController::class, 'update']);
            });

        });
    }
}
