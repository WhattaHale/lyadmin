<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;


// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

//定义文件引入CSS，JS等文件使用的常量SITE_URl
define('SITE_URl','http://127.0.0.1/layuiadmin');
 //执行应用并响应
//Container::get('app')->run()->send();

////加上入口文件
use think\facade\Url;
Url::root('/index.php');
//// 支持事先使用静态方法设置Request对象和Config对象
//// 执行应用并响应
Container::get('app',[__DIR__ . '/application/'])->run()->send();
// 读取自动生成定义文件
//$build = include './build.php';
//
//// 运行自动生成
//\think\facade\Build::run($build);


//// 应用入口文件
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//// 检测PHP环境
//if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//
//// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
//define('APP_DEBUG',True);
//
////define('BIND_MODULE','Home');//绑定Home模块到当前入口文件，可用于新增Home模块
//
//// 定义应用目录
//define('APP_PATH','./App/');
//define('RUNTIME_PATH','./Runtime/');
//// 引入ThinkPHP入口文件
//require './ThinkPHP/ThinkPHP.php';
//
//// 亲^_^ 后面不需要任何代码了 就是如此简单
