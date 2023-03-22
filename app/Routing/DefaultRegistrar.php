<?php

namespace App\Routing;

use App\Http\Controllers\HelloController;
use App\Routing\Concerns\MapRouteRegistrars;
use Illuminate\Contracts\Routing\Registrar;

class DefaultRegistrar implements Contracts\RouteRegistrar
{
    use MapRouteRegistrars;

    protected array $registrars = [
        ApiRegistrar::class,
        AdminRegistrar::class,
    ];


    public function map(Registrar $registrar): void
    {
        $registrar->get('/', [HelloController::class, 'hello'])->name('index');

        $this->mapRoutes($registrar, $this->registrars);
//        \Route::get('/hello', function (){
//            return 'hello';
//        });
        //$registrar->
    }
}
