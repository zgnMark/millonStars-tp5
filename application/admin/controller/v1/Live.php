<?php
namespace app\admin\controller\v1;

//use app\vendor\log\Monolog;
use \think\Loader;

class Live extends Common
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
        $id         = input('param.id', '');

        $param = [
            'id' => $id,
        ];
        $orderSort = [
            'order'       => $order,
            'order_field' => $orderField,
        ];

        $data = Loader::model('Live', 'logic')->getList(array_merge($param, ['page' => $page, 'pageSize' => $pageSize]), $orderSort, 'j_live');

        return $this->packReturn([
            'code'  => 0,
            'list'  => $data['list'],
            'total' => $data['total'],
        ]);
    }

    /**
     * 列表
     * @return [type] [description]
     */
    public function neteaselists()
    {

        $page       = input('param.page', 1);
        $pageSize   = input('param.pageSize', 20);
        $order      = input('param.order', 'desc');
        $orderField = input('param.order_field', 'id');
        $id         = input('param.id', '');

        $param = [
            'id' => $id,
        ];
        $orderSort = [
            'order'       => $order,
            'order_field' => $orderField,
        ];

        $data = Loader::model('Live', 'logic')->getList(array_merge($param, ['page' => $page, 'pageSize' => $pageSize]), $orderSort , 'j_netease_live_channel');

        return $this->packReturn([
            'code'  => 0,
            'list'  => $data['list'],
            'total' => $data['total'],
        ]);
    }


    /**
     * 添加一个系统直播房间
     * @return [type] [description]
     */
    public function createLive()
    {
        $id = input('param.id', '');
        if (empty($id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($id)) {
           $id = ['id' => $id];
        }

        $data = Loader::model('Live', 'logic')->lockLive($id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'锁定成功',
        ]);
    }

    /**
     * 删除直播房间
     * @return [type] [description]
     */
    public function deleteLive()
    {
         $id    = input('param.id', '');
         $data = Loader::model('Live', 'logic')->delLive(intval($id));
         return $data;
    }

    /**
     * 更新
     * @return [type] [description]
     */
    public function updateLive()
    {
        $id    = input('param.id', '');
        $nickname = input('param.nickname', '');
        $realname = input('param.realname', '');
        $sex = input('param.sex', '');
        $birthday = input('param.birthday', '');
        $login_num = input('param.login_num', '');
        $signature = input('param.signature', '');
        $avatar_logo = input('param.avatar_logo', '');
        $device_type = input('param.device_type', ''); 


        $where = ['id' => intval($id)];

        $param = [
            'nickname'   => $nickname ,
            'realname'   => $realname ,
            'sex'        => $sex ,
            'birthday'   => $birthday ,
            'login_num'  => $login_num ,
            'signature'  => $signature ,
            'avatar_logo'=> $avatar_logo ,
            'device_type'=> $device_type ,
        ];
        //判断直播房间是否存在
        $data = Loader::model('Live', 'logic')->isExists($where);

        if (!$data) {
            return $this->packReturn([
                'code'  => 2,
                'error' => '直播房间不存在',
            ]);
        }

        if (empty($id) && $id !== 0) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($id)) {
           $id = ['id' => $id];
        }

        $data = Loader::model('Live', 'logic')->updateLive($param,$where);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'更新成功',
        ]);
    }    
    /**
     * 锁定一个系统直播房间
     * @return [type] [description]
     */
    public function lockLive()
    {
        $id = input('param.id', '');
        if (empty($id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($id)) {
           $id = ['id' => $id];
        }

        $data = Loader::model('Live', 'logic')->lockLive($id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'锁定成功',
        ]);
    }
    /**
     * 批量开放系统直播房间
     * @return [type] [description]
     */
    public function openLive()
    {
        $id = input('param.id', '');
        if (empty($id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($id)) {
          //看传过来是什么做处理
        }

        $data = Loader::model('Live', 'logic')->openLive($id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'解锁成功',
        ]);
    }

    /**
     * 批量锁定系统直播房间
     * @return [type] [description]
     */
    public function lockMany()
    {
        $id = input('param.id', '');

        dump($id);
        die();
        if (empty($id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($id)) {
            //看传过来是什么做处理
        }

        $data = Loader::model('Live', 'logic')->lockMany($id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'锁定成功',
        ]);
    }

}
