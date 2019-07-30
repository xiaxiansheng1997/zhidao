<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;
use app\common\model\User;
use app\common\model\FriendLink;
class Base extends Controller
{
	protected $request;
    protected $beforeActionList = [
        'before_getUserInfo' => ['except' => ''],
    ];
	public function __construct()
    {
    	$this->request = Request::instance();
    	parent::__construct($this->request);
    }
    public function checkIsLogin()
    {
        if (!empty(Session::get('userinfo')) && is_array(Session::get('userinfo'))) {
            return true;
        }
        return false;
    }

    public function saveLoginStatus(array $sessions)
    {
        Session::set('userinfo', $sessions);
    }
    public function getLoginInfo()
    {
        return Session::get('userinfo');
    }
    public function getLoginUserId()
    {
        $loginInfo = Session::get('userinfo');
        return $loginInfo['uid'];
    }
    public function saveTokenAndEmail($token, $email)
    {
        Session::init([
            'expire' => 3600,
        ]);
        Session::set($token, $email);
    }
    public function before_getUserInfo()
    {
        $link = new FriendLink();
        $links = $link->limit(10)->order('sort', 'desc')->select();
        $this->assign('links', $links);
        if ($this->checkIsLogin()) {
            $this->assign('islogin', 1);
            $this->assign('userinfo', $this->getLoginInfo());
        } else {
            $this->assign('islogin', 0);
        }
        $goods_type = config('goods_type');
        $news_type = config('news_type');
        $this->assign('goods_type',$goods_type);
        $this->assign('news_type',$news_type);
    }
    public function clearSession()
    {
        Session::clear();
    }
}
