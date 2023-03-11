<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SystemService;
use App\Util\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function info(Request $request) {
        $avatar_url = $request->user()->avatar_url ?: '//lf1-xgcdn-tos.pstatp.com/obj/vcloud/vadmin/start.8e0e4855ee346a46ccff8ff3e24db27b.png';
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response::ok();
    }
}
