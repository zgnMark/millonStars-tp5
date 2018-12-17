<?php
/**
 * @Author     yhz
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\live;

use app\vendor\log\Monolog;

class AppRecordCallbackQuery extends Base
{

    /**
     *  设置视频录制回调地址查询
     * 暂无			暂无
     * @param  [type] $params [description]
     * @return
     * [
	 *	code	int	错误码
	 *	msg	String	请求结果信息，当code为200时该字段不存在，非200时msg为请求返回错误信息
	 *	ret	json	主要结果集
	 *	callbackUrl	String	回调地址
	 *	lastUpdateTime	String	最近更新时间
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['app.record.callbackQuery'],
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
            Monolog::error('设置视频录制回调地址查询,[AppRecordCallbackQuery]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
