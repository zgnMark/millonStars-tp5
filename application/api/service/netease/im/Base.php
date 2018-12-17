<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\im;

use GuzzleHttp\Client;

class Base
{
    //api配置链接
    public $apiConf;
    //请求
    public $httpClient;
    //公共头部
    public $headers;

    public function __construct()
    {
        $this->apiConf    = config('api');
        $this->httpClient = new Client([
            'timeout' => 10.0,
        ]);

        //设置头部
        $appKey        = 'ef85064ec3706a1d94a98121498682a2';
        $appSecret     = 'f87c3c672007';
        $nonce         = uniqid();
        $curTime       = time();
        $checkSum      = sha1($appSecret . $nonce . $curTime);
        $this->headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'AppKey'       => $appKey,
            'Nonce'        => $nonce,
            'CurTime'      => $curTime,
            'CheckSum'     => $checkSum,
        ];

    }

}
