<?php
namespace app\admin\validate;

use think\Validate;

class Goods extends Validate
{
    protected $rule = [
        'name|物品名称'  =>  'require|token',
        'type|物品类型' =>  'require',
        'detail|物品介绍' => 'require',
        'price|物品价格' => 'require',
        'phone|联系人电话' => 'require',
        'is_sell|交易类型' => 'require',
        'status|物品状态' => 'require',
    ];
    
}