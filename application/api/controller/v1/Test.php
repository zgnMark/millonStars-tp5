<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\api\controller\v1;


use app\api\service\netease\im\nim;


class Test extends Common
{
    public function test()
    {
        $test = new Nim;
   		$data = ['cid' => '1'];
       	$a = $test->request($data);
        dump($a);
    }
}
