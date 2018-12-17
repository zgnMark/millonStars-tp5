<?php
use think\Route;

Route::group('v1.0.0', function () {
    //注册
    Route::post('/passport/register', 'api/v1.Passport/register');
    //登录
    Route::post('/passport/login', 'api/v1.Passport/login');
    //发送验证码
    Route::post('/passport/sendCode', 'api/v1.Passport/sendCode');

    //获取国家列表
    Route::rule('/country/lists', 'api/v1.Country/lists'); //获取系统国家列表

    //测试
    Route::post('/Test/test', 'api/v1.Test/test');

});
