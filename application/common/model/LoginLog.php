<?php
namespace app\common\model;

use think\Model;
use think\Request;
use think\Db;
class LoginLog extends Model
{
	protected $pk = 'lid';
	protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = '';
    protected static $table_name = 'zhidao_login_log';
	public static function saveLoginLog($uid,$status)
	{
		$request = Request::instance();
        $loginLog = new LoginLog;
        $loginLog->uid = $uid;
        $loginLog->status = $status;
        $loginLog->login_ip = $request->ip();
        $loginLog->save();
	}
	public static function queryLoginFailInfo($uid)
	{
		return Db::table(self::$table_name)->field('count(lid) as num')->where('uid', $uid)->where('status', 0)->where('create_time','between',[date('Y-m-d H:i:s', time()-600),date('Y-m-d H:i:s', time())])->find();
	}
}