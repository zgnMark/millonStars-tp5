<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\Common\logic;

use app\vendor\log\Monolog;
use think\Db;

class Country
{
    /**
     * 获取国家列表
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
        $total = Db::table('j_country')->where($where)->count();
        //$totalPage = ceil($total / $pageSize);

        $data = [];
        //判断是全部获取还是分页
        if (!empty($pageSize) && $pageSize > 0) {
            if ($total > 0) {
                $page = ($page <= 1) ? 0 : ($page - 1);
                $data = Db::table('j_country')->limit($page * $pageSize, $pageSize)->where($where)->order($orderSort)->select();
            }
        } else {
            $data = Db::table('j_country')->where($where)->order($orderSort)->select();
        }
        return array(
            'total' => $total,
            'list'  => $data,
        );
    }

    /**
     * 保存国家
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function saveCountry($params)
    {
        //剔除空
        $data = array_filter($params, function ($v) {
            if (is_numeric($v) && ($v == 0)) {
                return true;
            }
            return !empty($v);
        });
        if (isset($params['id']) && $params['id'] > 0) {
            $flag = Db::table('j_country')
                ->where(['id' => $params['id']])
                ->update(array_merge($params, [
                    'update_time' => date('Y-m-d H:i:s'),
                ]));
            if ($flag === false) {
                Monolog::error('更新失败,[Admin.CountryLogic.saveCountry]:', [$where]);
                throw new \Exception("更新失败", 3);
            }
            return true;
        } else {
            $flag = Db::table('j_country')->insert(array_merge($params, [
                'update_time' => date('Y-m-d H:i:s'),
                'create_time' => date('Y-m-d H:i:s'),
                'status'      => 0,
                'is_del'      => 0,
            ]));
            if ($flag === false) {
                Monolog::error('创建国家失败,[Admin.AdminLogic.saveCountry]:', [$params]);
                throw new \Exception("创建国家失败", 1);
            }
            return Db::table('j_country')->getLastInsID();
        }

    }

    //检测国家是否存在
    public function isExists($data)
    {
        $flag = Db::table('j_country')
            ->where($data)
            ->find();

        if ($flag === false) {
            Monolog::error('查询失败,[Admin.CountryLogic.isExists]:', [$data]);
            throw new \Exception("查询失败", 3);
        }

        return !empty($flag);
    }

}
