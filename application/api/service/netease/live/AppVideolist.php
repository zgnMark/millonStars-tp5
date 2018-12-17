<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppVideolist extends Base
{

    /**
     * 获取录制视频文件列表
     * cid  String  频道ID，32位字符串 是
     * records  int 单页记录数，默认值为10    否
     * pnum int 要取第几页，默认值为1 否
     * @param  [type] $params [description]
     * @return
     * [
      *code    int 状态码
       *msg String  错误信息
       *videoList   JsonArray   录制视频列表
       *video_name  String  录制后文件名，格式为filename_YYYYMMDD-HHmmssYYYYMMDD-HHmmss, 文件名录制起始时间（年月日时分秒) -录制结束时间（年月日时分秒)
       *orig_video_key  String  视频文件在点播桶中的存储路径
       *uid Long    用户ID，是用户在网易云视频与通信业务的标识，用于与其他用户的业务进行区分。通常，用户不需关注和使用。
       *vid Long    视频文件ID
       *pnum    Long    当前页
       *totalRecords    Long    总记录数
       *totalPnum   Long    总页数
       *records Long    单页记录数
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.videolist'],
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
            Monolog::error('获取录制文件视频列表失败,[AppVideolist]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
