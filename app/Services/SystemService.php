<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Http\Resources\Admin\MenuSelectNode;
use App\Models\MiniScene;
use App\Models\Permission;
use App\Models\SysRoute;
use App\Models\UploadFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SystemService
{

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
        } else {
            throw new ApiException("上传失败");
        }
        return $UploadFile;
    }

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

    public function createAdmin(string $email, string $password, string $phone, string $name = '')
    {
        $existsUser = User::where('email', $email)->exists();
        if($existsUser) {
            throw new ApiException('同email用户已存在');
        }

        $existsUser = User::where('phone', $phone)->exists();
        if($existsUser) {
            throw new ApiException('同手机号用户已存在');
        }

        $User = new User();
        $User->phone = $phone;
        $User->name = $name?:$email;
        $User->password = password_hash($password, PASSWORD_DEFAULT);
        $User->email = $email;
        $User->email_verified_at = date('Y-m-d H:i:s');
        return $User->save()?$User:false;
    }

    public function updateAdmin(User $User, string $email, string $password, string $phone, string $name = '')
    {
        $existsUser = User::where('email', $email)->where('id', '!=', $User->id)->exists();
        if($existsUser) {
            throw new ApiException('同email用户已存在');
        }

        $existsUser = User::where('phone', $phone)->where('id', '!=', $User->id)->exists();
        if($existsUser) {
            throw new ApiException('同手机号用户已存在');
        }

        $User->phone = $phone;
        $User->name = $name?:$email;
        if($password) {
            $User->password = password_hash($password, PASSWORD_DEFAULT);
        }
        $User->email = $email;
        return $User->save()?$User:false;
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

    private function createTree(&$list, &$parent):array
    {
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
            unset($parent[$k]);
        }
        return $tree;
    }

    public function permissionTreeResponse(Request $request, string $responseClass = null, $permissionIds = null):array
    {
        if ($responseClass === null) {
            $responseClass = \App\Http\Resources\Admin\Permission::class;
        }
        $query = Permission::where("type", '=', 0);
        if($responseClass == MenuSelectNode::class) {
            $query->with('routeRelations');
        }
        if ($permissionIds !== null) {
            $query->whereIn('id', $permissionIds);
        }
        $arr = $query->orderBy('parent_id')->get();
        $map = [];
        foreach($arr as $_item) {
            if(empty($map[$_item->parent_id])) {
                $map[$_item->parent_id] = [];
            }
            $map[$_item->parent_id][] = $responseClass::make($_item)->toArray($request);
        }

        $tree = $this->createTree($map, $map[0]);

        if ($permissionIds !== null ) {
            $extraIds = [];
            // 仍有独立节点的，要合并父节点后再树型化
            foreach($map as $_items) {
                if ($_items) {
                    foreach($_items as $_item) {
                        $parentIds = $this->fetchParentId($_item['id']);
                        $extraIds = array_merge($extraIds, $parentIds);
                    }
                }
            }
            if (count($extraIds) > 0) {
                return $this->permissionTreeResponse($request, $responseClass, array_merge($permissionIds, $extraIds));
            }
        }


        if($map) {
            return $tree;
        } else {
            return [];
        }
    }

    private function fetchParentId($permId)
    {
        $parentIds = [];
        do {
            $perm = Permission::where('id', $permId)->first();
            $parentIds[] = $perm->parent_id;
        }while($perm->parent_id == 0);
        return $parentIds;
    }

    /**
     * @param Request $request
     * @param JsonResource|string $responseClass
     * @return array
     */
    public function routeTreeResponse(Request $request, JsonResource|string $responseClass):array
    {
        $query = SysRoute::query();
        $arr = $query->orderBy('parent_id')->orderBy('route')->get();
        $map = [];
        foreach($arr as $_item) {
            if(empty($map[$_item->parent_id])) {
                $map[$_item->parent_id] = [];
            }
            $map[$_item->parent_id][] = $responseClass::make($_item)->toArray($request);
        }

        if($map) {
            return $this->createTree($map, $map[0]);
        } else {
            return [];
        }
    }
}
