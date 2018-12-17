<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppChannelSetAlwaysRecord extends Base
{

    /**
     * 设置频道为录制状态
     * 设置频道为录制状态，用户推流时，即可录制为视频文件。如无需改变频道录制状态，仅修改频道录制的格式、文件名、切片时长等信息，请调用2.20
     * cid         String  频道ID，32位字符串 是
     * needRecord  int 1-开启录制； 0-关闭录制  是
     * format      int 1-flv； 0-mp4    是
     * duration    int 录制切片时长(分钟)，5~120分钟  是
     * filename    String  录制后文件名（只支持中文、字母和数字），格式为filename_YYYYMMDD-HHmmssYYYYMMDD-HHmmss, 文件名录制起始时间（年月日时分秒) -录制结束时间（年月日时分秒)   否
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
                $this->apiConf['app.channel.setAlwaysRecord'],
                [
                    'json'    => $params,
                    'headers' => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('设置频道为录制状态失败,[AppChannelSetAlwaysRecord]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
