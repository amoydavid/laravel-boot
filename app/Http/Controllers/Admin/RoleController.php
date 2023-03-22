<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Resources\Admin\RoleListItem;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');
        $roles = Role::query()->with('rolePermissions')->paginate($perPage);

        return Response::ok([
            'items' => RoleListItem::collection($roles->items()),
            'total' => $roles->total(),
        ]);
    }

    public function all(Request $request)
    {
        $roles = Role::query()->get();
        return Response::ok(RoleListItem::collection($roles));
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
        if($role->save()) {
            $succ = $role->updatePermissions($request->get('permission_ids', []));
        } else {
            $succ = false;
        }
        return $succ?Response::ok():Response::fail('创建角色出错');
    }


    public function update(Request $request, Role $role)
    {
        $name = trim($request->get('name'));
        if (strlen($name)==0) {
            throw new ApiException('未填写角色名称');
        }

        if (Role::where("name", $name)->whereNot('id', $role->id)->exists()) {
            throw new ApiException('同名角色已存在');
        }

        $role->name = $request->get('name');
        if($role->save()) {
            $succ = $role->updatePermissions($request->get('permission_ids', []));
        } else {
            $succ = false;
        }
        return $succ?Response::ok():Response::fail('修改角色出错');
    }

    public function destroy(Request $request, Role $role)
    {
        $succ = \DB::transaction(function()use($role){
            return $role->delete();
        });
        return $succ?Response::ok():Response::fail('修改角色出错');
    }
}
