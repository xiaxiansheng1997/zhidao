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

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    //登录
    '[user]'     => [
        '/login'   => ['admin/Login/login', ['method' => 'get']],
        '/login_handle' => ['admin/Login/login', ['method' => 'post']],
        '/forgot' => ['admin/Login/forgot', ['method' => 'get']],
        '/forgot_handle' => ['admin/Login/forgot', ['method' => 'post']],
        '/logot/:uid' => ['admin/Login/logot', ['method' => 'get']],
        '/reset/token/:token' => ['admin/Login/reset', ['method' => 'get']],
        '/reset_handle' => ['admin/Login/reset', ['method' => 'post']],
        '/active/token/:token' => ['admin/Login/active', ['method' => 'get']],
    ],
    //个人中心
    '[person]'    => [
    	//显示个人中心
    	'/index' => ['admin/Index/index', ['method' => 'get']],
    	'/changepwd/:uid' => ['admin/Index/changePwd', ['method' => 'get']],
    	'/changepwd_handle' => ['admin/Index/changePwd', ['method' => 'post']],
    	'/editinfo/:uid'=> ['admin/Index/editInfo', ['method' => 'get']],
    	'/editinfo_handle' => ['admin/Index/editInfo', ['method' => 'post']],
    ],
    //日志管理
    '[log]'     => [
        '/loginlog' => ['admin/Log/loginLog', ['method' => 'get']],
        '/delete/:lid' => ['admin/Log/delete', ['method' => 'get']],
        '/actionlog' => ['admin/Log/actionLog', ['method' => 'get']],
        '/deletelog/:log_id' => ['admin/Log/deleteLog', ['method' => 'get']],
    ],
    //用户管理
    '[users]' => [
    	'/index' => ['admin/User/index', ['method' => 'get']],
    	'/add' => ['admin/User/add', ['method' => 'get']],
    	'/add_handle' => ['admin/User/add', ['method' => 'post']],
    	'/edit/:uid' => ['admin/User/edit', ['method' => 'get']],
    	'/edit_handle' => ['admin/User/edit', ['method' => 'post']],
    	'/delete/:uid' => ['admin/User/delete', ['method' => 'get']],
    	'/enable/:uid' => ['admin/User/enable', ['method' => 'get']],
    	'/disable/:uid' => ['admin/User/disable', ['method' => 'get']],
    ],
    //网站配置管理
    '[config]' => [
        //主参数管理(网站配置，登录情况)
        '/index' => ['admin/Config/index', ['method' => 'get']],
        //网站信息配置
        '/edit' => ['admin/Config/edit', ['method' => 'get']],
        '/edit_handle' => ['admin/Config/edit', ['method' => 'post']],
        '/friendlink' => ['admin/Config/friendlink', ['method' => 'get']],
        '/link_add' => ['admin/Config/link_add', ['method' => 'get']],
        '/link_add_handle' => ['admin/Config/link_add', ['method' => 'post']],
        '/link_edit/:lid' => ['admin/Config/link_edit', ['method' => 'get']],
        '/link_edit_handle' => ['admin/Config/link_edit', ['method' => 'post']],
        '/link_delete/:lid' => ['admin/Config/link_delete', ['method' => 'get']],
        '/carousel_img' => ['admin/Config/carousel_img', ['method' => 'get']],
        '/carousel_img_add' => ['admin/Config/carousel_img_add', ['method' => 'get']],
        '/carousel_img_add_handle' => ['admin/Config/carousel_img_add', ['method' => 'post']],
        '/carousel_img_edit/:id' => ['admin/Config/carousel_img_edit', ['method' => 'get']],
        '/carousel_img_edit_handle' => ['admin/Config/carousel_img_edit', ['method' => 'post']],
        '/carousel_img_delete/:id' => ['admin/Config/carousel_img_delete', ['method' => 'post']],
    ],
    
    //二手物品管理
    '[goods]' => [
        '/index' => ['admin/Goods/index', ['method' => 'get']],
        '/add' => ['admin/Goods/add', ['method' => 'get']],
        '/add_handle' => ['admin/Goods/add', ['method' => 'post']],
        '/edit/:gid' => ['admin/Goods/edit', ['method' => 'get']],
        '/edit_handle' => ['admin/Goods/edit', ['method' => 'post']],
        '/delete/:gid' => ['admin/Goods/delete', ['method' => 'get']],
        '/enable/:gid' => ['admin/Goods/enable', ['method' => 'get']],
        '/disable/:gid' => ['admin/Goods/disable',['method' => 'get']],
    ],

    //兼职招聘管理
    '[job]' => [
        '/index' => ['admin/Job/index', ['method' => 'get']],
        '/add' => ['admin/Job/add', ['method' => 'get']],
        '/add_handle' => ['admin/Job/add', ['method' => 'post']],
        '/edit/:rid' => ['admin/Job/edit', ['method' => 'get']],
        '/edit_handle' => ['admin/Job/edit', ['method' => 'post']],
        '/delete/:rid' => ['admin/Job/delete', ['method' => 'get']],
        '/enable/:rid' => ['admin/Job/enable', ['method' => 'get']],
        '/disable/:rid' => ['admin/Job/disable',['method' => 'get']],
    ],
    //资讯管理
    '[news]' => [
        '/index' => ['admin/News/index', ['method' => 'get']],
        '/add' => ['admin/News/add', ['method' => 'get']],
        '/add_handle' => ['admin/News/add', ['method' => 'post']],
        '/edit/:nid' => ['admin/News/edit', ['method' => 'get']],
        '/edit_handle' => ['admin/News/edit', ['method' => 'post']],
        '/delete/:nid' => ['admin/News/delete', ['method' => 'get']],
    ],
    //角色管理
    '[role]' => [
        '/index' => ['admin/Role/index', ['method' => 'get']],
        '/add' => ['admin/Role/add', ['method' => 'get']],
        '/add_handle' => ['admin/Role/add', ['method' => 'post']],
        '/edit/:rid' => ['admin/Role/edit', ['method' => 'get']],
        '/edit_handle' => ['admin/Role/edit', ['method' => 'post']],
        '/delete/:rid' => ['admin/Role/delete', ['method' => 'get']],
        '/auth/:rid' => ['admin/Role/auth', ['method' => 'get']],
        '/auth_handle' => ['admin/Role/auth', ['method' => 'post']],
    ],
];
