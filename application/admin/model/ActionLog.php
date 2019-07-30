<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class ActionLog extends Model
{
	protected $pk = 'log_id';
	protected $autoWriteTimestamp = 'datetime';
	protected static $table_name = 'zhidao_action_log';
    protected $updateTime = '';
	public static function insertActionLog($uid,$action,$detail,$data = '')
	{
		$array = ['action' => $action, 'detail' => $detail, 'data' => $data, 'uid' => $uid, 'create_time' => date('Y-m-d H:i:s', time())];
        Db::table(self::$table_name)->insert($array);
	}
}