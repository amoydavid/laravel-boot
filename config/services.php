<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'qq_map' => [
        'key' => env('QQ_MAP_KEY'),
        'sk' => env('QQ_MAP_WEB_SERVICE_SECRET_KEY'),
    ],

    'easy-sms' => [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'qcloud',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            // 'errorlog' => [
            //     'file' => '/tmp/easy-sms.log',
            // ],
            'sendcloud' => [
                'sms_user' => env('EASY_SMS_SEND_CLOUD_USER'),
                'sms_key' => env('EASY_SMS_SEND_CLOUD_KEY'),
                'timestamp' => false, // 是否启用时间戳
            ],
            'aliyun' => [
                'access_key_id' => env('EASY_SMS_ALIYUN_KEY_ID'),
                'access_key_secret' =>  env('EASY_SMS_ALIYUN_API_KEY'),
                'sign_name' => '',
            ],
            'qcloud' => [
                'sdk_app_id' => env('EASY_SMS_QCLOUD_SDK_APP_ID'), // 短信应用的 SDK APP ID
                // 'sdk_app_key' => env('EASY_SMS_QCLOUD_APP_KEY'), // 短信应用的 SDK APP KEY
                'secret_id' => env('EASY_SMS_QCLOUD_SECRET_ID'), // SECRET ID
                'secret_key' => env('EASY_SMS_QCLOUD_SECRET_KEY'), // SECRET KEY
                'sign_name' => env('EASY_SMS_QCLOUD_SIGN_NAME'), // 短信签名
            ],
            //...
        ],
    ],
];
