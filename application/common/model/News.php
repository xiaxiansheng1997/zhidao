<?php
namespace app\common\model;
use think\Model;
class News extends Model
{
	protected $pk = 'nid';
	protected $autoWriteTimestamp = 'datetime';

}