<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\LoginLog;
use app\admin\model\ActionLog;
/**
 * @author xiayujie <1438641583@qq.com>
 */
class Log extends Base
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->checkIsLogin()) {
            $this->error('您还未登录!!!', 'user/login');
        }
        if (!$this->checkIsAuth()) {
            $this->error('你没有权限', 'person/index');
        }
    }
    public function loginLog(LoginLog $loginLog)
    {
        $input = $this->request->param();
        $where = [];
        if (isset($input['uid']) && !empty($input['uid'])) {
            $where['uid'] = $input['uid'];
        }
        if (isset($input['status']) && $input['status'] != -1) {
            $where['status'] = $input['status']; 
        }
        if ($this->isCommonUser == 1) {
            $where['uid'] = $this->getLoginUserId();
            $info = $loginLog->where($where)->order('create_time', 'desc')->paginate(15, false, ['query' => $input]);
        } else {
            $info = $loginLog->where($where)->order('create_time', 'desc')->paginate(15, false, ['query' => $input]);
        }
        $menu = config('menu');
        $menu['log'] = 1;
        $menu['login'] = 1;
        $breadcrumb = [
           ['menu' => '登录日志', 'href' => ''],
        ];
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $info);
        $this->assign('uid', isset($input['uid'])?$input['uid']:'');
        $this->assign('status', isset($input['status'])?$input['status']:'');
        return $this->fetch('');
    }
    public function delete($lid, LoginLog $loginLog)
    {
        $log = $loginLog->get($lid);
        if ($log->delete()) {
        	$this->success('删除成功');
        }
        $this->error('删除失败');
    }


    public function actionLog(ActionLog $actionLog)
    {
        $input = $this->request->param();
        $where = [];
        if (isset($input['uid']) && !empty($input['uid'])) {
            $where['uid'] = $input['uid'];
        }
        if (isset($input['action']) && $input['action'] != -1) {
            $where['action'] = $input['action']; 
        }
        if ($this->isCommonUser == 1) {
            $where['uid'] = $this->getLoginUserId();
            $info = $actionLog->where($where)->order('create_time', 'desc')->paginate(15, false, ['query' => $input]);
        } else {
            $info = $actionLog->where($where)->order('create_time', 'desc')->paginate(15, false, ['query' => $input]);
        }
        $menu = config('menu');
        $menu['log'] = 1;
        $menu['action'] = 1;
        $breadcrumb = [
           ['menu' => '操作日志', 'href' => ''],
        ];
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $info);
        $this->assign('uid', isset($input['uid'])?$input['uid']:'');
        $this->assign('action', isset($input['action'])?$input['action']:'');
        return $this->fetch();
    }

    public function deletelog($log_id, ActionLog $actionLog)
    {
    	$log = $actionLog->get($log_id);
    	if ($log->delete()) {
    		$this->success('删除成功');
    	}
    	$this->error('删除失败');
    }
}