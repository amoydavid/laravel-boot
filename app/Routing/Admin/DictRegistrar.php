<?php

namespace App\Routing\Admin;

use App\Http\Controllers\Admin\DictController;
use Illuminate\Contracts\Routing\Registrar;
use Route;

class DictRegistrar implements \App\Routing\Contracts\RouteRegistrar
{

    protected string $prefix = 'dict';

    public function map(Registrar $registrar): void
    {
        Route::prefix($this->prefix)->name($this->prefix.'.')->group(function() {
            Route::get('', [DictController::class, 'index'])->name('index');
            Route::get('{item}', [DictController::class, 'show'])->name('show')
                ->where('item', '[0-9]+');
            Route::get('list/{dictAlias}', [DictController::class, 'valueList'])->name('value-list');
            Route::post('create', [DictController::class, 'store'])->name('create');
            Route::post('update/{item}', [DictController::class, 'update'])->name('update');
            Route::post('delete/{item}', [DictController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('dict-value')->name('dict-value'.'.')->group(function() {
            Route::get('', [DictController::class, 'valueIndex'])->name('index');
            Route::get('{item}', [DictController::class, 'valueShow'])->name('show');
            Route::post('create', [DictController::class, 'valueStore'])->name('create');
            Route::post('update/{item}', [DictController::class, 'valueUpdate'])->name('update');
            Route::post('delete/{item}', [DictController::class, 'valueDestroy'])->name('destroy');
        });
    }
}
