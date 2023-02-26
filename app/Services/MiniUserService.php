<?php

namespace App\Services;
use App\Exceptions\ApiException;
use App\Http\Requests\Api\User\CreateMiniUserRequest;
use App\Http\Requests\Api\User\FetchWxInfoRequest;
use App\Models\WxUser;

class MiniUserService
{
    public function createUser(CreateMiniUserRequest $request): WxUser
    {
        $code = $request->get('code');
        $utils = app('easywechat.mini_app.default')->getUtils();
        $response = $utils->codeToSession($code);

        if(!empty($response['errcode'])) {
            throw new ApiException("未获得用户信息");
        }
        // Create user
        $wxUserData = [
            'open_id' => $response['openid'],
            'union_id' => $response['unionid']??'',
            'session_key' => $response['session_key'],
            'type' => WxUser::TYPE_MINI,
        ];
        $wxUser = WxUser::firstOrCreate([
            'open_id' => $response['openid']
        ], $wxUserData);

        if($wxUser->session_key != $response['session_key']) {
            $wxUser->session_key = $response['session_key'];
            $wxUser->save();
        }

        return $wxUser;
    }

    public function fetchWxInfo(FetchWxInfoRequest $request): WxUser|false
    {
        /**
         * @var WxUser $wxUser
         */
        $wxUser = $request->user();
        if(!$wxUser->session_key) {
            throw new ApiException("用户信息不正确");
        }
        $utils = app('easywechat.mini_app.default')->getUtils();
        $userInfo = $utils->decryptSession($wxUser, $request->get('iv'), $request->get('encryptedData'));
        $wxUser->avatar_url = $userInfo['avatarUrl'];
        $wxUser->name = $userInfo['nickName'];
        $wxUser->city = $userInfo['city'];
        $wxUser->province = $userInfo['province'];
        $wxUser->country = $userInfo['country'];
        return $wxUser->save()?$wxUser:false;
    }
}
