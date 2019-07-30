<?php
namespace app\admin\validate;

use think\Validate;

class Config extends Validate
{
    protected $rule = [
        'title|网站标题'  =>  'require|max:30',
        'keyword|网站关键字' =>  'require',
        'description|描述' => 'require|max:156',
    ];
    
}