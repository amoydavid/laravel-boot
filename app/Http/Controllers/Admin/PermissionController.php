<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Permission\FormRequest;
use App\Models\Permission;
use App\Services\SystemService;
use App\Util\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends \App\Http\Controllers\Controller
{
    public function tree(Request $request, SystemService $systemService)
    {
        return Response::ok(['menu'=>$systemService->permissionTreeResponse($request)]);
    }

    public function create(FormRequest $request)
    {
        $form = Helper::filterNull($request->only([
            'name', 'parent_id', 'path', 'title', 'component',
            'show_parent', 'frame_src', 'rank', 'icon', 'type'
        ]));

        $permission = Permission::create($form);
        if($permission) {
            return Response::ok();
        } else {
            return Response::fail('保存出错');
        }
    }

    public function update(FormRequest $request, Permission $permission)
    {
        $form = Helper::filterNull($request->only([
            'name', 'parent_id', 'path', 'title', 'component',
            'show_parent', 'frame_src', 'rank', 'icon', 'type'
        ]));

        if($permission->update($form)) {
            return Response::ok();
        } else {
            return Response::fail('保存出错');
        }
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('destroy', $permission);

        if($permission->delete()) {
            return Response::ok();
        } else {
            return Response::fail('删除出错');
        }
    }
}
