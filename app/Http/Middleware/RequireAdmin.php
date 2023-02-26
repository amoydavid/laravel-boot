<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Closure;

class RequireAdmin
{
    public function handle(Request $request, Closure $next){
        if(\Auth::guest()) {
            return "/";
        }else {
            $user = \Auth::user();
            if(!$user instanceof User) {
                return '/';
            }
        }
        return $next($request);
    }
}
