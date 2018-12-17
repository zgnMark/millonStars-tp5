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

class Singer extends BaseLogic
{
    /**
     * 获取歌手列表
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
        $total = Db::table('j_singer')->where($where)->count();
        //$totalPage = ceil($total / $pageSize);

        $data = [];
        //判断是全部获取还是分页
        if (!empty($pageSize) && $pageSize > 0) {
            if ($total > 0) {
                $page = ($page <= 1) ? 0 : ($page - 1);
                $data = Db::table('j_singer')->limit($page * $pageSize, $pageSize)->where($where)->order($orderSort)->select();
            }
        } else {
            $data = Db::table('j_singer')->where($where)->order($orderSort)->select();
        }
        return array(
            'total' => $total,
            'list'  => $data,
        );
    }

    //锁定歌手
    public function lockSinger(array $where)
    {
        $params = ['is_del' => 1];
        $flag = Db::table('j_singer')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('锁定歌手失败,[Singer.SingerLogic.locakSinger]:', [$params]);
            throw new \Exception("锁定歌手失败", 3);
        }
        return true;
    }    

    //解锁歌手
    public function openSinger(array $where)
    {
        $params = ['is_del' => 0];
        $flag = Db::table('j_singer')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('解锁歌手失败,[Singer.SingerLogic.locakSinger]:', [$params]);
            throw new \Exception("解锁歌手失败", 3);
        }
        return true;
    }

/*    //批量锁定歌手
    public function lockMany(array $where)
    {
        $params = ['is_del' => 1];
        $flag = Db::table('j_singer')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('解锁歌手失败,[Singer.SingerLogic.locakMany]:', [$params]);
            throw new \Exception("解锁歌手失败", 3);
        }
        return true;
    }

    //批量解锁歌手
    public function openMany(array $where)
    {
        $params = ['is_del' => 0];
        $flag = Db::table('j_singer')
            ->where($where)
            ->update($params);
        if ($flag === false) {
            Monolog::error('解锁歌手失败,[Singer.SingerLogic.lockMany]:', [$params]);
            throw new \Exception("解锁歌手失败", 3);
        }
        return true;
    }*/

    //删除歌手
    public function delSinger(int $Singer_id)
    {
        Db::table('j_singer')
            ->where('Singer_id',$Singer_id)
            ->delete();
    }


    //更新歌手
    public function updateSinger(array $params,array $where)
    {
        //剔除空
        $params = array_filter($params, function ($v) {
            if (is_numeric($v) && ($v == 0)) {
                return true;
            }
            return !empty($v);
        });

        $flag = Db::table('j_singer')
            ->where($where)
            ->update($params);

        if ($flag === false) {
            Monolog::error('更新失败,[Admin.SingerLogic.updateSinger]:', [$where]);
            throw new \Exception("更新失败", 3);
        }
        return true;
    }

    //检测歌手是否存在
    public function isExists($data)
    {
        $flag = Db::table('j_singer')
            ->where($data)
            ->find();

        if ($flag === false) {
            Monolog::error('查询失败,[Admin.SingerLogic.isExists]:', [$data]);
            throw new \Exception("查询失败", 3);
        }

        return !empty($flag);
    }
    
}
