<?php
namespace app\behavior;

use think\Request;

//过滤请求
class FilterRequest
{
    public function run(&$params)
    {
        if (Request::instance()->isOptions()) {
            exit;
        }
    }
}
