<?php
namespace app\common\model;

use think\Model;

class UsedGood extends Model
{
	protected $pk = 'gid';
	protected $autoWriteTimestamp = 'datetime';
}