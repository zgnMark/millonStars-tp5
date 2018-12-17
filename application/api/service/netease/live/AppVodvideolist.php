<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppVodVideoList extends Base
{

    /**
     * 获取某一时间范围的录制视频文件列表
     *  cid String  频道ID，32位字符串 是
     *  beginTime   long    查询的起始时间戳(毫秒)    是
     *  endTime long    查询的结束时间戳(毫秒)    是
     *  sort    int 排序字段，取值为0时降序，为1时升序，默认降序 否   
     * @param  [type] $params [description]
     * @return
     * [
     *  code    int 状态码
     *  msg String  错误信息
     *  videoList   JsonArray   录制视频列表
     *  name    String  录制后文件名，格式为filename_YYYYMMDD-HHmmssYYYYMMDD-HHmmss, 文件名录制起始时间（年月日时分秒) -录制结束时间（年月日时分秒)
     *  url String  视频文件在点播桶中的存储路径
     *  vid Long    视频文件ID
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.vodvideolist'],
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
            Monolog::error('获取某一时间范围的录制视频文件列表失败,[AppVodVideoList]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
