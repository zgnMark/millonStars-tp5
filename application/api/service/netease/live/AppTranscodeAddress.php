<?php
/**
 * @Author     yhz
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppTranscodeAddress extends Base
{

    /**
     * 直播实时转码地址
     * cid  String  频道ID    是
     * @param  [type] $params [description]
     * @return
     * [
      *  code    int 错误码
      *  msg String  错误信息
      *  requestId   String  全局唯一请求id
      *  status  int 拉流转码状态0->暂未开通,1->已开通
      *  pushUrl String  推流地址
      *  httpPullUrl String  http拉流地址
      *  hlsPullUrl  String  hls拉流地址
      *  rtmpPullUrl String  rtmp拉流地址
      *  transcodeHttpPullUrl    String  实时转码http拉流地址,当status=0时该数据结点不存在
      *  transcodeRtmpPullUrl    String  实时转码rtmp拉流地址,当status=0时该数据结点不存在
      *  transcodeHlsPullUrl String  实时转码hls拉流地址,当status=0时该数据结点不存在
      *  1280    String  16:9,1280x720,1600k格式拉流地址
      *  960 String  16:9,960x540,1000k格式拉流地址
      *  640 String  16:9,640x360,600k格式拉流地址
      *  320 String  16:9,320x180,300k格式拉流地址
      *  540 String  9:16,540x960,1000k格式拉流地址
      *  360 String  9:16,360x640,600k格式拉流地址
      *  180 String  9:16,180x320,300k格式拉流地址
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.transcodeAddress'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('获取直播实时转码失败,[AppTranscodeAddress]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
