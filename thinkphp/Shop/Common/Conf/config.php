<?php
return array(
	//'配置项'=>'配置值'
    'SHOW_PAGE_TRACE'       =>  TRUE, //显示页面加载信息
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'sw_shop',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'sw_',    // 数据库表前缀
    'DB_PARAMS'             =>  array(), // 数据库连接参数    
    'DB_DEBUG'              =>  FALSE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    //'TMPL_ENGINE_TYPE'      =>  'Smarty',     // 默认模板引擎 以下设置仅对使用Think模板引擎有效
    
    'LANG_SWITCH_ON'       =>     true,    //开启语言包功能        
    'LANG_AUTO_DETECT'     =>     true, // 自动侦测语言
    'DEFAULT_LANG'         =>     'zh-cn', // 默认语言        
    'LANG_LIST'            =>     'en-us,zh-cn,zh-tw,pt-br', //必须写可允许的语言列表
    'VAR_LANGUAGE'         =>     'hl', // 默认语言切换变量
);