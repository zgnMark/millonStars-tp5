<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\logic;

use app\vendor\log\Monolog;
use Think\Db;

class Admin extends BaseLogic
{
    /**
     * 创建后台用户
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function createAdmin(array $params)
    {
        //创建
        $data = [
            'username'        => $params['username'],
            'password'        => passwd($params['password']),
            'nickname'        => $params['nickname'],
            'email'           => $params['email'],
            'mobile'          => $params['mobile'],
            'remark'          => $params['remark'],
            'status'          => 0,
            'is_del'          => 0,
            'remark'          => $params['remark'],
            'last_login_time' => date('Y-m-d H:i:s'),
            'create_time'     => date('Y-m-d H:i:s'),
        ];

        $flag = Db::table('j_admin')->insert($data);
        if ($flag === false) {
            Monolog::error('创建后台用户失败,[Admin.AdminLogic.createAdmin]:', [$params]);
            throw new \Exception("创建后台用户失败", 1);
        }
        return Db::table('j_admin')->getLastInsID();
    }

    /**
     * 删除
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function deleteAdmin(array $params)
    {

    }

    /**
     * 修改后台用户信息
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function updateAdmin(array $params)
    {
        $flag = Db::table('j_admin')
            ->where(['id' => $params['id']])
            ->update($params);
        if ($flag === false) {
            Monolog::error('更新后台用户失败,[Admin.AdminLogic.updateAdmin]:', [$params]);
            throw new \Exception("更新后台用户失败", 1);
        }
        return true;
    }

    /**
     * 获取后台用户列表
     * @param  array  $where  [description]
     * @param  string $select [description]
     * @return [type]         [description]
     */
    public function getList(array $where, $order = [], $select = '*')
    {
        $page     = isset($where['page']) ? $where['page'] : null;
        $pageSize = isset($where['pageSize']) ? $where['pageSize'] : null;
        unset($where['page'], $where['pageSize']);

        //剔除空
        $where = array_filter($where, function ($v) {
            if (is_numeric($v) && ($v == 0)) {
                return true;
            }
            return !empty($v);
        });

        /*
        //筛选
        if (!empty($where['idle_flow'])) {
        $where['idle_flow'] = array('elt', $where['idle_flow']);
        }
        //过滤id
        if (isset($where['notIds']) && !empty($where['notIds'])) {
        $where['id'] = array('notin', $where['notIds']);
        unset($where['notIds']);
        }
        if (isset($where['inIds']) && !empty($where['inIds'])) {
        $where['id'] = array('in', $where['inIds']);
        unset($where['inIds']);
        }
         */

        //排序
        if (!empty($order)) {
            $orderSort = "{$order['order_field']}  {$order['order']}";
        } else {
            $orderSort = "id desc";
        }

        //获取总数
        $total = Db::table('j_admin')->where($where)->count();
        //$totalPage = ceil($total / $pageSize);

        $data = [];
        //判断是全部获取还是分页
        if (!empty($pageSize) && $pageSize > 0) {
            if ($total > 0) {
                $page = ($page <= 1) ? 0 : ($page - 1);
                $data = Db::table('j_admin')->limit($page * $pageSize, $pageSize)->where($where)->order($orderSort)->select();
            }
        } else {
            $data = Db::table('j_admin')->where($where)->order($orderSort)->select();
        }

        return array(
            'total' => $total,
            'list'  => $data,
        );
    }

    /**
     * 登录
     * @return [type] [description]
     */
    public function login(array $params)
    {

        $user = Db::table('j_admin')
            ->where('username', 'like', '%' . $params['username'] . '%')
            ->whereOr('email', 'like', '%' . $params['username'] . '%')
            ->whereOr('mobile', 'like', '%' . $params['username'] . '%')
            ->find();

        if (empty($user) || $user['is_del'] == 1) {
            throw new \Exception('不存在该用户', 1);
        }
        if ($user['status'] == 1) {
            throw new \Exception('账户已冻结，请联系管理员', 1);
        }
        if (passwd($params['password']) != $user['password']) {
            throw new \Exception('账户密码不正确', 1);
        }

        //更改用户的登录信息
        Db::table('j_admin')
            ->where(['id' => $user['id']])
            ->update(['last_login_time' => date('Y-m-d H:i:s')]);

        return $user;
    }

}
