<?php
namespace app\common\model;

use think\Model;
use think\Db;
class User extends Model
{
	protected $pk = 'uid';
	protected $autoWriteTimestamp = 'datetime';
	protected static $table_name = 'zhidao_user';
	public function queryUserCountByType()
	{
       return Db::table(self::$table_name)->field('user_type,count(uid) as count')->group('user_type')->select();
	}
	public function queryTodayLoginCount()
	{
		return Db::table(self::$table_name)->field('count(*) as num')->where('last_login_time','between',[date('Y-m-d 00:00:00', time()),date('Y-m-d 23:59:59', time())])->find();
	}
}