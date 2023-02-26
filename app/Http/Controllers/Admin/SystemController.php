<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AreaItem;
use App\Models\Area;
use App\Models\Option;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\WxUser;
use Carbon\Carbon;
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

        $User = User::where('email', $username)->first();
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

    public function upload(Request $request)
    {
        $file = $request->file('file')?:$request->file('uploadImage');
        $ext = strtolower($file->extension());
        $mime = strtolower($file->getMimeType());
        $fileSize = $file->getSize()?:0;
        $fileName = date("His").'-'.\Str::random();
        $path = date('Ymd');
        $savePath = $file->storeAs($path, $fileName.'.'.$ext);
        if($savePath) {
            $UploadFile = new UploadFile();
            $UploadFile->user()->associate($request->user());
            $UploadFile->path = $savePath;
            $UploadFile->mime = $mime;
            $UploadFile->size = $fileSize;
            $UploadFile->ext = $ext;
            $UploadFile->driver = config('filesystems.default');
            $UploadFile->save();
            $disk = \Storage::disk();
            $url = $disk->url($savePath);
            return Response::ok([
                'media_type'=>strstr($mime,'video')?'video':'image',
                'path' => $savePath,
                'url'=>$url
            ]);
        } else {
            return Response::fail("upload failed");
        }
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
