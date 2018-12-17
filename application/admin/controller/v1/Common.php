<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\controller\v1;

use Auth\Auth;
use think\Config;
use think\Session;

class Common extends Base
{
    //权限验证
    public function __construct()
    {
        $auth = Auth::instance([
            'auth'      => Config::get('auth.auth'),
            'db_config' => [
                'hostname' => Config::get('database.hostname'),
                'username' => Config::get('database.username'),
                'password' => Config::get('database.password'),
                'hostport' => Config::get('database.hostport'),
                'database' => Config::get('database.database'),
                'charset'  => Config::get('database.charset'),
            ],
        ]);

        $request = \think\Request::instance();
        $user    = $this->getLoginUser();
        if (!empty($user) && $user['id'] != 1) {
            if (!$auth->check($request->module() . '/' . $request->controller() . '/' . $request->action(), $user['id'])) {
                exit(json_encode([
                    "ret"  => 200,
                    "msg"  => "",
                    "data" => [
                        "code" => 1000, //状态码，0表示正常获取，1表示用户不存在
                        "msg"  => "没有权限",
                    ],
                ], JSON_UNESCAPED_UNICODE));
            }
        }

    }

    /**
     * 获取登录用户
     * @return [type] [description]
     */
    public function getLoginUser()
    {
        $user = Session::get('user');
        return empty($user) ? false : $user;
    }

}
