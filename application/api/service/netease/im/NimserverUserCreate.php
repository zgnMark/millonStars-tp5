<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-13
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\service\netease\im;

use app\vendor\log\Monolog;

class NimserverUserCreate extends Base
{

    /**
     * 创建网易云通信ID
     * 1.第三方帐号导入到网易云通信平台；
     * 2.注意accid，name长度以及考虑管理token。
     *
     * accid   String  是   网易云通信ID，最大长度32字符，必须保证一个
    APP内唯一（只允许字母、数字、半角下划线_、
    @、半角点以及半角-组成，不区分大小写，
    会统一小写处理，请注意以此接口返回结果中的accid为准）。
     * name    String  否   网易云通信ID昵称，最大长度64字符，用来PUSH推送
    时显示的昵称
     * props   String  否   json属性，第三方可选填，最大长度1024字符
     * icon    String  否   网易云通信ID头像URL，第三方可选填，最大长度1024
     * token   String  否   网易云通信ID可以指定登录token值，最大长度128字符，
    并更新，如果未指定，会自动生成token，并在
    创建成功后返回
     * sign    String  否   用户签名，最大长度256字符
     * email   String  否   用户email，最大长度64字符
     * birth   String  否   用户生日，最大长度16字符
     * mobile  String  否   用户mobile，最大长度32字符，只支持国内号码
     * gender  int     否   用户性别，0表示未知，1表示男，2女表示女，其它会报参数错误
     * ex      String  否   用户名片扩展字段，最大长度1024字符，用户可自行扩展，建议封装成JSON字符串

     *
     * @param  [type] $params [description]
     * @return
     * [
     *   accid   String  是   网易云通信ID，最大长度32字符，必须保证一个
    APP内唯一
     *   props   String  否   json属性，第三方可选填，最大长度1024字符
     *   token   String  否   网易云通信ID可以指定登录token值，最大长度128字符
     * ]
     */
    public function request($params)
    {
        try {
            $response = $this->httpClient->post(
                $this->apiConf['nimserver.user.create'],
                [
                    'form_params' => $params,
                    'headers'     => $this->headers,
                ]
            );
            $body = $response->getBody()->getContents();
            Monolog::info(':', [$body]);
            return json_decode($body, true);
        } catch (\Exception $e) {
            Monolog::error('创建网易云通信ID失败,[NimserverUserCreate]' . $e->getMessage(), [$params]);
        }
        return false;
    }

}
