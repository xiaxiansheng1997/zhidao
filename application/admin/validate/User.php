<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'nickname|昵称'  =>  'require|max:50',
        'email|邮箱' =>  'require|email',
        'password|密码' => 'require|length:6,30',
        'checkpwd|确认密码' =>  'require|length:6,30',
        'status|用户状态' =>  'require',
        'user_type|用户类型' =>  'require',
        'role_type|用户角色' =>  'require',
        'is_freeze|冻结状态' =>  'require',
        'is_active|激活状态' =>  'require',
    ];
    protected $scene = [
        'add'   =>  ['nickname','email','password','checkpwd','status','user_type','role_type','is_freeze','is_active'],
        'edit'  =>  ['nickname','email','status','user_type','role_type','is_freeze','is_active'],
    ];
}