<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\User;
use app\common\model\LoginLog;
use \think\Url;
/**
 *@author xiayujie <1438641583@qq.com>
 *@date(2019/04/13)
 */
class Index extends Base
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
    public function index()
    {
        $menu = config('menu');
        $menu['user'] = 1;
        $breadcrumb = [
           ['menu' => '个人中心', 'href' => ''],
        ];
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        return $this->fetch();
    }
    //修改密码
    public function changePwd($uid = null, User $user)
    {
    	if ($this->request->method() == 'GET') {
    		if ($uid != $this->getLoginUserId()) {
    			$this->error('登录信息出错!');
    		}
            $menu = config('menu');
            $menu['user'] = 1;
            $breadcrumb = [
               ['menu' => '个人中心', 'href' => Url::build('person/index','', false, true)],
               ['menu' => '修改密码', 'href' => ''],
            ];
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            return $this->fetch();
    	} else {
            //修改密码
            $params = $this->request->param();
            $uid = $params['uid'];
            $oldPwd = $params['oldpwd'];
            $newPwd = $params['newpwd'];
            $checkPwd = $params['checkpwd'];
            if ($checkPwd != $newPwd) {
            	$this->error('两次密码输入不一致');
            }
            $info = $user->get($uid);
            if (empty($info)) {
            	$this->error('该用户不存在');
            }
            if (md5($oldPwd.$info->salt) != $info->password) {
                $this->error('原始密码输入错误');
            }
            $salt = getSalt();
            $info->password = md5($newPwd.$salt);
            $info->salt = $salt;
            if ($info->save()) {
            	$this->success('更新密码成功', 'person/index');
            }
            $this->error('更新密码失败');
    	}
    }
    //编辑个人信息
    public function editInfo($uid, User $user)
    {
        if ($this->request->method() == 'GET') {
            $info = $user->get($uid);
            if (empty($info)) {
                $this->error('用户信息错误');
            }
            $menu = config('menu');
            $menu['user'] = 1;
            $breadcrumb = [
               ['menu' => '个人中心', 'href' => Url::build('person/index','', false, true)],
               ['menu' => '编辑信息', 'href' => ''],
            ];
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            return $this->fetch();
        } else {
            $uid = $this->request->param('uid');
            if ($uid != $this->getLoginUserId()) {
                $this->error('用户信息错误');
            }
            $nickName = $this->request->param('nick_name');
            if (empty($nickName)) {
                $this->error('昵称不能为空');
            }
            $handle = $user->get($uid);
            if (empty($handle)) {
                $this->error('用户不存在');
            }
            //var_dump($this->request->param());
            $file = $this->request->file('avatar');
            //var_dump($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/avatar');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    $getSaveName = str_replace("\\","/",$info->getSaveName());
                    $handle->avatar = 'avatar/'.$getSaveName;
                }else{
                    // 上传失败获取错误信息
                    $this->error('头像上传失败:'.$file->getError());
                }
            }
            $handle->nickname = $nickName;
            if ($handle->save()) {
                $this->success('编辑成功');
            } else {
                $this->error('编辑失败');
            }
        }
    }
    public function logout()
	{
        $this->clearSession();
        $this->success('退出成功', 'user/login');
	}
}
