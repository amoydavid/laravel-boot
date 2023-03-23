<?php

namespace App\Http\Middleware;

use App\Models\PermissionRoute;
use App\Models\RolePermission;
use App\Models\SysRoute;
use App\Models\User;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class RouteRbacAuth
{
    public function handle(Request $request, Closure $next){
        /**
         * @var User $user
         */
        $user = $request->user();
        if($user->isSuperAdmin()) {
            return $next($request);
        }

        $method = $request->method();
        $uri = $request->route()->uri;
        $routeModel = SysRoute::where('method', $method)->where('route', $uri)->first();
        if($routeModel) {

            $role_ids = $user->roleRelations->pluck('role_id');
            $permission_ids = RolePermission::whereIn('role_id', $role_ids)->pluck('permission_id');
            $exists = PermissionRoute::query()->whereIn('permission_id', $permission_ids)->where('route_id', $routeModel->id)->exists();
            if($exists) {
                return $next($request);
            }
        }

        throw new AuthorizationException("access denied", 403);
    }
}
