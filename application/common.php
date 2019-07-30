<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getSalt()
{
    $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    $salt = substr(str_shuffle($str),10,6);
    return $salt;
}
function getToken()
{
	$str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    $token = substr(str_shuffle($str),10,30);
    session([
	    'expire' => 3600,
	]);
    $suffix = substr($token, 20);
    session('token'.$suffix, $token);
    return $token;
}
function getSessionInfo($session)
{
    return session($session) ?? '';
}
function getTokenForPwd()
{
    $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    $token = substr(str_shuffle($str),10,30);
    $suffix = substr($token, 20);
    cache('token'.$suffix, $token, 3600);
    return $token;
}
function saveTokenAndEmailWithCache($token, $email)
{
    cache($token, $email, 3600);
}
function clearSession($key)
{
    if (is_array($key)) {
       foreach ($key as $k => $v) {
           session($v,NULL); 
       }
    } else {
       session($key,NULL); 
    }
}
function clearCache($key)
{
    if (is_array($key)) {
       foreach ($key as $k => $v) {
           cache($v,NULL); 
       }
    } else {
       cache($key,NULL); 
    }
}