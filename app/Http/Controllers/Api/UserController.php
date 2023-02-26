<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CreateMiniUserRequest;
use App\Http\Requests\Api\User\FetchWxInfoRequest;
use App\Services\MiniUserService;
use Illuminate\Http\Response;


class UserController extends Controller
{
    public function login(CreateMiniUserRequest $request, MiniUserService $miniUserService) {
        $wxUser = $miniUserService->createUser($request);
        $token = $wxUser->createToken("API");
        return Response::ok([
            'token'=>$token->plainTextToken,
            'expires' => $token->accessToken->getAttributeValue("expired_at"),
        ]);
    }

    public function info(FetchWxInfoRequest $request, MiniUserService $miniUserService) {
        $wxUser = $miniUserService->fetchWxInfo($request);
        if($wxUser) {
            return Response::ok([
                'user'=>$wxUser
            ]);
        } else {
            return Response::fail("保存用户信息出错");
        }
    }

}
