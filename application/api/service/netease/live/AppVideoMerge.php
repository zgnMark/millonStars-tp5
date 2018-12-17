<?php
/**
 * @Author     yhz
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppVideoMerge extends Base
{

    /**
     * 录制文件合并
     * outputName   String  合并文件的名称(不能含有空格),无需包含文件后缀    是
     * vidList JsonArray   待合并的视频文件的ID列表(文件ID类型为long),视频文件数量限制为2-20个   是
     * @param  [type] $params [description]
     * @return
     * [
     * code    int 状态码
     * result  boolean 请求是否成功
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.video.merge'],
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
            Monolog::error('录制文件合并失败,[AppVideoMerge]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
