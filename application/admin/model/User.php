<?php
/**
 * @Author     YHZ
 * @time       2017-12-12
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\model;

class User extends BaseModel
{
    protected $table = 'j_sysuser_user';


    public function searchs($where, $whereOr, $order, $pageSize = 10)
    {

        $dataSearch = $this
            ->where($where)
            ->where($whereOr)
            ->order($order)
            ->paginate($pageSize);


        return $dataSearch; 

        $dataItems = $dataSearch->getCollection()->toArray();
        $data      = [
            'start' => input('page'),
            'data'  => $dataItems,
            'total' => $dataSearch->total(),
        ];

        return $data;

    }

    public function searchOne($user_id)
    {
        # code...
    }

}
