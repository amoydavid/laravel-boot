<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per-page');
        $roles = Role::paginate($perPage);

        return Response::ok([
            'items' => $roles->items(),
            'total' => $roles->total(),
        ]);
    }

    public function create(Request $request)
    {
        $name = trim($request->get('name'));
        if (strlen($name)==0) {
            throw new ApiException('未填写角色名称');
        }

        if (Role::where("name", $name)->exists()) {
            throw new ApiException('同名角色已存在');
        }
        $role = new Role();
        $role->name = $request->get('name');
        return $role->save()?Response::ok():Response::fail('创建角色出错');
    }


    public function modify(Request $request, Role $role)
    {
        $name = trim($request->get('name'));
        if (strlen($name)==0) {
            throw new ApiException('未填写角色名称');
        }

        if (Role::where("name", $name)->whereNot('id', $role->id)->exists()) {
            throw new ApiException('同名角色已存在');
        }

        $role->name = $request->get('name');
        return $role->save()?Response::ok():Response::fail('修改角色出错');
    }

    public function destroy(Request $request, Role $role)
    {
        $succ = \DB::transaction(function()use($role){
            return $role->delete();
        });
        return $succ?Response::ok():Response::fail('修改角色出错');
    }
}
