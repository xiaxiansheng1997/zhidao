<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\common\model\CarouselImg;
use app\common\model\News;
use app\common\model\RecruitInfo;
use app\common\model\UsedGood;
class Show extends Base
{
	public function __construct()
    {
        parent::__construct();
    }
    //高校资讯内容显示
    public function news($id, News $news)
    {  
        $info = $news->get($id);
        $info->r_count = $info->r_count + 1;
        $info->save();
        $handle = $news->get($id);
        $handle->type = config('news_type')[$handle->type];
        $last = $news->limit(7)->order('create_time','desc')->select();
        $this->assign('info',$handle);
        $this->assign('last',$last);
        return $this->fetch();
    }
    //兼职信息内容显示
    public function jobs($id, RecruitInfo $recruitInfo)
    {
        $info = $recruitInfo->get($id);
        $last = $recruitInfo->where('status',1)->limit(7)->order('create_time','desc')->select();
        $this->assign('info',$info);
        $this->assign('last',$last);
    	return $this->fetch();
    }
    //二手物品内容显示
    public function goods($id, UsedGood $usedGood)
    {
        $info = $usedGood->get($id);
        $info->type = config('goods_type')[$info->type];
        $last = $usedGood->where('status',2)->limit(7)->order('create_time','desc')->select();
        $this->assign('info',$info);
        $this->assign('last',$last);
        return $this->fetch();
    }
}
