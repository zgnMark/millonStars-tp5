<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\Admin\logic;

use app\vendor\log\Monolog;
use Think\Db;

class live extends BaseLogic
{
    /**
     * 获取直播房间列表
     * @param  array  $where  [description]
     * @param  string $select [description]
     * @return [type]         [description]
     */

    public function getList(array $where, $order = [], $select = '*', $live)
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
        $total = Db::table($live)->where($where)->count();
        //$totalPage = ceil($total / $pageSize);

        $data = [];
        //判断是全部获取还是分页
        if (!empty($pageSize) && $pageSize > 0) {
            if ($total > 0) {
                $page = ($page <= 1) ? 0 : ($page - 1);
                $data = Db::table($live)->limit($page * $pageSize, $pageSize)->where($where)->order($orderSort)->select();
            }
        } else {
            $data = Db::table($live)->where($where)->order($orderSort)->select();
        }
        return array(
            'total' => $total,
            'list'  => $data,
        );
    }

    //锁定直播房间
    public function locklive(array $where)
    {
        $params = ['is_del' => 1];
        $flag = Db::table('j_live')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('锁定直播房间失败,[live.liveLogic.locaklive]:', [$params]);
            throw new \Exception("锁定直播房间失败", 3);
        }
        return true;
    }    

    //解锁直播房间
    public function openlive(array $where)
    {
        $params = ['is_del' => 0];
        $flag = Db::table('j_live')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('解锁直播房间失败,[live.liveLogic.locaklive]:', [$params]);
            throw new \Exception("解锁直播房间失败", 3);
        }
        return true;
    }

    //批量锁定直播房间
    public function lockMany(array $where)
    {
        $params = ['is_del' => 1];
        $flag = Db::table('j_live')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('解锁直播房间失败,[live.liveLogic.locakMany]:', [$params]);
            throw new \Exception("解锁直播房间失败", 3);
        }
        return true;
    }

    //批量解锁直播房间
    public function openMany(array $where)
    {
        $params = ['is_del' => 0];
        $flag = Db::table('j_live')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('解锁直播房间失败,[live.liveLogic.lockMany]:', [$params]);
            throw new \Exception("解锁直播房间失败", 3);
        }
        return true;
    }

    //删除直播房间
    public function dellive(int $id)
    {
        Db::table('j_live')
            ->where('id',$id)
            ->delete();
    }


    //更新直播房间
    public function updatelive(array $params,array $where)
    {
        //剔除空
        $params = array_filter($params, function ($v) {
            if (is_numeric($v) && ($v == 0)) {
                return true;
            }
            return !empty($v);
        });

        $flag = Db::table('j_live')
            ->where($where)
            ->update($params);

        if ($flag === false) {
            Monolog::error('更新失败,[live.liveLogic.updatelive]:', [$where]);
            throw new \Exception("更新失败", 3);
        }
        return true;
    }

    //检测直播房间是否存在
    public function isExists($data)
    {
        $flag = Db::table('j_live')
            ->where($data)
            ->find();

        if ($flag === false) {
            Monolog::error('查询失败,[live.liveLogic.isExists]:', [$data]);
            throw new \Exception("查询失败", 3);
        }

        return !empty($flag);
    }
    
}
