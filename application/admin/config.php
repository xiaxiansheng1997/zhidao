<?php
return [
    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__PUBLIC__'=>'/zhidao/public',
        '__JS__' => 'admin/js',
        '__IMG__' => 'admin/images',
        '__CSS__' => 'admin/css',
        '__SCSS_' => 'admin/scss',
        '__PLUGIN__' => 'admin/plugins',
    ],
    'captcha'  => [
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)，根据所需进行设置验证码字体大小
        'fontSize' => 30,
        // 是否画混淆曲线
        'useCurve' => true,
        // 验证码图片高度，根据所需进行设置高度
        'imageH'   => '',
        // 验证码图片宽度，根据所需进行设置宽度
        'imageW'   => '',
        // 验证码位数，根据所需设置验证码位数
        'length'   => 4,
        // 验证成功后是否重置
        'reset'    => true
    ],
    //网站配置
    'base_config' => 'BASE_CONFIG',
    //菜单配置(控制菜单激活状态--默认不激活)
    'menu' => [
        'system' => 0,
        'basic' => 0,
        'web' => 0,
        'link' => 0,
        'img' => 0,
        'user' => 0,
        'users' => 0,
        'log' => 0,
        'login' => 0,
        'action' => 0,
        'role' => 0,
        'good' => 0,
        'job' => 0,
        'news' => 0,
    ],
    //是否显示菜单
    'display_menu' => [
        'person/index' => 1,
        'log/default' => 1,
        'log/loginlog' => 1,
        'log/actionlog' => 1,
        'users/index' => 1,
        'config/default' => 1,
        'config/index' => 1,
        'config/edit' => 1,
        'config/friendlink' => 1,
        'config/carousel_img' => 1,
        'role/index' => 1,
        'goods/index' => 1,
        'job/index' => 1,
        'news/index' => 1,
    ],
    //权限控制
    'auth_menu' => include("menu.php"),
];