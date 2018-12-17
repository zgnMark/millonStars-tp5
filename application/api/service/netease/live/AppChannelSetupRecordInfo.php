<?php
/**
 * @Author     yhz
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannelSetupRecordInfo extends Base
{

    /**
     * 设置录制信息
     *   cid String  频道ID，32位字符串 是
     *   format  int 1-flv； 0-mp4    是
     *   duration    int 录制切片时长(分钟)，5~120分钟  是
     *   filename    String  录制后文件名（只支持中文、字母和数字），格式为filename_YYYYMMDD-HHmmssYYYYMMDD-HHmmss, 文件名录制起始时间（年月日时分秒) -录制结束时间（年月日时分秒)
     * @param  [type] $params [description]
     * @return
     * [
     *   recordClk  String  录制文件生成回调地址(http开头)  是
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.channel.setupRecordInfo '],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('设置录制信息,[AppChannelSetupRecordInfo]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
