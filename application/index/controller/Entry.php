<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\common\model\CarouselImg;
use app\common\model\News;
use app\common\model\RecruitInfo;
use app\common\model\UsedGood;
class Entry extends Base
{
	public function __construct()
    {
        parent::__construct();
    }
    //高校资讯列表
    public function news($type, News $news)
    {  
        if ($type == 0) {
            $info = $news->order('create_time', 'desc')->paginate(20);
        } else {
            $info = $news->where('type',$type)->order('create_time', 'desc')->paginate(20);
        }
        $this->assign('news',$info);
        return $this->fetch();
    }
    //兼职招聘列表
    public function jobs(RecruitInfo $recruitInfo)
    {
        $info = $recruitInfo->where('status',1)->paginate(20);
        $this->assign('jobs',$info);
    	return $this->fetch();
    }
    //二手物品列表
    public function goods($type, UsedGood $usedGood)
    {
        if ($type == 0) {
            $info = $usedGood->where('status',2)->order('create_time', 'desc')->paginate(20);
        } else {
            $info = $usedGood->where('type',$type)->where('status',2)->order('create_time', 'desc')->paginate(20);
        }
        $this->assign('goods',$info);
        return $this->fetch();
    }
}
