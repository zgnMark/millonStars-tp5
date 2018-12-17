<?php
namespace app\admin\controller\v1;

//use app\vendor\log\Monolog;
use \think\Loader;

use app\vendor\cache\Cache;
use app\vendor\log\Monolog;
use Ramsey\Uuid\Uuid;

use \wb\SaeTOAuthV2;
use \wb\SaeTClientV2;

use \qq\QC;

class User extends Common
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
        $id         = input('param.user_id', '');

        $param = [
            'user_id' => $id,
        ];
        $orderSort = [
            'order'       => $order,
            'order_field' => $orderField,
        ];

        $data = Loader::model('Oauth', 'logic')->getList(array_merge($param, ['page' => $page, 'pageSize' => $pageSize]), $orderSort);

        return $this->packReturn([
            'code'  => 0,
            'list'  => $data['list'],
            'total' => $data['total'],
        ]);
    }


 

    /**
     * 微博登陆
     * @return [type] [description]
     */
    public function wblogin()
    {
        $o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
        if (input('?param.code')) {
            $keys = [];
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL;
            try {
                $token = $o->getAccessToken('code', $keys);
            } catch (OAuthException $e) {
            }
        }
        if ($token) {
            session('token', $token);
            setcookie('weibojs_' . $o->client_id, http_build_query($token));
        }
        $c = new SaeTClientV2(WB_AKEY, WB_SKEY, session('token')['access_token']);
        $ms = $c->home_timeline(); // done
        $uid_get = $c->get_uid();
        $uid = $uid_get['uid'];
        $user_message = $c->show_user_by_id($uid);

        try {
            $mobileAreaCode     = input('param.mobileAreaCode', '');
            $mobile             = input('param.mobile', '');

            $sysuserAccountData = [
                'mobile_area_code' => $mobileAreaCode,
                'mobile'           => $mobile,
            ];
            $data = Loader::model('Passport', 'logic')->login($sysuserAccountData);
            //保存redis
            $uuid4 = Uuid::uuid4();
            $uuid  = $uuid4->toString() . $data['id'];
            $cache = new Cache([]);
            $flag  = $cache->setSession($uuid, 'user', $data);
            if ($flag !== true) {
                throw new \Exception("保存用户信息失败", 1);
            }
            //
            $imInfo = Loader::model('Passport', 'logic')->getImInfo($data['id']);
            return $this->packReturn([
                'code'    => 0,
                'token'   => $uuid,
                'imToken' => isset($imInfo['token']) ? $imInfo['token'] : null,
                'accid'   => isset($imInfo['accid']) ? $imInfo['accid'] : null,
                'msg'     => '登录成功',
            ]);
        } catch (\Exception $e) {
            Monolog::error('登录失败,[Api.Passport.login]:' . $e->getMessage(), []);
        }
        return $this->packReturn([
            'code' => 100,
            'msg'  => '登录失败',
        ]);
    }

    public function sina()
    {
        $o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
        $code_url = $o->getAuthorizeURL(WB_CALLBACK_URL);
        $this->redirect($code_url);
    }

    /**
     * qq登陆
     * @return [type] [description]
     */
    public function qqlogin()
    {
        $qc = new \qq\QQ_LoginAction();
        $acs = $qc->qq_callback();
        $oid = $qc->get_openid();
        $loginuser = new User;
        $user_data = $qc->get_user_info();
        $user = User::where('openid', $oid)
                    ->find();
    }

    public function qqcallback()
    {
        $qq_login = new \qq\QQ_LoginAction();
        $qq_login->qq_login();
    }

}
