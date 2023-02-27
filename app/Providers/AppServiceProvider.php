<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Response::macro('ok', function ($data = [], $message = 'success') {
            if(is_array($data) && !$data) {
                $data = new \stdClass();
            }
            return new JsonResponse([
                'code' => 0,
                'data' => $data,
                'message' => $message
            ], 200);
        });

        Response::macro('fail', function ($message = 'error', $code = 500, $data = []) {
            if(is_array($data) && !$data) {
                $data = new \stdClass();
            }
            return new JsonResponse([
                'code' => $code?:500,
                'data' => $data,
                'message' => $message
            ], 200);
        });
    }
}
