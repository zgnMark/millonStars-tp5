<?php
/**
 * @Author     yhz
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class ChannelResetRecord extends Base
{

    /**
     * 录制重置
     * cid  String  频道ID，32位字符串 是
     * @param  [type] $params [description]
     * @return
     * [
     *   code   int 状态码
     *   msg    String  错误信息
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channel.resetRecord'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('录制重置失败,[ChannelResetRecord]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
