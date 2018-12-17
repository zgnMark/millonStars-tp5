<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\controller\v1;

use app\admin\model\Monolog as MonologModel;

//use app\vendor\log\Monolog;

class System extends Common
{

    /**
     * 获取系统日志
     * @return [type] [description]
     */
    public function getSystemLog()
    {

        $createTimeMax = input('datemax');
        $createTimeMin = input('datemin');
        $message       = input('message');
        $channel       = input('channel');
        $level         = input('level');

        $where   = [];
        $whereOr = [];

        if (!empty($channel)) {
            $where['channel'] = array('eq', $channel);
        }
        if (!empty($message)) {
            $where['message'] = array('like', '%' . $message . '%');
        }
        if (!empty($level)) {
            $where['level'] = $level;
        }
        if (!empty($createTimeMin)) {
            $where['create_time'] = array('egt', $createTimeMin . ' 00:00:00');
        }
        if (!empty($createTimeMax)) {
            $where['create_time'] = array('elt', $createTimeMax . ' 23:59:59');
        }
        if (!empty($createTimeMin) && !empty($createTimeMax)) {
            $where['create_time'] = array('between', $createTimeMin . ' 00:00:00' . ',' . $createTimeMax . ' 23:59:59');
        }
        $order    = input('order', 'DESC');
        $order_by = input('orderBy', 'id');
        $pageSize = input('pageSize', 20);
        $orders   = [
            $order_by => $order,
        ];

        $data = (new MonologModel())->searchs($where, $whereOr, $orders, $pageSize);

        return $this->packReturn([
            'code'  => 0,
            'list'  => $data['data'],
            'total' => $data['total'],
            'start' => $data['start'],
        ]);
    }
}
