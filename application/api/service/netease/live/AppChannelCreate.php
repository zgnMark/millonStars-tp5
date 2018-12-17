<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannelCreate extends Base
{

    /**
     * 创建频道
     * name    String  频道名称（最大长度64个字符，只支持中文、字母、数字和下划线） 是
     * type    int  频道类型（0:rtmp）    是
     * @param  [type] $params [description]
     * @return
     * [
     *   code int 状态码
     *   cid String  频道ID，32位字符串
     *   ctime   Long    创建频道的时间戳
     *   name    String  频道名称
     *   pushUrl String  推流地址
     *   httpPullUrl String  http拉流地址
     *   hlsPullUrl  String  hls拉流地址
     *   rtmpPullUrl String  rtmp拉流地址
     *   msg String  错误信息
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channel.create'],
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
            Monolog::error('创建频道失败,[AppChannelCreate]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
