<?php
namespace app\admin\controller\v1;

//use app\vendor\log\Monolog;
use \think\Loader;

class Admin extends Common
{

    /**
     * 列表
     * @return [type] [description]
     */
    public function lists()
    {

        $page       = input('param.page', 1);
        $pageSize   = input('param.pageSize', 20);
        $order      = input('param.order', 'desc');
        $orderField = input('param.order_field', 'id');
        $username   = input('param.username', '');
        $email      = input('param.email', '');
        $mobile     = input('param.mobile', '');
        $status     = input('param.status', '');

        $param = [
            'username' => $username,
            'status'   => $status,
            'email'    => $email,
            'mobile'   => $mobile,
        ];
        $orderSort = [
            'order'       => $order,
            'order_field' => $orderField,
        ];
        $data = Loader::model('admin', 'logic')->getList(array_merge($param, ['page' => $page, 'pageSize' => $pageSize]), $orderSort);

        return $this->packReturn([
            'code'  => 0,
            'list'  => $data['list'],
            'total' => $data['total'],
        ]);
    }

    /**
     * 添加一个用户
     * @return [type] [description]
     */
    public function create()
    {



    }

    /**
     * 删除一个后台用户
     * @return [type] [description]
     */
    public function delete()
    {

    }

    /**
     * 更新
     * @return [type] [description]
     */
    public function update()
    {

    }

}
