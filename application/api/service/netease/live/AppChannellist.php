<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannellist extends Base
{

    /**
     * 批量恢复频道
     * 获取用户直播频道列表
     * records int 单页记录数，默认值为10    否
     * pnum    int 要取第几页，默认值为1 否
     * ofield  String  排序的域，支持的排序域为：ctime（默认）  否
     * sort    int 升序还是降序，1升序，0降序，默认为desc  否
     * @param  [type] $params [description]
     * @return
     * [
     *   ctime   Long    创建频道的时间戳
     *   cid     String  频道ID，32位字符串
     *   name    String  频道名称
     *   status  int 频道状态（0：空闲； 1：直播； 2：禁用； 3：直播录制）
     *   type    int 频道类型 ( 0 : rtmp, 1 : hls, 2 : http)
     *   uid Long    用户ID，是用户在网易云视频与通信业务的标识，用于与其他用户的业务进行区分。通常，用户不需关注和使用。
     *   needRecord  int 1-开启录制； 0-关闭录制
     *   format  int 1-flv； 0-mp4
     *   duration    int 录制切片时长(分钟)，默认120分钟
     *   filename    String  录制后文件名
     *   msg     String  错误信息
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channellist'],
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
            Monolog::error('批量恢复频道失败,[AppChannellist]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
