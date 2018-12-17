<?php
namespace app\admin\controller\v1;

//use app\vendor\log\Monolog;
use \think\Loader;

class Singer extends Common
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
        $orderField = input('param.order_field', 'user_id');
        $id         = input('param.id', '');

        $param = [
            'id' => $id,
        ];
        $orderSort = [
            'order'       => $order,
            'order_field' => $orderField,
        ];

        $data = Loader::model('Singer', 'logic')->getList(array_merge($param, ['page' => $page, 'pageSize' => $pageSize]), $orderSort);

        return $this->packReturn([
            'code'  => 0,
            'list'  => $data['list'],
            'total' => $data['total'],
        ]);
    }

    /**
     * 添加一个系统用户
     * @return [type] [description]
     */
    public function createUser()
    {
        $user_id = input('param.user_id', '');
        if (empty($user_id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($user_id)) {
           $user_id = ['user_id' => $user_id];
        }

        $data = Loader::model('User', 'logic')->lockUser($user_id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'锁定成功',
        ]);
    }

    /**
     * 删除用户
     * @return [type] [description]
     */
    public function deleteUser()
    {
         $user_id    = input('param.user_id', '');
         $data = Loader::model('User', 'logic')->delUser(intval($user_id));
         return $data;
    }

    /**
     * 更新
     * @return [type] [description]
     */
    public function updateUser()
    {
        $user_id     = input('param.user_id', '');
        $nickname    = input('param.nickname', '');
        $realname    = input('param.realname', '');
        $sex         = input('param.sex', '');
        $birthday    = input('param.birthday', '');
        $login_num   = input('param.login_num', '');
        $signature   = input('param.signature', '');
        $avatar_logo = input('param.avatar_logo', '');
        $device_type = input('param.device_type', ''); 


        $where = ['user_id' => intval($user_id)];

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
        //判断用户是否存在
        $data = Loader::model('User', 'logic')->isExists($where);

        if (!$data) {
            return $this->packReturn([
                'code'  => 2,
                'error' => '用户不存在',
            ]);
        }

        if (empty($user_id) && $user_id !== 0) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($user_id)) {
           $user_id = ['user_id' => $user_id];
        }

        $data = Loader::model('User', 'logic')->updateUser($param,$where);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'更新成功',
        ]);
    }    
    /**
     * 锁定一个系统用户
     * @return [type] [description]
     */
    public function lockUser()
    {
        $user_id = input('param.user_id', '');
        if (empty($user_id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($user_id)) {
           $user_id = ['user_id' => $user_id];
        }

        $data = Loader::model('User', 'logic')->lockUser($user_id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'锁定成功',
        ]);
    }
    /**
     * 批量开放系统用户
     * @return [type] [description]
     */
    public function openUser()
    {
        $user_id = input('param.user_id', '');
        if (empty($user_id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($user_id)) {
          //看传过来是什么做处理
        }

        $data = Loader::model('User', 'logic')->openUser($user_id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'解锁成功',
        ]);
    }

    /**
     * 批量锁定系统用户
     * @return [type] [description]
     */
    public function lockMany()
    {
        $user_id = input('param.user_id', '');

        if (empty($user_id)) {
             return $this->packReturn([
                    'code'  => 1,
                    'error' => 'id不能为空',
                ]);
        }
        if (!is_array($user_id)) {
            //看传过来是什么做处理
        }

        $data = Loader::model('User', 'logic')->lockUser($user_id);
        return $this->packReturn([
            'code'      => 0,
            'success'   =>'锁定成功',
        ]);
    }

    //查看所有粉丝 
    public function fansList()
    {
        $user_id = input('param.user_id', '');
    }

    //查看所有关注
    public function attentionList()
    {
        $user_id = input('param.user_id', '');
    }


}
