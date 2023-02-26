<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MiniScene;
use App\Models\Option;
use App\Models\UploadFile;
use App\Models\WxUser;
use App\Util\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SystemController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
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
                'path' => $savePath,
                'url'=>$url
            ]);
        } else {
            return Response::fail("upload failed");
        }

    }


    /**
     * 解析小程序码
     * @param Request $request
     * @return mixed
     */
    public function parseScene(Request $request)
    {
        $scene = $request->get('code');
        if($scene) {
            $MiniScene = MiniScene::where('code', $scene)->first();
            if($MiniScene) {
                return Response::ok([
                    'url' => $MiniScene->path
                ]);
            }
        }
        return Response::fail('未找到对应码');
    }


    public function init() {
        /**
         * @var WxUser $wxUser
         */
        $wxUser = \Auth::user();
        return Response::ok([
            'shareInfo' => [
                'title' => '',
                'imageUrl' => Option::getValue('sys_share_img'),
                'path' => '/pages/index/index'
            ],
        ]);
    }

    public function about() {
        return Response::ok([
            'content' => Helper::xcxHtml(Option::getValue('about', ''))
        ]);
    }
}
