<?php
use think\Request;
use think\Route;
Route::rule('/', 'admin/v1.Index/index'); //
Route::group('v1.0.0', function () {
    Route::group('backstage', function () {
        //公共
        Route::rule('/publicity/login', 'admin/v1.Publicity/login'); //登录
        Route::rule('/publicity/upload', 'admin/v1.Publicity/upload'); //上传
        Route::rule('/publicity/test', 'admin/v1.Publicity/test'); //

        //系统
        Route::rule('/getSystemLog', 'admin/v1.System/getSystemLog'); //获取系统日志

        //权限管理
        Route::rule('/admin/lists', 'admin/v1.Admin/lists'); //获取后台用户列表
        Route::rule('/admin/adminGroupList', 'admin/v1.Auth/getAdminGroupList'); //获取后台用户列表

       


        Route::rule('/admin/saveAdminGroup', 'admin/v1.Auth/saveAdminGroup'); //获取后台用户列表


        Route::rule('/group/lists', 'admin/v1.Auth/getGroupList'); //组列表
        Route::rule('/group/get', 'admin/v1.Auth/getGroupById'); //获取一条组信息
        Route::rule('/group/add', 'admin/v1.Auth/saveGroup'); //新增
        Route::rule('/group/del', 'admin/v1.Auth/delGroupById'); //
        Route::rule('/rule/add', 'admin/v1.Auth/saveRule'); //
        Route::rule('/rule/del', 'admin/v1.Auth/delRuleById'); //
        Route::rule('/rule/get', 'admin/v1.Auth/getRuleById'); //
        Route::rule('/rule/lists', 'admin/v1.Auth/getRuleList'); //
        Route::rule('/group/ruleLists', 'admin/v1.Auth/getGroupRuleList'); //
        Route::rule('/rule/accessLists', 'admin/v1.Auth/getUserAccessLists'); //

        //用户管理
        Route::rule('/user/lists', 'admin/v1.User/lists'); //获取系统用户列表
        Route::get([ //对用户锁定 ，解锁
            '/user/lock' => 'admin/v1.User/lockUser',
            '/user/open' => 'admin/v1.User/openUser',
        ]);
        Route::rule('/user/add', 'admin/v1.User/createUser');
        Route::rule('/user/locks', 'admin/v1.User/lockMany'); //批量锁定
        Route::rule('/user/opens', 'admin/v1.User/openMany'); //批量解锁
        Route::rule('/user/update', 'admin/v1.User/updateUser');
        Route::rule('/user/follow', 'admin/v1.User/followlist');//关系
        Route::rule('/user/update', 'admin/v1.User/updateUser');

        //国家管理
        Route::rule('/country/lists', 'admin/v1.Country/lists'); //获取系统国家列表
        Route::rule('/country/save', 'admin/v1.Country/saveCountry');
        Route::rule('/country/get', 'admin/v1.Country/get');
        Route::rule('/country/del', 'admin/v1.Country/deleteCountry');

    });
}, ['before_behavior' => function () {
    if (Request::instance()->isOptions()) {
        // exit;
    }
}]);
