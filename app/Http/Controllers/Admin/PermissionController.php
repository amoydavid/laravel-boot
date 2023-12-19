<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Permission\FormRequest;
use App\Http\Resources\Admin\MenuSelectNode;
use App\Http\Resources\Admin\MenuTreeNode;
use App\Http\Resources\Admin\RouteTreeNode;
use App\Models\Permission;
use App\Services\SystemService;
use App\Util\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Attributes\RbacTitle;

#[RbacTitle('权限菜单')]
class PermissionController extends \App\Http\Controllers\Controller
{
    #[RbacTitle('所有权限列表')]
    public function index(Request $request, SystemService $systemService)
    {
        return Response::ok(['items'=>$systemService->permissionTreeResponse($request, MenuSelectNode::class)]);
    }

    /**
     * 权限树形结构，用于添加权限时指定父级
     */
    #[RbacTitle('所有权限(树形列表)')]
    public function treeSelect(Request $request, SystemService $systemService)
    {
        return Response::ok(['items'=>$systemService->permissionTreeResponse($request, MenuTreeNode::class)]);
    }

    /**
     * 后端路由列表，用于菜单选择
     */
    #[RbacTitle('路由树形列表')]
    public function routeTree(Request $request, SystemService $systemService)
    {
        return Response::ok(['items'=>$systemService->routeTreeResponse($request, RouteTreeNode::class)]);
    }

    #[RbacTitle('创建新权限')]
    public function create(FormRequest $request)
    {
        $form = Helper::filterNull($request->only([
            'name', 'parent_id', 'path', 'title', 'component',
            'show_parent', 'frame_src', 'rank', 'icon', 'type', 'hidden', 'affix',
            'route_ids'
        ]));

        $permission = Permission::create($form);
        if($permission) {
            $permission->updateRoutes($form['route_ids']??[]);
            return Response::ok();
        } else {
            return Response::fail('保存出错');
        }
    }

    #[RbacTitle('编辑权限')]
    public function update(FormRequest $request, Permission $permission)
    {
        $form = Helper::filterNull($request->only([
            'name', 'parent_id', 'path', 'title', 'component',
            'show_parent', 'frame_src', 'rank', 'icon', 'type', 'hidden', 'affix',
            'route_ids'
        ]));

        if($permission->update($form)) {
            $permission->updateRoutes($form['route_ids']??[]);
            return Response::ok();
        } else {
            return Response::fail('保存出错');
        }
    }

    #[RbacTitle('删除权限')]
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
