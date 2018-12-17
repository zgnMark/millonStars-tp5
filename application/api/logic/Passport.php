<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\logic;

use app\api\service\netease\im\Manager;
use app\vendor\log\Monolog;
use Ramsey\Uuid\Uuid;
use think\Db;
use think\Request;

class Passport extends BaseLogic
{

    /**
     *
     * @return [type] [description]
     */
    public function __construct()
    {

    }

    /**
     * 登录操作
     * @return [type] [description]
     */
    public function login(array $params)
    {
        $userData = Db::table('j_sysuser_account')->where([
            'mobile_area_code' => $params['mobile_area_code'],
            'mobile'           => $params['mobile'],
        ])->find();
        if (empty($userData)) {
            throw new \Exception("不存在该用户", 1);
        }
        if ($userData['disabled'] != 0) {
            throw new \Exception("已冻结，请联系管理员", 1);
        }
        if ($userData['login_password'] != passwd($params['login_password'])) {
            throw new \Exception("密码有误", 1);
        }
        return $userData;
    }

    /**
     * 获取IM相关信息
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    public function getImInfo($userId)
    {
        $data = Db::table('j_netease_im_user')->where([
            'user_id' => $userId,
        ])->find();
        return $data;
    }

    /**
     * 注册用户
     */
    public function createUser(array $params)
    {
        try {

            Db::startTrans();
            if ($this->checkAccountRegister($params)) {
                throw new \Exception("该用户已存在", 1);
            }

            //创建
            $sysuserAccountData = [
                'account_number'   => null,
                'email'            => null,
                'mobile_area_code' => $params['mobile_area_code'],
                'mobile'           => $params['mobile'],
                'login_password'   => passwd($params['login_password']),
                'account_type'     => 0,
                'disabled'         => 0,
                'create_time'      => date('Y-m-d H:i:s'),
                'modify_time'      => date('Y-m-d H:i:s'),
            ];

            $flag = Db::table('j_sysuser_account')->insert($sysuserAccountData);
            if ($flag === false) {
                throw new \Exception("创建用户失败", 1);
            }
            $userId        = Db::table('j_sysuser_account')->getLastInsID();
            $accountNumber = $this->setAccountNumber($userId);
            $flag          = Db::table('j_sysuser_account')->where(['id' => $userId])->update(['account_number' => $accountNumber]);
            if ($flag === false) {
                throw new \Exception("更新account_number失败", 1);
            }

            //添加信息表
            $sysuserUser = [
                'user_id'         => $userId,
                'nickname'        => empty($params['nickname']) ? $accountNumber : $params['nickname'],
                'realname'        => null,
                'sex'             => isset($params['sex']) ? $params['sex'] : 0,
                'birthday'        => null,
                'login_num'       => 1,
                'reg_ip'          => Request::instance()->ip(),
                'reg_time'        => date('Y-m-d H:i:s'),
                'last_login_ip'   => Request::instance()->ip(),
                'last_login_time' => date('Y-m-d H:i:s'),
                'signature'       => isset($params['signature']) ? $params['signature'] : null,
                'avatar_logo'     => isset($params['avatar_logo']) ? $params['avatar_logo'] : null,
                'device_type'     => 0,
            ];
            $flag = Db::table('j_sysuser_user')->insert($sysuserUser);
            if ($flag === false) {
                throw new \Exception("创建用户详细信息失败", 1);
            }

            //注册网易IM账户
            $uuid4 = Uuid::uuid4();
            $uuid  = $uuid4->toString() . $userId;
            $data  = Manager::call('nimserver.user.create', ['accid' => md5($uuid), 'name' => $sysuserUser['nickname']]);
            Monolog::info('创建网易IM账户:', [$data]);

            if (isset($data['code']) && $data['code'] == 200 && !empty($data['info']['accid'])) {
                $flag = Db::table('j_netease_im_user')->insert([
                    'user_id'     => $userId,
                    'accid'       => $data['info']['accid'],
                    'token'       => $data['info']['token'],
                    'update_time' => date('Y-m-d'),
                    'create_time' => date('Y-m-d'),
                ]);
                if ($flag === false) {
                    throw new \Exception("新增网易IM账户失败", 1);
                }
            } else {
                throw new \Exception("注册网易IM账户失败" . $data['msg'], 1);
            }

            Db::commit();
            return $userId;
        } catch (\Exception $e) {
            Db::rollback();
            Monolog::error('创建用户失败,[Api.Passport.createUser]:' . $e->getMessage(), [$params]);
        }
        return false;
    }

    //用户编号
    private function setAccountNumber($userId)
    {
        $accountNumber = '1' . str_pad($userId, 7, 0, STR_PAD_LEFT);
        $accountData   = Db::table('j_sysuser_account')->where(['account_number' => $accountNumber])->count();
        if (!empty($accountData)) {
            $this->setAccountNumber($userId);
        }
        ($accountData > 0) && $accountNumber = $this->setAccountNumber($userId);
        return $accountNumber;
    }

    /**
     * 检测用户是否被注册
     * [checkAccount description]
     * @return [type] bool，存在返回true,否则返回false
     */
    private function checkAccountRegister(array $params)
    {
        $flag = Db::table('j_sysuser_account')->where([
            'mobile_area_code' => $params['mobile_area_code'],
            'mobile'           => $params['mobile'],
        ])->count();
        return ($flag > 0) ? true : false;
    }

}
