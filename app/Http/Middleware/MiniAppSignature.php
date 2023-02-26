<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MiniAppSignature
{
    public function handle(Request $request, Closure $next){
        //todo 验证
        return $next($request);
    }
}
