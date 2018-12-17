<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\model;

class Schedule extends BaseModel
{
    protected $table = 'cmd_schedule';
    public function searchs($where, $whereOr, $order, $pageSize = 10)
    {

        $dataSearch = $this
            ->where($whereOr)
            ->where($where)
            ->order($order)
            ->paginate($pageSize);

        $dataItems = $dataSearch->getCollection()->toArray();
        $data      = [
            'start' => input('page'),
            'data'  => $dataItems,
            'total' => $dataSearch->total(),
        ];

        return $data;

    }

}
