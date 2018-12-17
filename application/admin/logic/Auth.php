<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\logic;

use Auth\DataAccess;
use think\Config;
use think\Db;

class Auth extends BaseLogic
{
    private $auth;

    /**
     * 权限
     * @return [type] [description]
     */
    public function __construct()
    {
        $this->auth = new DataAccess([
            'hostname' => Config::get('database.hostname'),
            'username' => Config::get('database.username'),
            'password' => Config::get('database.password'),
            'hostport' => Config::get('database.hostport'),
            'database' => Config::get('database.database'),
            'charset'  => Config::get('database.charset'),
        ]);
    }

    /**
     * 创建组权限
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function saveRule(array $params)
    {
        //创建
        $ret = $this->auth->saveRule(array_merge([
            'type'   => 0,
            'status' => 1], $params));
        return $ret;
    }

    /**
     * 创建组
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function saveGroup(array $params)
    {
        //创建
        $ret = $this->auth->saveGroup(array_merge(['status' => 1], $params));
        return $ret;
    }

    /**
     * 获取组列表
     * @return [type] [description]
     */
    public function getGroupList(array $where)
    {
        $page     = isset($where['page']) ? $where['page'] : null;
        $pageSize = isset($where['pageSize']) ? $where['pageSize'] : null;
        unset($where['page'], $where['pageSize']);

        $where = ['status' => 1];

        //获取总数
        $total = Db::table('auth_group')->where($where)->count();

        $data = [];
        //判断是全部获取还是分页
        if (!empty($pageSize) && $pageSize > 0) {
            if ($total > 0) {
                $page = ($page <= 1) ? 0 : ($page - 1);
                $data = Db::table('auth_group')->limit($page * $pageSize, $pageSize)->where($where)->select();
            }
        } else {
            $data = Db::table('auth_group')->where($where)->select();
        }

        return array(
            'total' => $total,
            'list'  => $data,
        );
    }

    /**
     * 获取规则列表
     * @return [type] [description]
     */
    public function getRuleList()
    {
        $data       = Db::table('auth_rule')->where(['status' => 1])->select();
        $fatherData = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == 0) {
                $v['childData']       = [];
                $fatherData[$v['id']] = $v;
            }
        }
        foreach ($data as $k => $v) {
            if ($v['pid'] != 0) {
                $fatherData[$v['pid']]['childData'][] = $v;
            }
        }
        return $fatherData;
    }

    /**
     * 获取规则列表
     * @return [type] [description]
     */
    public function getGroupRuleList($id)
    {
        $data       = Db::table('auth_rule')->where(['status' => 1])->select();
        $ruleData   = Db::table('auth_group')->where(['id' => $id])->find();
        $ruleData   = explode(',', $ruleData['rules']);
        $fatherData = $selectData = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == 0) {
                $v['childData']       = [];
                $fatherData[$v['id']] = $v;
            }
        }
        foreach ($data as $k => $v) {
            if ($v['pid'] != 0) {
                if (in_array($v['id'], $ruleData)) {
                    $selectData[] = $v['id'];
                }
                $fatherData[$v['pid']]['childData'][] = $v;
            }
        }
        return ['list' => $fatherData, 'selectData' => $selectData];
    }

}
