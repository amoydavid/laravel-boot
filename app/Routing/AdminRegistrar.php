<?php

namespace App\Routing;


use App\Http\Controllers\Admin\SystemController;
use App\Routing\Admin\SystemRegistrar;
use App\Routing\Admin\UserRegistrar;
use App\Routing\Admin\DictRegistrar;
use App\Routing\Concerns\MapRouteRegistrars;
use Illuminate\Contracts\Routing\Registrar;
use Route;

class AdminRegistrar implements Contracts\RouteRegistrar
{
    use MapRouteRegistrars;

    protected string $prefix = 'api/admin';
    protected string $name = 'admin.api.';

    // 子路由挂载到这里
    protected array $registrars = [
        SystemRegistrar::class,
        UserRegistrar::class,
        DictRegistrar::class,
    ];

    public function map(Registrar $registrar): void
    {
        // 非登录页只允许登录
        Route::prefix($this->prefix)->name($this->name)
            ->post('login', [\App\Http\Controllers\Admin\SystemController::class, 'login'])->name('login');

        // 添加登录后的子路由
        \Route::prefix($this->prefix)->name($this->name)
            ->middleware(['api', 'auth:admin'])
            ->group(function()use($registrar){
            $this->mapRoutes($registrar, $this->registrars);
            Route::get('info', [SystemController::class, 'info'])->name('info');
        });

    }
}
