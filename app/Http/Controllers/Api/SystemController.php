<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MiniScene;
use App\Models\Option;
use App\Models\UploadFile;
use App\Models\WxUser;
use App\Services\SystemService;
use App\Util\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SystemController extends Controller
{
    public function upload(Request $request, SystemService $systemService)
    {
        $UploadFile = $systemService->upload($request);
        $disk = \Storage::disk();
        return Response::ok([
            'path' => $UploadFile->path,
            'url' => $disk->url($UploadFile->path),
        ]);
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
