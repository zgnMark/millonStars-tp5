<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannelPause extends Base
{

    /**
     * 禁用频道
     * 禁用用户正在直播的频道。
     * cid  String  频道ID，32位字符串 是
     * @param  [type] $params [description]
     * @return
     * [
     *   code    int 状态码
     *   msg String  错误信息
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channel.pause'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('禁用频道失败,[AppChannelPause]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
