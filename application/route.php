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
    '[home]'     => [
        '/index'   => ['index/Index/index', ['method' => 'get']],
        '/about'   => ['index/Index/about', ['method' => 'get']],
        '/click_up/:id' => ['index/Index/click_up', ['method' => 'get']],
        '/click_down/:id' => ['index/Index/click_down', ['method' => 'get']],
    ],
    '[list_info]' => [
        '/news/:type' => ['index/Entry/news', ['method' => 'get']],
        '/jobs' => ['index/Entry/jobs', ['method' => 'get']],
        '/goods/:type' => ['index/Entry/goods', ['method' => 'get']],
    ], 
    '[detail]' => [
        '/news/:id' => ['index/Show/news', ['method' => 'get']],
        '/jobs/:id' => ['index/Show/jobs', ['method' => 'get']],
        '/goods/:id' => ['index/Show/goods', ['method' => 'get']],
    ],
    '[web_login]' => [
        '/login' => ['index/Login/login', ['method' => 'get']],
        '/login_handle' => ['index/Login/login', ['method' => 'post']],
        '/register' => ['index/Login/register', ['method' => 'get']],
        '/register_handle' => ['index/Login/register', ['method' => 'post']],
        '/logot/:uid' => ['index/Login/logot', ['method' => 'get']],
        '/active/token/:token' => ['index/Login/active', ['method' => 'get']],
        '/forgot' => ['index/Login/forgot', ['method' => 'get']],
        '/forgot_handle' => ['index/Login/forgot', ['method' => 'post']],
        '/reset/token/:token' => ['index/Login/reset', ['method' => 'get']],
        '/reset_handle' => ['index/Login/reset', ['method' => 'post']],
    ],
];
