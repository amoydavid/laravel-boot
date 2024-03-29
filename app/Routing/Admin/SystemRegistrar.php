<?php

namespace App\Routing\Admin;

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SystemController;
use App\Routing\Contracts\RouteRegistrar;
use Illuminate\Contracts\Routing\Registrar;
use Route;

class SystemRegistrar implements RouteRegistrar
{
    protected string $prefix = 'system';

    public function map(Registrar $registrar): void
    {
        Route::prefix($this->prefix)->name($this->prefix.'.')->group(function() {
            Route::get('option', [SystemController::class, 'fetchOption']);
            Route::post('option', [SystemController::class, 'saveOption']);
            Route::post('upload', [SystemController::class, 'upload']);
            Route::get('fetch-area', [SystemController::class, 'fetchArea']);

            Route::prefix('permission')->middleware(['rbac'])->name('permission.')->group(function() {
                Route::get('', [PermissionController::class, 'index']);
                Route::get('all', [PermissionController::class, 'treeSelect']);
                Route::post('create', [PermissionController::class, 'create']);
                Route::post('update/{permission}', [PermissionController::class, 'update']);
                Route::get('routes', [PermissionController::class, 'routeTree']);
            });

            Route::prefix('user')->middleware(['rbac'])->name('user.')->group(function(){
                Route::get('', [SystemController::class, 'userList']);
                Route::post('create', [SystemController::class, 'userCreate'])->name('create');
                Route::post('update/{user}', [SystemController::class, 'userUpdate']);
                Route::post('delete/{user}', [SystemController::class, 'userDestroy']);
            });

            Route::prefix('role')->middleware(['rbac'])->name('role.')->group(function() {
                Route::get('', [RoleController::class, 'index']);
                Route::get('all', [RoleController::class, 'all']);
                Route::post('create', [RoleController::class, 'create'])->name('create');
                Route::post('update/{role}', [RoleController::class, 'update']);
                Route::post('delete/{role}', [RoleController::class, 'destroy']);
            });

        });
    }
}
