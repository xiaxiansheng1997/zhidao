<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\User as Ur;
use app\common\model\Role;
use \think\Url;
/**
 * @author xiayujie <1438641583@qq.com>
 * @date(2019/04/14 17:26)
 */
class User extends Base
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
	public function index(Ur $user, Role $role)
	{
        $input = $this->request->param();
        $where = [];
        if (isset($input['uid']) && !empty($input['uid'])) {
            $where['uid'] = $input['uid'];
        }
        if (isset($input['nickname']) && !empty($input['nickname'])) {
            $where['nickname'] = ['like', '%'.$input['nickname'].'%'];
        }
        if (isset($input['user_type']) && $input['user_type'] != -1) {
            $where['user_type'] = $input['user_type']; 
        }
        if ($this->isCommonUser == 1) {
            $where['uid'] = $this->getLoginUserId();
            $users = $user->where($where)->paginate(15, false, ['query' => $input]);
        } else {
            $users = $user->where($where)->paginate(15, false, ['query' => $input]);
        }
        $roles = $role->column('role_name','rid');
        foreach ($users as $key => $value) {
        	$users[$key]->role_type = $roles[$value->role_type];
        }
        $breadcrumb = [
           ['menu' => '用户管理', 'href' => ''],
        ];
        $menu = config('menu');
        $menu['users'] = 1;
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $users);
        $this->assign('uid', isset($input['uid'])?$input['uid']:'');
        $this->assign('nickname', isset($input['nickname'])?$input['nickname']:'');
        $this->assign('user_type', isset($input['user_type'])?$input['user_type']:'');
        return $this->fetch();
	}
	public function add(Ur $user, Role $role)
	{
		if ($this->request->method() == 'GET') {
			$roles = $role->column('role_name','rid');
			$breadcrumb = [
	           ['menu' => '用户管理', 'href' => Url::build('users/index','', false, true)],
	           ['menu' => '添加', 'href' => ''],
	        ];
	        $menu = config('menu');
	        $menu['users'] = 1;
	        $this->assign('menu', $menu);
	        $this->assign('breadcrumb', $breadcrumb);
	        $this->assign('roles', $roles);
			return $this->fetch();
		} else {
			$data = $this->request->param();
			$result = $this->validate($data,'User.add');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }
            if ($data['password'] != $data['checkpwd']) {
            	$this->error('两次输入密码不一致');
            }
            $userInfo = $this->getLoginInfo();
            if ($data['role_type'] == 1 && $userInfo['role_type'] != 1) {
            	$this->error('你没有添加超级管理员的权限');
            }
            $row = $user->where('nickname', $data['nickname'])->find();
            if (!empty($row)) {
            	$this->errro('昵称已存在');
            }
            $row2 = $user->where('email', $data['email'])->find();
            if (!empty($row2)) {
            	$this->errro('邮箱已存在');
            }
            if ($data['user_type'] == 1) {
            	$data['role_type'] = 7;
            }
			$user->nickname = $data['nickname'];
			$user->email = $data['email'];
			$salt = getSalt();
			$user->password = md5($data['password'].$salt);
			$user->salt = $salt;
			$user->status = $data['status'];
			$user->user_type = $data['user_type'];
			$user->role_type = $data['role_type'];
			$user->is_active = $data['is_active'];
			$user->is_freeze = $data['is_freeze'];
            $user->save();
            if ($user->uid) {
            	$this->success('新增用户成功', 'users/index');
            }
            $this->error('新增用户失败');
		}
	}
	public function edit($uid, Ur $user, Role $role)
	{
		if ($this->request->method() == 'GET') {
			$info = $user->get($uid);
			if (empty($info)) {
				$this->error('用户信息不存在');
			}
			$roles = $role->column('role_name','rid');
			$breadcrumb = [
	           ['menu' => '用户管理', 'href' => Url::build('users/index','', false, true)],
	           ['menu' => '编辑', 'href' => ''],
	        ];
	        $menu = config('menu');
	        $menu['users'] = 1;
	        $this->assign('menu', $menu);
	        $this->assign('breadcrumb', $breadcrumb);
	        $this->assign('roles', $roles);
	        $this->assign('info', $info);
			return $this->fetch();
		} else {
			$data = $this->request->param();
			$info = $user->get($data['uid']);
			if (empty($info)) {
				$this->error('用户信息不存在');
			}
			$result = $this->validate($data,'User.edit');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }
            $userInfo = $this->getLoginInfo();
            if ($data['role_type'] == 1 && $userInfo['role_type'] != 1) {
            	$this->error('你没有添加超级管理员的权限');
            }
            $row = $user->where('nickname', $data['nickname'])->find();
            if ($row['uid'] != $data['uid']) {
            	$this->errro('昵称已存在');
            }
            $row2 = $user->where('email', $data['email'])->find();
            if ($row2['uid'] != $data['uid']) {
            	$this->errro('邮箱已存在');
            }
            if ($data['user_type'] == 1) {
            	$data['role_type'] = 7;
            }
			$info->nickname = $data['nickname'];
			$info->email = $data['email'];
			$info->status = $data['status'];
			$info->user_type = $data['user_type'];
			$info->role_type = $data['role_type'];
			$info->is_active = $data['is_active'];
			$info->is_freeze = $data['is_freeze'];
            if ($info->save()) {
            	$this->success('编辑成功', 'users/index');
            }
            $this->error('编辑失败');
		}
	}
	public function delete($uid, Ur $user) 
	{
		$info = $user->get($uid);
        if ($info->delete()) {
        	$this->success('删除成功');
        }
        $this->error('删除失败');
	}
	public function enable($uid, Ur $user) {
        $info = $user->get($uid);
        if (empty($info)) {
        	$this->error('用户不存在');
        }
        $info->status = 1;
        if ($info->save()) {
        	$this->success('启用成功');
        }
        $this->error('启用失败');
	}
	public function disable($uid, Ur $user)
	{
        $info = $user->get($uid);
        if (empty($info)) {
        	$this->error('用户不存在');
        }
        $info->status = 2;
        if ($info->save()) {
        	$this->success('禁用成功');
        }
        $this->error('禁用失败');
	}
}