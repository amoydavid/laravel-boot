<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserSetting\PasswordFormRequest;
use App\Http\Resources\Admin\ElePermission;
use App\Http\Resources\Admin\Permission;
use App\Models\RolePermission;
use App\Models\UserRole;
use App\Services\SystemService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function menu(Request $request, SystemService $systemService)
    {
        if($request->user()->id == 1) {
            $permissionIds = null;
        } else {
            $roleIds = UserRole::where('user_id', $request->user()->id)->get()->pluck('role_id')->toArray();
            $permissionIds = RolePermission::whereIn('role_id', $roleIds)->get()->pluck('permission_id')->toArray();
        }

        $isElementClient = $request->header('x-client-type', 'arco-boot') == 'ele-boot';

        return Response::ok($systemService->permissionTreeResponse($request,
            $isElementClient?ElePermission::class:Permission::class,
            $permissionIds));
    }

    public function info(Request $request) {
        $avatar_url = $request->user()->avatar_url ?: '';
        return Response::ok([
            'name' => $request->user()->name,
            'avatar' => $avatar_url,
            'email' => $request->user()->email,
            'job' => 'frontend',
            'jobName' => '前端艺术家',
            'organization' => 'Frontend',
            'organizationName' => '前端',
            'location' => 'beijing',
            'locationName' => '北京',
            'introduction' => '人潇洒，性温存',
            'personalWebsite' => 'https://www.arco.design',
            'phone' => substr_replace($request->user()->phone, "****", 3, 4),
            'registrationDate' => $request->user()->created_at->format("Y-m-d H:i:s"),
            'accountId' => $request->user()->id,
            'certification' => 1,
        ]);
    }

    public function avatarUpload(Request $request, SystemService $systemService) {
        $UploadFile = $systemService->upload($request);
        $disk = \Storage::disk();
        $url = $disk->url($UploadFile->path);

        $request->user()->avatar_url = $url;
        if(!$request->user()->save()) {
            throw new ApiException("更新头像出错");
        }

        return Response::ok([
            'url' => $url
        ]);
    }

    public function modifyPassword(PasswordFormRequest $request)
    {
        $form = $request->only(['new_password']);
        $request->user()->password = password_hash($form['new_password'], PASSWORD_DEFAULT);
        if(!$request->user()->save()) {
            throw new ApiException("更新密码出错");
        }

        return Response::ok();
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response::ok();
    }
}
