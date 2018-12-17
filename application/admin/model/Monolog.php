<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\model;

class Monolog extends BaseModel
{
    protected $table = 'j_Monolog';
    public function searchs($where, $whereOr, $order, $pageSize = 10)
    {

        $dataSearch = $this
            ->where($whereOr)
            ->where($where)
            ->order($order)
            ->paginate($pageSize);

        $dataItems = $dataSearch->getCollection()->toArray();
        $data      = [
            'start' => input('post.page'),
            'data'  => $dataItems,
            'total' => $dataSearch->total(),
        ];
        return $data;
    }

}
