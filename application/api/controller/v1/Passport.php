<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\controller\v1;

use app\vendor\cache\Cache;
use app\vendor\log\Monolog;
use Ramsey\Uuid\Uuid;
use think\Loader;

class Passport extends Common
{
    /**
     * 注册
     * @return [type] [description]
     */
    public function register()
    {
        $mobileAreaCode = input('param.mobileAreaCode', '');
        $mobile         = input('param.mobile', '');
        $loginPassword  = input('param.password', '');
        //qq, wechat, sina ,mobile
        $type = input('param.type', 'mobile');
        //如果第三方登录，先判断之前有没注册过,有则跳转至登录

        $sysuserAccountData = [
            'mobile_area_code' => $mobileAreaCode,
            'mobile'           => $mobile,
            'login_password'   => $loginPassword,
        ];
        $flag = Loader::model('Passport', 'logic')->createUser($sysuserAccountData);
        if ($flag > 0) {
            return $this->packReturn([
                'code' => 0,
                'msg'  => '注册成功！',
            ]);
        } else {
            return $this->packReturn([
                'code' => 100,
                'msg'  => '注册失败！',
            ]);
        }
    }

    /**
     * 登录
     * @return [type] [description]
     */
    public function login()
    {
        try {
            $mobileAreaCode     = input('param.mobileAreaCode', '');
            $mobile             = input('param.mobile', '');
            $loginPassword      = input('param.password', '');
            $sysuserAccountData = [
                'mobile_area_code' => $mobileAreaCode,
                'mobile'           => $mobile,
                'login_password'   => $loginPassword,
            ];
            $data = Loader::model('Passport', 'logic')->login($sysuserAccountData);
            //保存redis
            $uuid4 = Uuid::uuid4();
            $uuid  = $uuid4->toString() . $data['id'];
            $cache = new Cache([]);
            $flag  = $cache->setSession($uuid, 'user', $data);
            if ($flag !== true) {
                throw new \Exception("保存用户信息失败", 1);
            }
            //
            $imInfo = Loader::model('Passport', 'logic')->getImInfo($data['id']);
            return $this->packReturn([
                'code'    => 0,
                'token'   => $uuid,
                'imToken' => isset($imInfo['token']) ? $imInfo['token'] : null,
                'accid'   => isset($imInfo['accid']) ? $imInfo['accid'] : null,
                'msg'     => '登录成功',
            ]);
        } catch (\Exception $e) {
            Monolog::error('登录失败,[Api.Passport.login]:' . $e->getMessage(), []);
        }
        return $this->packReturn([
            'code' => 100,
            'msg'  => '登录失败',
        ]);
    }

    /**
     * 发送验证码
     * @return [type] [description]
     */
    public function sendCode()
    {
        $mobileAreaCode = input('param.mobileAreaCode', '');
        $mobile         = input('param.mobile', '');
        return $this->packReturn([
            'code' => 0,
            'msg'  => '发送成功，测试期间，任意验证码都可以',
        ]);
    }
}
