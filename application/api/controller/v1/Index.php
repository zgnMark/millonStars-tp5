<?php
namespace app\api\controller\v1;

class Index
{
    public function index()
    {
        return config('api');
    }
}
