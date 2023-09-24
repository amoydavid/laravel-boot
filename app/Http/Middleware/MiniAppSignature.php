<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class MiniAppSignature
{
    public function handle(Request $request, Closure $next){
        if (str_contains(strtolower(request()->header('Referer')), strtolower('https://servicewechat.com'))) {
            // 来自小程序请求
            $nonce = $request->header('x-nonce');
            $requestSign = $request->header('x-sign');

            $params = request()->all(); // 获取所有请求参数

            $params['_nonce'] = $nonce;
            ksort($params); // 将参数按key排序

            $signString = '';

            foreach($params as $key => $value) {
                if($value instanceof UploadedFile) {
                    continue;
                }
                if(is_bool($value)) {
                    $value = intval($value);
                }
                if(!is_array($value)) {
                    $signString .= "$key=$value&";
                }
            }

            $signString = rtrim($signString, '&');
            $signString .= config('services.wechat-mini.api-key');
            $sign = md5($signString);

            if (strtoupper($requestSign) == strtoupper($sign)) {
                return $next($request);
            } else {
                return response(json_encode(['code'=>400, 'message' => 'Invalid sign']), 400);
            }
        } else {
            return response(json_encode(['code'=>400, 'message' => 'Invalid request']), 400);
        }
    }
}
