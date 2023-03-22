<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Permission\FormRequest;
use App\Http\Resources\Admin\MenuSelectNode;
use App\Http\Resources\Admin\MenuTreeNode;
use App\Models\Permission;
use App\Services\SystemService;
use App\Util\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Attributes\MethodTitle;

#[MethodTitle('权限菜单')]
class PermissionController extends \App\Http\Controllers\Controller
{
    #[MethodTitle('所有权限列表')]
    public function index(Request $request, SystemService $systemService)
    {
        return Response::ok(['items'=>$systemService->permissionTreeResponse($request, MenuSelectNode::class)]);
    }

    #[MethodTitle('所有权限(树形列表)')]
    public function treeSelect(Request $request, SystemService $systemService)
    {
        return Response::ok(['items'=>$systemService->permissionTreeResponse($request, MenuTreeNode::class)]);
    }

    #[MethodTitle('创建新权限')]
    public function create(FormRequest $request)
    {
        $form = Helper::filterNull($request->only([
            'name', 'parent_id', 'path', 'title', 'component',
            'show_parent', 'frame_src', 'rank', 'icon', 'type', 'hidden', 'affix'
        ]));

        $permission = Permission::create($form);
        if($permission) {
            return Response::ok();
        } else {
            return Response::fail('保存出错');
        }
    }

    #[MethodTitle('编辑权限')]
    public function update(FormRequest $request, Permission $permission)
    {
        $form = Helper::filterNull($request->only([
            'name', 'parent_id', 'path', 'title', 'component',
            'show_parent', 'frame_src', 'rank', 'icon', 'type', 'hidden', 'affix',
        ]));

        if($permission->update($form)) {
            return Response::ok();
        } else {
            return Response::fail('保存出错');
        }
    }

    #[MethodTitle('删除权限')]
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
