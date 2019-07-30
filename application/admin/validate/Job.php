<?php
namespace app\admin\validate;

use think\Validate;

class Job extends Validate
{
    protected $rule = [
        'work|招聘职位'  =>  'require|token',
        'detail|详情' =>  'require',
        'count|人数' => 'require',
        'days|天数' => 'require',
        'work_time|工作时间' => 'require',
        'work_place|工作地点' => 'require',
        'salary|薪资' => 'require',
        'phone|联系人电话' => 'require',
        'status|职位状态' => 'require',
    ];
    
}