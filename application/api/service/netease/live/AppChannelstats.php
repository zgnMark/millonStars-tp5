<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannelstats extends Base
{

    /**
     * 获取频道状态
     * cid  String  频道ID，32位字符串 是
     * @param  [type] $params [description]
     * @return
     * [
     *    ctime   Long    创建频道的时间戳
     *    cid     String  频道ID，32位字符串
     *    name    String  频道名称
     *    status  int 频道状态（0：空闲； 1：直播； 2：禁用； 3：直播录制）
     *    type    int 频道类型 ( 0 : rtmp, 1 : hls, 2 : http)
     *    uid     Long    用户ID，是用户在网易云视频与通信业务的标识，用于与其他用户的业务进行区分。通常，用户不需关注和使用。
     *    needRecord  int 1-开启录制； 0-关闭录制
     *    format  int 1-flv； 0-mp4
     *    duration    int 录制切片时长(分钟)，默认120分钟
     *    filename    String  录制后文件名
     *    recordStatus    String  网易云内部维护用字段，用户不需关注。后续版本将删除，请勿调用
     *    msg String  错误信息
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channelstats'],
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
            Monolog::error('获取频道状态失败,[AppChannelstats]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
