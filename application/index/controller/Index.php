<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\common\model\CarouselImg;
use app\common\model\News;
use app\common\model\RecruitInfo;
use app\common\model\UsedGood;
use app\index\model\UserNews;
class Index extends Base
{
	public function __construct()
    {
        parent::__construct();
    }
    //首页内容显示
    public function index(CarouselImg $carouselImg, News $news, RecruitInfo $recruitInfo, UsedGood $usedGood)
    {  
        $imgs = $carouselImg->limit(4)->order('sort', 'desc')->select();
        $last_news = $news->limit(8)->order('create_time', 'desc')->select();
        $jobs = $recruitInfo->where('status',1)->limit(4)->order('create_time', 'desc')->select();
        $goods = $usedGood->where('status',2)->limit(4)->order('create_time','desc')->select();    
        $this->assign('imgs',$imgs);
        $this->assign('last_news',$last_news);
        $this->assign('jobs',$jobs);
        $this->assign('goods',$goods);
        return $this->fetch();
    }
    //关于我们页面
    public function about()
    {
    	return $this->fetch();
    }
    public function click_up($id)
    {
        if($this->request->isAjax()){
            $userNews = new UserNews();
            if (empty($this->getLoginUserId())) {
                return '请先登录';
            }
            $row = $userNews->where('uid',$this->getLoginUserId())->where('nid',$id)->where('is_up',1)->find();
            if (!empty($row)) {
                return '你已经赞过了';
            }
            $news = new News();
            $info = $news->get($id);
            $info->click_up = $info->click_up + 1;
            $info->save();
            $handle = $userNews->where('uid',$this->getLoginUserId())->where('nid',$id)->where('is_down',1)->find();
            if (empty($handle)) {
               $handle = new UserNews(); 
            }
            $handle->uid = $this->getLoginUserId();
            $handle->nid = $id;
            $handle->is_up = 1;
            $handle->save();
            return '点赞成功';
        }
        return '系统异常';
    }
    public function click_down($id)
    {
        if($this->request->isAjax()){
            $userNews = new UserNews();
            if (empty($this->getLoginUserId())) {
                return '请先登录';
            }
            $row = $userNews->where('uid',$this->getLoginUserId())->where('nid',$id)->where('is_down',1)->find();
            if (!empty($row)) {
                return '你已经踩过了';
            }
            $news = new News();
            $info = $news->get($id);
            $info->click_down = $info->click_down + 1;
            $info->save();
            $handle = $userNews->where('uid',$this->getLoginUserId())->where('nid',$id)->where('is_up',1)->find();
            if (empty($handle)) {
               $handle = new UserNews(); 
            }
            $handle->uid = $this->getLoginUserId();
            $handle->nid = $id;
            $handle->is_down = 1;
            $handle->save();
            return '踩成功';
        }
        return '系统异常';
    }
}
