<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannelUpdate extends Base
{

    /**
     * 修改频道
     * 修改直播频道信息
     * name    String  频道名称（最大长度64个字符，只支持中文、字母、数字和下划线） 是
     * cid     String  频道ID，32位字符串 是
     * type    int  频道类型（0:rtmp）    是
     * @param  [type] $params [description]
     * @return
     * [
     *   code    int 状态码
     *   msg     String  错误信息
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channel.update'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('修改频道失败,[AppChannelUpdate]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
