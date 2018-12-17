<?php
/**
 * @Author     yhz
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppRecordSetcallback extends Base
{

    /**
     *  设置视频录制回调地址
     * recordClk    String  录制文件生成回调地址(http开头)  是
     * @param  [type] $params [description]
     * @return
     * [
     *   code   int 状态码
     *   result  boolean 是否设置成功
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.record.setcallback'],
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
            Monolog::error(' 设置视频录制回调地址失败,[AppRecordSetcallback]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
