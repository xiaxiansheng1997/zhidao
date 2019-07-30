<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\News as Ns;
use \think\Url;
/**
 * @author xiayujie <1438641583@qq.com>
 * @date(2019/05/01 16:49)
 */
class News extends Base
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
    //高校资讯列表
	public function index(Ns $news)
	{
        $input = $this->request->param();
        $where = [];
        if (isset($input['title']) && !empty($input['title'])) {
            $where['title'] = ['like', '%'.$input['title'].'%'];
        }
        if (isset($input['type']) && $input['type'] != -1) {
            $where['type'] = $input['type']; 
        }
        if ($this->isCommonUser == 1) {
            $where['uid'] = $this->getLoginUserId();
            $info = $news->where($where)->order('create_time','desc')->paginate(5);
        } else {
            $info = $news->where($where)->order('create_time','desc')->paginate(5);
        }
        foreach ($info as $key => $value) {
            $info[$key]->type = config('news_type')[$value->type];
        }
        $breadcrumb = [
           ['menu' => '资讯管理', 'href' => ''],
        ];
        $menu = config('menu');
        $menu['news'] = 1;
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $info);
        $this->assign('title', isset($input['title'])?$input['title']:'');
        $this->assign('type', isset($input['type'])?$input['type']:'');
        $this->assign('types', config('news_type'));
        return $this->fetch();
	}
    //添加
	public function add(Ns $news)
	{
		if ($this->request->method() == 'GET') {
			$breadcrumb = [
               ['menu' => '资讯管理', 'href' => Url::build('news/index','', false, true)],
               ['menu' => '添加', 'href' => ''],
            ];
            $menu = config('menu');
            $menu['news'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('type', config('news_type'));
            return $this->fetch();
		} else {
            //添加
            $data = $this->request->param();
            $result = $this->validate($data,'News');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }
            $news->title = $data['title'];
            $news->type = $data['type'];
            $news->content = $data['content'];
            $news->uid = $this->getLoginUserId();
            $news->save();
            if ($news->nid) {
            	$this->success('添加成功', 'news/index');
            }
            $this->error('添加失败');
		}
	}
	public function edit($nid, Ns $news)
	{
        if ($this->request->method() == 'GET') {
            $info = $news->get($nid);
            if (empty($info)) {
                $this->error('该文章不存在');
            }
            $breadcrumb = [
               ['menu' => '资讯管理', 'href' => Url::build('news/index','', false, true)],
               ['menu' => '编辑', 'href' => ''],
            ];
            $menu = config('menu');
            $menu['news'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('type', config('news_type'));
            $this->assign('info', $info);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            $info = $news->get($data['nid']);
            if (empty($info)) {
            	$this->error('该篇文章不存在');
            }
            $result = $this->validate($data,'News');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }
            $info->title = $data['title'];
            $info->type = $data['type'];
            $info->content = $data['content'];
            if ($info->save()) {
            	$this->success('编辑成功', 'news/index');
            }
            $this->error('编辑失败');
        }
	}
	public function delete($nid, Ns $news)
	{
        $info = $news->get($nid);
        if (empty($info)) {
        	$this->error('文章不存在');
        }
        if ($info->delete()) {
        	$this->success('删除成功');
        }
        $this->error('删除失败');
	}
}
