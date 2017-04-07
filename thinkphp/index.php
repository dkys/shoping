<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

//定义项目根目录URL
define('ROOT_URL', 'www.shop.com');

//前台：定义css所在目录
define('CSS_PATH', '/thinkphp/Shop/public/Home/css/');
//定义img图片所在目录
define('IMG_PATH', '/thinkphp/Shop/public/Home/img/');
//定义js所在目录
define('JS_PATH', '/thinkphp/Shop/public/Home/js/');

//后台路径
//后台：定义css所在目录
define('ADMIN_CSS_PATH', '/thinkphp/Shop/public/Admin/css/');
//定义img图片所在目录
define('ADMIN_IMG_PATH', '/thinkphp/Shop/public/Admin/img/');
//定义js所在目录
define('ADMIN_JS_PATH', '/thinkphp/Shop/public/Admin/js/');

//定义上传商品图片文件目录
define('UPLOADS_IMG_PATH', '/thinkphp/Shop/public/');


// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
//当调试模式开启时 模型类会自动查询可使用的字段 public 'queryString' => string 'SHOW COLUMNS FROM `tencent_qq`' (length=30)
//当调试模式关闭时 则不会执行查询 这样可以对系统做一个优化
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Shop/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单