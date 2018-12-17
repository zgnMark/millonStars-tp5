<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\controller\v1;

use think\Response;

class Base
{

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param integer $code 状态码
     * @param array   $header 头部
     * @param array   $options 参数
     * @return \think\response\Json
     */
    protected function ajaxReturn($data, $type = 'JSONP', $code = 200, $header = [], $options = [])
    {
        $response = null;

        switch (strtoupper($type)) {
            case 'JSON':
                // 返回JSON数据格式到客户端 包含状态信息
                //$data     = json_encode($data, JSON_UNESCAPED_UNICODE);
                $response = Response::create($data, 'json', $code, $header, $options);
                break;
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                $response = Response::create($data, 'jsonp', $code, $header, $options);
                break;
            case 'XML':
                $response = Response::create($data, 'xml', $code, $header, $options);
                break;

        }

        return $response;
    }

    /**
     * 返回包装器
     *
     * 一般返回两种类型数据，一种是返回列表数据、一种是返回信息类型,返回格式如下列表:
     * {
     *    "ret": 200,
     *    "msg": "",
     *    "data": {
     *        "code": 0,    //状态码，0表示正常获取，1表示用户不存在
     *        "msg": "",
     *        "info": {      //用户信息
     *            "id": "1",    //用户ID
     *            "name": "dogstar",   //帐号
     *            "note": "oschina"   //来源
     *        }
     *    }
     *
     * }

     * {
     *    "ret": 200,
     *    "msg": "",
     *    "data": {
     *        "code": 0,    //状态码，0表示正常获取，1表示用户不存在
     *        "list": [
     *                        {      //用户信息
     *                        "id": "1",    //用户ID
     *                        "name": "dogstar",   //帐号
     *                        "note": "oschina"   //来源
     *                        },
     *                        {      //用户信息
     *                        "id": "1",    //用户ID
     *                        "name": "dogstar",   //帐号
     *                        "note": "oschina"   //来源
     *                        }
     *                ]
     *        "total":100
     *    }
     * }

     * @param  array   $packData [description]
     * @param  string  $jsonp   [description]
     * @return [type]            [description]
     */
    protected function packReturn(array $packData, $jsonp = 'JSON')
    {
        $data        = array();
        $data['ret'] = 200;
        $data['msg'] = '';

        //返回数据
        if (!isset($packData['code'])) {
            throw new \Exception('请先设置code码', 1);
        }
        $data['data'] = $packData;

        return $this->ajaxReturn($data, $jsonp);
    }

}
