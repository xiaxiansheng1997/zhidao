<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\UsedGood;
use app\admin\model\ActionLog;
use \think\Url;
/**
 * @author xiayujie <1438641583@qq.com>
 */
class Goods extends Base
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
    //二手物品列表
	public function index(UsedGood $usedgood)
	{
        $input = $this->request->param();
        $where = [];
        if (isset($input['name']) && !empty($input['name'])) {
            $where['name'] = ['like', '%'.$input['name'].'%'];
        }
        if (isset($input['type']) && $input['type'] != -1) {
            $where['type'] = $input['type']; 
        }
        if (isset($input['is_sell']) && $input['is_sell'] != -1) {
            $where['is_sell'] = $input['is_sell']; 
        }
        if ($this->isCommonUser == 1) {
            $where['uid'] = $this->getLoginUserId();
            $info = $usedgood->where($where)->order('create_time','desc')->paginate(10, false, ['query' => $input]);
        } else {
            $info = $usedgood->where($where)->order('create_time','desc')->paginate(10, false, ['query' => $input]);
        }
        foreach ($info as $key => $value) {
            $info[$key]->type = config('goods_type')[$value->type];
        }
        $breadcrumb = [
           ['menu' => '二手物品管理', 'href' => ''],
        ];
        $menu = config('menu');
        $menu['good'] = 1;
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $info);
        $this->assign('name', isset($input['name'])?$input['name']:'');
        $this->assign('type', isset($input['type'])?$input['type']:'');
        $this->assign('types', config('goods_type'));
        $this->assign('is_sell', isset($input['is_sell'])?$input['is_sell']:'');
        return $this->fetch();
	}
	public function add(UsedGood $usedgood)
	{
        if ($this->request->method() == 'GET') {
            $breadcrumb = [
               ['menu' => '二手物品管理', 'href' => Url::build('goods/index','', false, true)],
               ['menu' => '添加', 'href' => '']
            ];
            $menu = config('menu');
            $menu['good'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('type', config('goods_type'));
            return $this->fetch();
        } else {
            $data = $this->request->param();
            $result = $this->validate($data, 'Goods');
            if (true !== $result) {
                $this->error($result);
            }
            //物品图片上传
            $file = $this->request->file('photo');
            //var_dump($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/goods');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    $getSaveName = str_replace("\\","/",$info->getSaveName());
                    $usedgood->photo = 'goods/'.$getSaveName;
                }else{
                    // 上传失败获取错误信息
                    $this->error('物品图片上传失败:'.$file->getError());
                }
            } else {
                $this->error('物品图片必须上传');
            }
            $usedgood->uid = $this->getLoginUserId();
            $usedgood->name = $data['name'];
            $usedgood->type = $data['type'];
            $usedgood->detail = $data['detail'];
            $usedgood->price = $data['price'];
            $usedgood->phone = $data['phone'];
            $usedgood->is_sell = $data['is_sell'];
            $usedgood->status = $data['status'];
            $usedgood->save();
            if ($usedgood->gid) {
                ActionLog::insertActionLog($this->getLoginUserId(),config('action.insert'),'新增二手商品',implode('|', $data));
            	$this->success('添加成功', 'goods/index');
            } else {
            	$this->error('添加失败');
            }
        }
	}
	public function edit($gid, UsedGood $usedgood)
	{
        $goods = $usedgood->get($gid);
		if ($this->request->method() == 'GET') {
            if (empty($goods)) {
                $this->error('该物品记录不存在');
            }
            $breadcrumb = [
               ['menu' => '二手物品管理', 'href' => Url::build('goods/index','', false, true)],
               ['menu' => '编辑', 'href' => '']
            ];
            $menu = config('menu');
            $menu['good'] = 1;
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('type', config('goods_type'));
            $this->assign('info', $goods);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            $result = $this->validate($data, 'Goods');
            if (true !== $result) {
                $this->error($result);
            }
            $file = $this->request->file('photo');
            //var_dump($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/goods');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    $getSaveName = str_replace("\\","/",$info->getSaveName());
                    $goods->photo = 'goods/'.$getSaveName;
                }else{
                    // 上传失败获取错误信息
                    $this->error('物品图片上传失败:'.$file->getError());
                }
            }
            $goods->name = $data['name'];
            $goods->type = $data['type'];
            $goods->detail = $data['detail'];
            $goods->price = $data['price'];
            $goods->phone = $data['phone'];
            $goods->is_sell = $data['is_sell'];
            $goods->status = $data['status'];
            if ($goods->save()) {
                ActionLog::insertActionLog($this->getLoginUserId(),config('action.update'),'编辑二手商品',implode('|', $data));
                $this->success('编辑成功', 'goods/index');
            } else {
                $this->error('编辑失败');
            }
        }
	}
	public function delete($gid, UsedGood $usedgood)
	{
        $goods = $usedgood->get($gid);
        $str = $goods['gid'].'--'.$goods['name'].'--'.$goods['detail'];
        if ($goods->delete()) {
            ActionLog::insertActionLog($this->getLoginUserId(),config('action.delete'),'删除二手商品',$str);
        	$this->success('删除成功');
        }
        $this->error('删除失败');
	}
	public function enable($gid, UsedGood $usedgood)
	{
        $info = $usedgood->get($gid);
        if (empty($info)) {
        	$this->error('记录不存在');
        }
        $info->status = 2;
        if ($info->save()) {
        	$this->success('上架成功');
        }
        $this->error('上架失败');
	}
    public function disable($gid, UsedGood $usedgood)
	{
        $info = $usedgood->get($gid);
        if (empty($info)) {
        	$this->error('记录不存在');
        }
        $info->status = 1;
        if ($info->save()) {
        	$this->success('下架成功');
        }
        $this->error('下架失败');
	}
}