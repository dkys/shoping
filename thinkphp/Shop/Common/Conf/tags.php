<?php

return array(
    'app_begin'     =>  array(
            'Behavior\ReadHtmlCacheBehavior', // 读取静态缓存
            'Behavior\CheckLangBehavior',             //手动启动语言包支持
        ),
);

