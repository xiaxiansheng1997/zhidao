<?php
namespace app\admin\Controller;
use app\admin\controller\Base;
use app\admin\model\Config as cf;
use app\common\model\FriendLink;
use app\common\model\CarouselImg;
use app\common\model\User;
use app\admin\model\ActionLog;
use \think\Url;
/**
 * @author xiayujie <1438641583@qq.com>
 * @date(2019/04/20 22:21 PM)
 */
class Config extends Base
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
    public function index(User $user)
    {
        $data['server_addr'] = $_SERVER['SERVER_ADDR']; //服务器ip
        $data['server_name'] = $_SERVER['SERVER_NAME']; //服务器域名
        $data['server_port'] = $_SERVER['SERVER_PORT']; //服务器端口  
        $data['server_version'] = php_uname('s').php_uname('r'); //服务器版本
        $data['server_systerm'] = php_uname(); //服务器操作系统
        $data['php_version'] = PHP_VERSION;   //PHP版本
        $data['server_time'] = date("Y-m-d H:i:s"); //服务器当前时间 
        $data['upload_max_filesize'] = get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许"; //最大上传限制
        $uid = $this->getLoginUserId();
        $userInfo = $user->get($uid);
        //统计用户(普通用户,管理员)人数
        $userTypeCount = $user->queryUserCountByType();
        foreach ($userTypeCount as $key => $value) {
            if ($value['user_type'] == 1) {
                $normal = $value['count'];
            } elseif ($value['user_type'] == 2) {
                $manager = $value['count'];
            }
        }
        $todayLoginCount = $user->queryTodayLoginCount();
        $menu = config('menu');
        $menu['system'] = 1;
        $menu['basic'] = 1;
        $breadcrumb = [
           ['menu' => '网站基本信息', 'href' => ''],
        ];
        $this->assign('menu', $menu);
        $this->assign('data', $data);
        $this->assign('normal', $normal ?? 0);
        $this->assign('manager', $manager ?? 0);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('num', $todayLoginCount['num']);
        return $this->fetch('index');
    }

    public function edit(cf $config)
    {
        $configs = $config->where('config_name', config('base_config'))->find();
    	if ($this->request->method() == 'GET') {
            $menu = config('menu');
            $menu['system'] = 1;
            $menu['web'] = 1;
            $breadcrumb = [
               ['menu' => '网站配置', 'href' => ''],
            ];
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('configs', json_decode($configs->config_value, true));
            return $this->fetch();
    	} else {
            $param = $this->request->param();
            $result = $this->validate($param,'Config');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }
            $configs->config_value = json_encode($param);
            if ($configs->save()) {
                ActionLog::insertActionLog($this->getLoginUserId(),config('action.update'),'编辑网站配置信息',implode('|', $param));
            	$this->success('更新成功');
            } else {
            	$this->error('更新失败');
            }
    	}
    }
    public function friendlink(FriendLink $friendlink)
    {
        if ($this->isCommonUser == 1) {
            $info = $friendlink->where('uid',$this->getLoginUserId())->order('sort', 'desc')->paginate(10);
        } else {
            $info = $friendlink->order('sort', 'desc')->paginate(10);
        }
        $menu = config('menu');
        $menu['system'] = 1;
        $menu['link'] = 1;
        $breadcrumb = [
           ['menu' => '友情链接', 'href' => ''],
        ];
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $info);
        return $this->fetch();
    }
    public function link_add(FriendLink $friendlink)
    {
        if ($this->request->method() == 'GET') {
            $menu = config('menu');
            $menu['system'] = 1;
            $menu['link'] = 1;
            $breadcrumb = [
               ['menu' => '友情链接', 'href' => Url::build('config/friendlink','', false, true)],
               ['menu' => '添加', 'href' => ''],
            ];
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            $result = $this->validate($data,'FriendLink');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }
            $friendlink->title = $data['title'];
            $friendlink->link = $data['link'];
            $friendlink->uid = $this->getLoginUserId();
            $friendlink->sort = $data['sort'];
            $friendlink->save();
            if ($friendlink->lid) {
                ActionLog::insertActionLog($this->getLoginUserId(),config('action.insert'),'新增友情链接',implode('|', $data));
                $this->success("添加成功", 'config/friendlink');
            } else {
                $this->error("添加失败");
            }
        }
    }
    public function link_edit($lid, FriendLink $friendlink)
    {
        if ($this->request->method() == 'GET') {
            $link = $friendlink->get($lid);
            if (empty($link)) {
                $this->error('链接信息不存在');
            }
            $menu = config('menu');
            $menu['system'] = 1;
            $menu['link'] = 1;
            $breadcrumb = [
               ['menu' => '友情链接', 'href' => Url::build('config/friendlink','', false, true)],
               ['menu' => '编辑', 'href' => ''],
            ];
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('link', $link);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            $result = $this->validate($data,'FriendLink');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }
            $link = $friendlink->get($data['lid']);
            if (empty($link)) {
                $this->error('链接信息不存在');
            }
            $link->title = $data['title'];
            $link->link = $data['link'];
            $link->sort = $data['sort'];
            if ($link->save()) {
                ActionLog::insertActionLog($this->getLoginUserId(),config('action.update'),'编辑友情链接',json_encode($data,JSON_UNESCAPED_UNICODE));
                $this->success("编辑成功",'config/friendlink');
            } else {
                $this->error("编辑失败");
            }
        }
    }
    public function link_delete($lid, FriendLink $friendlink)
    {
        $info = $friendlink->get($lid);
        $data = $info->lid.'--'.$info->title.'--'.$info->link;
        if ($info->delete()) {
            ActionLog::insertActionLog($this->getLoginUserId(),config('action.delete'),'删除友情链接',$data);
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }
    public function carousel_img(CarouselImg $carouselimg)
    {
        if ($this->isCommonUser == 1) {
            $info = $carouselimg->where('uid',$this->getLoginUserId())->order('sort', 'desc')->paginate(10);
        } else {
            $info = $carouselimg->order('sort', 'desc')->paginate(10);
        }
        $menu = config('menu');
        $menu['system'] = 1;
        $menu['img'] = 1;
        $breadcrumb = [
           ['menu' => '轮播图管理', 'href' => ''],
        ];
        $this->assign('menu', $menu);
        $this->assign('breadcrumb', $breadcrumb);
        $this->assign('info', $info);
        return $this->fetch();
    }
    public function carousel_img_add(CarouselImg $carouselimg)
    {
        if ($this->request->method() == 'GET') {
            $menu = config('menu');
            $menu['system'] = 1;
            $menu['img'] = 1;
            $breadcrumb = [
               ['menu' => '轮播图管理', 'href' => Url::build('config/carousel_img','', false, true)],
               ['menu' => '添加', 'href' => ''],
            ];
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            if ($data['sort'] < 0 || $data['sort'] > 12) {
                $this->error('排序字段必须在0-12范围内');
            }
            $file = $this->request->file('carousel');
            //var_dump($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/carousel');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    $getSaveName = str_replace("\\","/",$info->getSaveName());
                    $carouselimg->img = 'carousel/'.$getSaveName;
                }else{
                    // 上传失败获取错误信息
                    $this->error('轮播图上传失败:'.$file->getError());
                }
            } else {
                $this->error('轮播图必须上传');
            }
            $carouselimg->uid = $this->getLoginUserId();
            $carouselimg->sort = $data['sort'];
            $carouselimg->save();
            if ($carouselimg->id) {
                ActionLog::insertActionLog($this->getLoginUserId(),config('action.insert'),'新增轮播图',json_encode($data,JSON_UNESCAPED_UNICODE));
                $this->success("添加成功",'config/carousel_img');
            } else {
                $this->error("添加失败");
            }
        }
    }
    public function carousel_img_edit($id, CarouselImg $carouselimg)
    {
        if ($this->request->method() == 'GET') {
            $info = $carouselimg->get($id);
            $menu = config('menu');
            $menu['system'] = 1;
            $menu['img'] = 1;
            $breadcrumb = [
               ['menu' => '轮播图管理', 'href' => Url::build('config/carousel_img','', false, true)],
               ['menu' => '编辑', 'href' => ''],
            ];
            $this->assign('menu', $menu);
            $this->assign('breadcrumb', $breadcrumb);
            $this->assign('info', $info);
            return $this->fetch();
        } else {
            $data = $this->request->param();
            if ($data['sort'] < 0 || $data['sort'] > 12) {
                $this->error('排序字段必须在0-12范围内');
            }
            $file = $this->request->file('carousel');
            $handle = $carouselimg->get($data['id']);
            if (empty($handle)) {
                $this->error('该记录不存在');
            }
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/carousel');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    $getSaveName = str_replace("\\","/",$info->getSaveName());
                    $handle->img = 'carousel/'.$getSaveName;
                }else{
                    // 上传失败获取错误信息
                    $this->error('轮播图上传失败:'.$file->getError());
                }
            }
            $handle->sort = $data['sort'];
            if ($handle->save()) {
                ActionLog::insertActionLog($this->getLoginUserId(),config('action.update'),'编辑轮播图',implode('|', $data));
                $this->success("编辑成功", 'config/carousel_img');
            } else {
                $this->error("编辑失败");
            }
        }
    }
    public function carousel_img_delete($id, CarouselImg $carouselimg)
    {
        $info = $carouselimg->get($id);
        $str = $info['id'].'--'.$info['img'];
        if ($info->delete()) {
            ActionLog::insertActionLog($this->getLoginUserId(),config('action.delete'),'删除轮播图',$str);
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }
}