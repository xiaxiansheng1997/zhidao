<?php
namespace app\admin\validate;

use think\Validate;

class FriendLink extends Validate
{
    protected $rule = [
        'title|链接内容'  =>  'require|max:30|token',
        'link|链接地址' =>  'require|url',
        'sort|排序字段' => 'require|number|between:1,12',
    ];
    
}