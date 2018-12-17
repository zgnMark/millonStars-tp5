<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

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
            'timeout' => 5.0,
        ]);

        //设置头部
        $appKey        = '485f3375ff6d96f2b8ae0cb1d881537b';
        $appSecret     = '5cdd1af68bad';
        $nonce         = uniqid();
        $curTime       = time();
        $checkSum      = sha1($appSecret . $nonce . $curTime);
        $this->headers = [
            'Content-Type' => 'application/json',
            'AppKey'       => $appKey,
            'Nonce'        => $nonce,
            'CurTime'      => $curTime,
            'CheckSum'     => $checkSum,
        ];

    }

    public function test($params)
    {

        //带上签名验证
        $params['test'] = 1;
        try {
            $response = $this->httpClient->post($this->apiConf['test'],
                [
                    'form_params' => $param,
                    'headers'     => [],
                    // 'json'    => $param,
                    // 'headers' => [
                    //     'Content-Type' => 'application/json',
                    // ],
                ]
            );
            $body = $response->getBody()->getContents();
            return $body;
        } catch (\Exception $e) {
            Monolog::error('error' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
