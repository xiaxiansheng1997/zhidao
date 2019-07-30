<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\RecruitInfo;
use \think\Url;
/**
 * @author xiayujie <1438641583@qq.com>
 */
class Job extends Base
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
    //兼职招聘信息列表
	public function index(RecruitInfo $recruitInfo)
	{
        $input = $this->request->param();
        $where = [];
        if (isset($input['work']) && !empty($input['work'])) {
            $where['work'] = ['like', '%'.$input['work'].'%'];
        }
        if (isset($input['status']) && $input['status'] != -1) {
            $where['status'] = $input['status']; 
        }
        if ($this->isCommonUser == 1) {
            $where['uid'] = $this->getLoginUserId();
            $info = $recruitInfo->where($where)->order('create_time','desc')->paginate(10, false, ['query' => $input]);
        } else {
            $info = $recruitInfo->where($where)->order('create_time','desc')->paginate(10, false, ['query' => $input]);
        }
        $breadcrumb = [
           ['menu' => '兼职招聘管理', 'href' => ''],
        ];
        $menu = config('menu');
        $menu['job'] = 1;
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $info);
        $this->assign('work', isset($input['work'])?$input['work']:'');
        $this->assign('status', isset($input['status'])?$input['status']:'');
        return $this->fetch();
	}
	public function add(RecruitInfo $recruitInfo)
	{
        if ($this->request->method() == 'GET') {
            $breadcrumb = [
               ['menu' => '兼职招聘管理', 'href' => Url::build('job/index','', false, true)],
               ['menu' => '添加', 'href' => '']
            ];
            $menu = config('menu');
            $menu['job'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            return $this->fetch();
        } else {
            //添加
            $data = $this->request->param();
            $result = $this->validate($data, 'Job');
            if (true !== $result) {
                $this->error($result);
            }
            $recruitInfo->uid = $this->getLoginUserId();
            $recruitInfo->work = $data['work'];
            $recruitInfo->detail = $data['detail'];
            $recruitInfo->count = $data['count'];
            $recruitInfo->days = $data['days'];
            $recruitInfo->work_time = $data['work_time'];
            $recruitInfo->work_place = $data['work_place'];
            $recruitInfo->salary = $data['salary'];
            $recruitInfo->phone = $data['phone'];
            $recruitInfo->status = $data['status'];
            $recruitInfo->save();
            if ($recruitInfo->rid) {
            	$this->success('添加成功', 'job/index');
            } else {
            	$this->error('添加失败');
            }
        }
	}
	public function edit($rid, RecruitInfo $recruitInfo)
	{
		$info = $recruitInfo->get($rid);
        if ($this->request->method() == 'GET') {
            if (empty($info)) {
                $this->error('该记录不存在');
            }
            $breadcrumb = [
               ['menu' => '兼职招聘管理', 'href' => Url::build('job/index','', false, true)],
               ['menu' => '编辑', 'href' => '']
            ];
            $menu = config('menu');
            $menu['job'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
        	$this->assign('info', $info);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            $result = $this->validate($data, 'Job');
            if (true !== $result) {
                $this->error($result);
            }
            $info->work = $data['work'];
            $info->detail = $data['detail'];
            $info->count = $data['count'];
            $info->days = $data['days'];
            $info->work_time = $data['work_time'];
            $info->work_place = $data['work_place'];
            $info->salary = $data['salary'];
            $info->phone = $data['phone'];
            $info->status = $data['status'];
            if ($info->save()) {
            	$this->success('编辑成功', 'job/index');
            } else {
            	$this->error('编辑失败');
            }
        }
	}
	public function delete($rid, RecruitInfo $recruitInfo)
	{
        $job = $recruitInfo->get($rid);
        if ($job->delete()) {
        	$this->success('删除成功');
        }
        $this->error('删除失败');
	}
	public function enable($rid, RecruitInfo $recruitInfo)
	{
        $info = $recruitInfo->get($rid);
        if (empty($info)) {
        	$this->error('记录不存在');
        }
        $info->status = 1;
        if ($info->save()) {
        	$this->success('开启成功');
        }
        $this->error('开启失败');
	}
    public function disable($rid, RecruitInfo $recruitInfo)
	{
        $info = $recruitInfo->get($rid);
        if (empty($info)) {
        	$this->error('记录不存在');
        }
        $info->status = 2;
        if ($info->save()) {
        	$this->success('关闭成功');
        }
        $this->error('关闭失败');
	}
}