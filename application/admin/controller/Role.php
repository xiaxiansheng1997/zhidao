<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\Role as Rl;
use \think\Url;
/**
 * @author xiayujie <1438641583@qq.com>
 */
class Role extends Base
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
    public function index(Rl $role)
    {
        if ($this->isCommonUser == 1) {
            $info = $role->where('uid',$this->getLoginUserId())->paginate(5);
        } else {
            $info = $role->paginate(5);
        }
        $breadcrumb = [
           ['menu' => '角色管理', 'href' => ''],
        ];
        $menu = config('menu');
        $menu['role'] = 1;
    	$this->assign('info', $info);
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
    	return $this->fetch();
    }
    //添加角色
    public function add(Rl $role)
    {
        if ($this->request->method() == 'GET') {
            $breadcrumb = [
               ['menu' => '角色管理', 'href' => Url::build('role/index','', false, true)],
               ['menu' => '添加', 'href' => ''],
            ];
            $menu = config('menu');
            $menu['role'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            return $this->fetch();
        } else {
            //添加角色
            $roleName = $this->request->param('role_name');
            $row = $role->where('role_name',$roleName)->find();
            if (!empty($row)) {
                $this->error('角色名已存在');
            }
            if (empty($roleName)) {
                $this->error('角色名称不能为空');
            }
            $role->uid = $this->getLoginUserId();
            $role->role_name = $roleName;
            $role->save();
            if ($role->rid) {
                $this->success('添加成功','role/index');
            }
            $this->error('添加失败');
        }
    }
    public function edit($rid, Rl $role)
    {
        if ($this->request->method() == 'GET') {
            $info = $role->get($rid);
            $breadcrumb = [
               ['menu' => '角色管理', 'href' => Url::build('role/index','', false, true)],
               ['menu' => '编辑', 'href' => ''],
            ];
            $menu = config('menu');
            $menu['role'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('info', $info);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            $info = $role->get($data['rid']);
            if (empty($info)) {
                $this->error('角色信息不存在');
            }
            $where['rid'] = ['neq', $data['rid']]; 
            $where['role_name'] = ['eq', $data['role_name']];
            $row = $role->where($where)->find();
            if (!empty($row)) {
                $this->error('角色名已存在');
            }
            if (empty($data['role_name'])) {
                $this->error('角色名称不能为空');
            }
            if ($data['uid'] != $this->getLoginUserId()) {
                $this->error('系统异常,请稍后重试');
            }
            $info->role_name = $data['role_name'];
            if ($info->save()) {
                $this->success('编辑成功', 'role/index');
            }
            $this->error('编辑失败');
        }
    }
    public function delete($rid,Rl $role)
    {
        $info = $role->get($rid);
        if (empty($info)) {
            $this->error('角色不存在');
        }
        if ($info->delete()) {
        	$this->success('删除成功');
        }
        $this->error('删除失败');
    }
    //设置权限
    public function auth($rid, Rl $role)
    {
        if ($this->request->method() == 'GET') {
            $info = $role->get($rid);
            $menus = config('auth_menu');
            $breadcrumb = [
               ['menu' => '角色管理', 'href' => Url::build('role/index','', false, true)],
               ['menu' => '权限设置', 'href' => ''],
            ];
            $menu = config('menu');
            $menu['role'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('info', $info);
            $this->assign('menus', $menus);
            return $this->fetch();
        } else {
            //设置权限
            $data = $this->request->param();
            $info = $role->get($data['rid']);
            if (empty($info)) {
                $this->error('该角色不存在');
            }
            $info->role_permission = implode(',', $data['auth']);
            if ($info->save()) {
                $this->success('设置成功', 'role/index');
            }
            $this->error('设置失败');
        }
    }
}