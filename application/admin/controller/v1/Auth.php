<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\admin\controller\v1;

//use app\vendor\log\Monolog;
use app\vendor\log\Monolog;
use think\Db;
use \think\Loader;

class Auth extends Common
{

    /**
     * 列表
     * @return [type] [description]
     */
    public function getGroupList()
    {
        $page     = input('param.page', 1);
        $pageSize = input('param.pageSize', 20);

        $data = Loader::model('auth', 'logic')->getGroupList(['page' => $page, 'pageSize' => $pageSize]);

        return $this->packReturn([
            'code'  => 0,
            'list'  => $data['list'],
            'total' => $data['total'],
        ]);
    }

    /**
     * 获取用户组列表
     * @return [type] [description]
     */
    public function getAdminGroupList()
    {
        $id            = input('param.id', 0);
        $userGroupData = Db::table('auth_group_access')->where(['uid' => (int) $id])->select();
        $userGroupData = array_column($userGroupData, 'group_id');

        $groupData  = Db::table('auth_group')->where(['status' => 1])->select();
        $selectData = [];
        foreach ($groupData as $k => $v) {
            if (in_array($v['id'], $userGroupData)) {
                $selectData[] = $v['id'];
            }
        }
        return $this->packReturn([
            'code'       => 0,
            'list'       => $groupData,
            'selectData' => $selectData,
        ]);
    }

    /**
     * 添加用户组
     * @return [type] [description]
     */
    public function saveAdminGroup()
    {
        $id     = input('param.id', 0);
        $groups = input('param.groups', 0);

        try {
            //删除之前
            $flag = Db::table('auth_group_access')->where(['uid' => (int) $id])->delete();
            if ($flag === false) {
                throw new \Exception("删除之前的用户所属用户组失败", 1);
            }
            $groups = explode(',', $groups);
            foreach ($groups as $k => $v) {
                Db::table('auth_group_access')->insert(['uid' => $id, 'group_id' => $v]);
            }
        } catch (\Exception $e) {
            return $this->packReturn([
                'code' => 100,
                'msg'  => $e->getMessage(),
            ]);
        }
        return $this->packReturn([
            'code' => 0,
            'msg'  => '编辑成功',
        ]);
    }

    /**
     * 保存组
     * @return [type] [description]
     */
    public function saveGroup()
    {
        try {
            $param            = [];
            $param['id']      = input('param.id', 0);
            $param['title']   = input('param.title', '');
            $param['remarks'] = input('param.remarks', '');
            if (isset($_POST['rules'])) {
                $param['rules'] = implode(',', $_POST['rules']);
            } else {
                $param['rules'] = '';
            }

            $data = Loader::model('auth', 'logic')->saveGroup($param);
            return $this->packReturn([
                'code' => 0,
                'msg'  => empty($param['id']) ? '新增成功' : '编辑成功',
            ]);
        } catch (\Exception $e) {
            Monolog::error('新增管理组失败' . $e->getMessage(), $param);
            return $this->packReturn([
                'code' => 100,
                'msg'  => $e->getMessage(),
            ]);
        }

    }

    /**
     * 删除
     * @return [type] [description]
     */
    public function delGroupById()
    {
        //todo 要把auth_group_access删除
        $id   = input('param.id', 0);
        $flag = Db::table('auth_group')->where(['id' => (int) $id])->update(['status' => 0]);
        if ($flag === false) {
            return $this->packReturn([
                'code' => 100,
                'msg'  => '删除失败',
            ]);
        } else {
            return $this->packReturn([
                'code' => 0,
                'msg'  => '删除成功',
            ]);
        }
    }

    /**
     * 获取一条组
     * @return [type] [description]
     */
    public function getGroupById()
    {
        $id   = input('param.id', 0);
        $data = Db::table('auth_group')->where(['id' => (int) $id])->find();
        return $this->packReturn([
            'code' => 0,
            'info' => $data,
        ]);
    }

    /**
     * 保存规则
     * @return [type] [description]
     */
    public function saveRule()
    {
        try {
            $param              = [];
            $param['id']        = input('param.id', 0);
            $param['name']      = input('param.name', '');
            $param['title']     = input('param.title', '');
            $param['type']      = input('param.type', 0);
            $param['condition'] = input('param.condition', 1);
            $param['pid']       = input('param.pid', 0);
            $data               = Loader::model('auth', 'logic')->saveRule($param);
            return $this->packReturn([
                'code' => 0,
                'msg'  => $param['id'] != 0 ? '编辑成功' : '新增成功',
            ]);
        } catch (\Exception $e) {
            Monolog::error('新增规则失败' . $e->getMessage(), $param);
            return $this->packReturn([
                'code' => 100,
                'msg'  => $e->getMessage(),
            ]);
        }
    }

    /**
     * 列表
     * @return [type] [description]
     */
    public function getRuleList()
    {
        $data = Loader::model('auth', 'logic')->getRuleList();
        return $this->packReturn([
            'code' => 0,
            'list' => $data,
        ]);
    }
    /**
     * 获取一条组
     * @return [type] [description]
     */
    public function getRuleById()
    {
        $id   = input('param.id', 0);
        $data = Db::table('auth_rule')->where(['id' => (int) $id])->find();
        return $this->packReturn([
            'code' => 0,
            'info' => $data,
        ]);
    }

    /**
     * 删除
     * @return [type] [description]
     */
    public function delRuleById()
    {
        $id   = input('param.id', 0);
        $data = Db::table('auth_rule')->where(['id' => (int) $id])->find();
        if (isset($data['pid']) && $data['pid'] == 0) {
            $childData = Db::table('auth_rule')->where(['pid' => (int) $id, 'status' => 1])->find();
            if (!empty($childData)) {
                return $this->packReturn([
                    'code' => 100,
                    'msg'  => '删除失败,请先删除子项',
                ]);
            }
        }

        $flag = Db::table('auth_rule')->where(['id' => (int) $id])->update(['status' => 0]);
        if ($flag === false) {
            return $this->packReturn([
                'code' => 100,
                'msg'  => '删除失败',
            ]);
        } else {
            return $this->packReturn([
                'code' => 0,
                'msg'  => '删除成功',
            ]);
        }
    }

    /**
     * 获取组权限列表
     * @return [type] [description]
     */
    public function getGroupRuleList()
    {
        $id   = input('param.id', 0);
        $data = Loader::model('auth', 'logic')->getGroupRuleList($id);
        return $this->packReturn([
            'code'       => 0,
            'list'       => $data['list'],
            'selectData' => $data['selectData'],
        ]);
    }

    //获取用户权限
    public function getUserAccessLists()
    {
        $userData = $this->getLoginUser();
        if ($userData['id'] == 1) {
            $ruleData = Db::table('auth_rule')->where(['status' => 1])->select();
            $ruleData = array_column($ruleData, 'name');
        } else {
            //获取指定权限
            $groupData = $ruleData = Db::table('auth_group_access')->where(['uid' => $userData['id']])->select();
            if (!empty($groupData)) {
                //获取拥有用户组所有权限
                $groupData = array_column($groupData, 'group_id');
                $authGroup = Db::table('auth_group')->where(['id' => ['in', $groupData]])->select();
                $rules     = [];
                foreach ($authGroup as $k => $v) {
                    $rule  = explode(',', $v['rules']);
                    $rules = array_merge($rules, $rule);
                }
                $ruleData = Db::table('auth_rule')->where(['status' => 1, 'pid' => 1, 'id' => ['in', $rules]])->select();
                $ruleData = array_column($ruleData, 'name');

            } else {
                $ruleData = [];
            }
        }

        return $this->packReturn([
            'code' => 0,
            'list' => $ruleData,
        ]);
    }

}
