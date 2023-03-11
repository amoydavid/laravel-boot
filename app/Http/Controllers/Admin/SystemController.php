<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AreaItem;
use App\Models\Area;
use App\Models\Option;
use App\Models\User;
use App\Services\SystemService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SystemController extends Controller
{

    /**
     * 登录
     * @param Request $request
     * @return mixed
     * @throws ApiException
     */
    public function login(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        if(!$username || !$password) {
            throw new ApiException('请输入用户名和密码');
        }

        if (preg_match('/^1\d{10}$/', $username)) {
            $User = User::where('phone', $username)->first();
        } else {
            $User = User::where('email', $username)->first();
        }

        if(!$User || !password_verify($password, $User->password)) {
            throw new ApiException('用户名或密码不正确');
        }

        $token = $User->createToken('PC');

        return Response::ok([
            'accessToken'=>$token->plainTextToken,
            'expires' => $token->accessToken->getAttributeValue("expired_at"),
            'refreshToken' => '',
            'username' => $User->name,
            'roles' => ['admin']
        ]);
    }

    public function info(Request $request)
    {
        return Response::ok([
            'info' => $request->user()
        ]);
    }

    public function fetchOption(Request $request)
    {
        $key = $request->get('key');
        if(!$key) {
            throw new ApiException('未找到对应值');
        }

        return Response::ok(Option::getValue($key));
    }

    public function saveOption(Request $request)
    {
        $key = $request->get('key');
        if(!$key) {
            throw new ApiException('未找到对应值');
        }

        return Option::saveValue($key, $request->get('data'))?Response::ok():Response::fail('保存数据出错');
    }

    /**
     * 上传
     * @param Request $request
     * @param SystemService $systemService
     * @return mixed
     * @throws ApiException
     */
    public function upload(Request $request, SystemService $systemService)
    {
        $UploadFile = $systemService->upload($request);
        $disk = \Storage::disk();
        $url = $disk->url($UploadFile->path);
        return Response::ok([
            'media_type'=>strstr($UploadFile->mime,'video')?'video':'image',
            'path' => $UploadFile->path,
            'url'=>$url
        ]);
    }

    public function fetchArea(Request $request)
    {
        $parentCode = intval($request->get('area_code'));
        $areas = Area::with('parent')->where('parent_code', $parentCode)->orderBy('pinyin')->get();
        return Response::ok(
            ["items" => AreaItem::collection($areas)]
        );
    }
}
