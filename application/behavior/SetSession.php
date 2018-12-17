<?php
namespace app\behavior;

use think\Session;

//过滤请求
class SetSession
{
    public function run(&$params)
    {

        $header = getallheaders();
        if (isset($header['X-Auth-Token']) && !empty($header['X-Auth-Token'])) {
            session_id($header['X-Auth-Token']);
        }

        Session::start();
        // $sessionId = session_id();
        // Monolog::error('session_id:' . $sessionId, []);
        // define('SESSION_ID', $sessionId);
    }
}
