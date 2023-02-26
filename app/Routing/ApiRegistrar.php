<?php

namespace App\Routing;

use App\Routing\Api\SystemRegistrar;
use App\Routing\Api\UserRegistrar;
use App\Routing\Concerns\MapRouteRegistrars;
use Illuminate\Contracts\Routing\Registrar;

class ApiRegistrar implements Contracts\RouteRegistrar
{
    use MapRouteRegistrars;

    protected string $prefix = 'api/mini';
    protected string $name = 'api.';

    // 子路由挂载到这里
    protected array $registrars = [
        UserRegistrar::class,
        SystemRegistrar::class,
    ];

    public function map(Registrar $registrar): void
    {
        // 这里可以直接写路由规则
//        $registrar->get('/', function (){
//            return ['App' => app()->version()];
//        })->name('index');

//        \Route::get('/hello', function (){
//            return 'hello';
//        });

        // 添加子路由
        \Route::prefix($this->prefix)->name($this->name)
            ->middleware(['api', 'mini-sign'])
            ->group(function()use($registrar){
            $this->mapRoutes($registrar, $this->registrars);
        });

    }
}
