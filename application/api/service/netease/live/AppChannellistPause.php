<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannellistPause extends Base
{

    /**
     * 批量禁用频道
     * 禁用一组用户正在直播的频道。（注意：每应用每天对禁用和恢复频道的接口总调用上限为400次。单次和批量接口统一计算，批量操作每频道每次操作计一次调用。超限后，批量禁用或恢复接口可能部分执行失败，请以successList返回内容为准。）
     * cidList  JsonArray   频道ID列表  是
     * {"cidList": ["cidxxxxxxxxx", "cidxxxxxxxxx1", "cidxxxxxxxxx2"]}
     * @param  [type] $params [description]
     * @return
     * [
     *   code    int 状态码
     *   msg String  错误信息
     *   successList JsonArray   成功禁用cid列表
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channellist.pause'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('批量禁用频道失败,[AppChannellistPause]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
