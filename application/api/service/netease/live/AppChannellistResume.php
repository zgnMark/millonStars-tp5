<?php
/**
 * @Author     yhz 
 * @time       2017-12-12
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannellistResume extends Base
{

    /**
     * 批量恢复频道
     * cidList    JsonArray  频道Id列表 是
     * @param  [type] $params [description]
     * @return
     * [
     *   code int 状态码
     *   msg String  错误信息
     *   successList JsonArray 成功禁用cid列表
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channellist.resume'],
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
            Monolog::error('恢复频道失败,[AppChannellistResume]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
