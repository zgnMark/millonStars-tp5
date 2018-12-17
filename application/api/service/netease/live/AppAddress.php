<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppAddress extends Base
{

    /**
     * 重新获取推流地址
     * 用户创建频道时获取的推流地址失效时，重新获取推流地址。
     * cid  String  频道ID，32位字符串 是
     * @param  [type] $params [description]
     * @return
     * [
     *    code    int 状态码
     *    pushUrl String  推流地址
     *    httpPullUrl String  http拉流地址
     *    hlsPullUrl  String  hls拉流地址
     *    rtmpPullUrl String  rtmp拉流地址
     *    msg String  错误信息
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.address'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('重新获取推流地址失败,[AppAddress]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
