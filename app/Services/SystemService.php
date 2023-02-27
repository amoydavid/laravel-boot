<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\MiniScene;
use App\Models\Permission;
use App\Models\User;
use App\Models\WxUser;
use App\Util\ImageProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SystemService
{
    public function createQr(Request $request, string $path)
    {
        $wxUid = intval(\Auth::id());

        $oldScene = MiniScene::where('path', $path)
            ->where('wx_uid', $wxUid)
            ->first();
        if(!$oldScene) {
            do {
                $scene = strtolower(Str::random(30));
                $exists = MiniScene::where('code', $scene)->exists();
            }while($exists);

            $MiniScene = new MiniScene();
            $MiniScene->code = $scene;
            $MiniScene->path = $path;
            $MiniScene->wx_uid = $wxUid;
            if(!$MiniScene->save()) {
                throw new ApiException('生成链接出错');
            }
        } else {
            $scene = $oldScene->code;
        }

        $miniApp = app('easywechat.mini_app.default');
        $token = $miniApp->getAccessToken();
//        dd($token->getToken());
        $miniAppClient = $miniApp->getClient();
        $response = $miniAppClient->postJson('/wxa/getwxacodeunlimit', [
            'page' => 'pages/index/reach',
            'scene' => $scene,
            'env_version' => config('app.env') == 'local' ? 'develop': 'release',
            'check_path' => !(config('app.env') == 'local'),
            'is_hyaline' => true,
        ]);
        $miniCode = $response->getContent();
        $response = json_decode($miniCode, true);
        if($response) {
            if($response['errcode'] && $response['errmsg']) {
                throw new ApiException('未能生成小程序码'.$response['errmsg']);
            }
            //if($response[''])
        }

        return $miniCode;

    }

    public function createAdmin(string $username, string $password)
    {
        $existsUser = User::where('email', $username)->exists();
        if($existsUser) {
            throw new ApiException('同名用户已存在');
        }

        $User = new User();
        $User->name = $username;
        $User->password = password_hash($password, PASSWORD_DEFAULT);
        $User->email = $username;
        $User->email_verified_at = date('Y-m-d H:i:s');
        return $User->save();
    }

    public function getGps($address)
    {
        $result = $this->requestQQLbs('/ws/geocoder/v1', ['address'=>$address]);
        if($result['status'] != 0) {
            throw new ApiException('解析GPS地址出错:'.($result['message']??"未知错误"));
        }
        return [
            'lat' => $result['result']['location']['lat']??'',
            'lng' => $result['result']['location']['lng']??'',
        ];
    }

    private function requestQQLbs($path, $query)
    {
        $query['key'] = config("services.qq_map.key");
        $sn = $this->caculateAKSN(config("services.qq_map.sk"), $path, $query);
        $query['sig'] = $sn;

        $uri = 'http://apis.map.qq.com'.$path.'?'.http_build_query($query);

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $uri);

        $result = json_decode($res->getBody(), true);
        return $result;
    }

    private function caculateAKSN( $sk, $url, $querystring_arrays){
        ksort($querystring_arrays);
        $query_arr = [];
        foreach($querystring_arrays as $_key => $_val) {
            $query_arr[] = $_key.'='.$_val;
        }
        $querystring = join('&', $query_arr);
        $before_sign = $url.'?'. ($querystring) . $sk;

        return md5($before_sign);
    }

    private function createTree(&$list, $parent):array
    {
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    public function permissionTreeResponse(Request $request):array
    {
        $arr = Permission::where("type", '=', 0)->orderBy('parent_id')->get();
        $map = [];
        foreach($arr as $_item) {
            if(empty($map[$_item->parent_id])) {
                $map[$_item->parent_id] = [];
            }
            $map[$_item->parent_id][] = \App\Http\Resources\Admin\Permission::make($_item)->toArray($request);
        }

        return $this->createTree($map, $map[0]);
    }
}
