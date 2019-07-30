<?php
namespace app\admin\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title|文章标题'  =>  'require|max:50',
        'type|文章分类' =>  'require',
        'content|文章内容' => 'require',
    ];
    
}