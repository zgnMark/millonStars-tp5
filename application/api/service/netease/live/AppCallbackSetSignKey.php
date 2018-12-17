<?php
/**
 * @Author     yhz
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppCallbackSetSignKey extends Base
{

    /**
     * 设置回调的加签秘钥
     * signKey  String  加签秘钥    是
     * @param  [type] $params [description]
     * @return
     * [
     *  code    int 状态码
     *  result  boolean 是否设置成功
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.callback.setSignkey'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                    'verify'  => false,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('设置回调的加签秘钥失败,[AppCallbackSetSignKey]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
