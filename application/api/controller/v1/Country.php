<?php
namespace app\api\controller\v1;

//use app\vendor\log\Monolog;
use \think\Loader;

class Country extends Common
{

    /**
     * 列表
     * @return [type] [description]
     */
    public function lists()
    {
        $order      = input('param.order', 'desc');
        $orderField = input('param.order_field', 'sort');
        $id         = input('param.id', '');
        $param      = [
            'id' => $id,
        ];
        $orderSort = [
            'order'       => $order,
            'order_field' => $orderField,
        ];
        $data = Loader::model('Country', 'logic')->getList($param, $orderSort);

        return $this->packReturn([
            'code' => 0,
            'list' => $data['list'],
        ]);
    }

}
