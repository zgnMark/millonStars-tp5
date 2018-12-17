<?php
return [

    'auth' => [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 2, // 认证方式，1为实时认证；2为登录认证。
        'public_auth'       => 'admin/v1.Auth/getUserAccessLists',
        'auth_group'        => 'auth_group', // 用户组数据表名
        'auth_group_access' => 'auth_group_access', // 用户-用户组关系表
        'auth_rule'         => 'auth_rule', // 权限规则表
        'auth_user'         => 'j_admin', // 用户信息表
    ],

];
