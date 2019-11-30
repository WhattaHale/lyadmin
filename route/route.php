<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    dump('xxx');exit;
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

Route::domain('admin','Admin');
Route::domain('api','Api');
//Route::domain('admin', function () {
//    // 动态注册域名的路由规则
//    Route::get('home', 'index/hello');
//    Route::rule('new', 'admin/index');
//    Route::rule(':user', 'index/user/info');
//});

return [
// 开启子域名配置
//    'APP_SUB_DOMAIN_DEPLOY' => 1,
//    'APP_SUB_DOMAIN_RULES'  => [
//        'wwwbeta'  => 'Home',
//        'admin' => 'Admin',
//        'userbeta'  => 'User',
//        'phonebeta'  => 'Phone',
//        'spreadbeta'  => 'Spread',
//        'apibeta'  => 'Api',
//        'yzhbeta'  => 'NewUser',
//        'yzhbuybeta'  => 'NewPhone',
//        'buybeta'  => 'NewPhone',
//    ],
];
