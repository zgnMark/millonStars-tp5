<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\controller\v1;

use Auth\Auth;
use think\request;
use think\Session;
use \think\Loader;

class Publicity extends Base
{

    public function test()
    {
        $a = Auth::instance([
            'auth'      => [
                'auth_on'           => 1, // 权限开关
                'auth_type'         => 2, // 认证方式，1为实时认证；2为登录认证。
                'public_auth'       => '',
                'auth_group'        => 'auth_group', // 用户组数据表名
                'auth_group_access' => 'auth_group_access', // 用户-用户组关系表
                'auth_rule'         => 'auth_rule', // 权限规则表
                'auth_user'         => 'j_admin', // 用户信息表
            ],

            'db_config' => [
                'hostname' => '127.0.0.1',
                'username' => 'root',
                'password' => '123456',
                'hostport' => '3306',
                'database' => 'juxing_db',
                'charset'  => 'utf8',
            ],
        ]);

        if ($a->check('/a/bf', 1)) {

        } else {
            echo 111111;
        }
    }

    /**
     * 登录方法
     * @return [type] [description]
     */
    public function login()
    {

        $username = input('post.username', '');
        $password = input('post.password', '');

        try {
            $user = Loader::model('admin', 'logic')->login(['username' => $username, 'password' => $password]);

            Session::set('user', $user);
            return $this->packReturn([
                'code'  => 0,
                'token' => session_id(),
                'msg'   => '登录成功',
            ]);

        } catch (\Exception $e) {
            return $this->packReturn([
                'code' => 100,
                'msg'  => $e->getMessage(),
            ]);
        }
    }

    /**
     * 好传
     * @return [type] [description]
     */
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            // 成功上传后 获取上传信息
            return $this->packReturn([
                'code' => 0,
                'info' => DS . 'uploads' . DS . $info->getSaveName(),
                'msg'  => '上传成功',
            ]);
        } else {
            // 上传失败获取错误信息
            return $this->packReturn([
                'code' => 100,
                'msg'  => $file->getError(),
            ]);
        }
    }

}
