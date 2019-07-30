<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Session;
use app\common\model\User;
use app\common\model\Role;
class Base extends Controller
{
	protected $request;
    public $isCommonUser = 0;
    protected $beforeActionList = [
        'before_getUserInfo' => ['except' => 'login,forgot,reset,active'],
    ];
	public function __construct()
    {
    	$this->request = Request::instance();
    	parent::__construct($this->request);
        if ($this->checkIsCommonUser()) {
            $this->isCommonUser = 1;
        }
    }

	public function _empty($name)
    {
        abort(404,'页面不存在');
    }

    public function saveLoginStatus(array $sessions)
    {
        Session::set('userinfo', $sessions);
    }

    public function checkIsLogin()
    {
        if (!empty(Session::get('userinfo')) && is_array(Session::get('userinfo'))) {
        	return true;
        }
        return false;
    }
    public function checkIsAuth()
    {
        $role = new Role();
        $loginInfo = $this->getLoginInfo();
        $info = $role->get($loginInfo['role_type']);
        $path = $this->request->path();
        $tmp_path = explode('/', $path);
        $path = $tmp_path[0].'/'.$tmp_path[1];
        $menus = explode(',', $info['role_permission']);
        if (in_array($path, $menus)) {
            return true;
        } 
        return false;
    }
    public function getLoginUserId()
    {
    	$loginInfo = Session::get('userinfo');
    	return $loginInfo['uid'];
    }
    public function clearSession()
    {
    	Session::clear();
    }
    public function getLoginInfo()
    {
        return Session::get('userinfo');
    }
    public function saveTokenAndEmail($token, $email)
    {
        Session::init([
            'expire' => 3600,
        ]);
        Session::set($token, $email);
    }
    public function checkIsCommonUser()
    {
        $user = $this->getLoginInfo();
        if ($user['role_type'] == 7) {
            return true;
        }
        return false;
    }
    public function before_getUserInfo()
    {
        $user = new User();
        $uid = $this->getLoginUserId();
        if (!empty($uid)) {
            $userInfo = $user->get($uid);
            if (empty($userInfo->avatar)) {
                $userInfo->avatar = config('default_avatar')[rand(0, 9)];
                $userInfo->save();
                $userInfo = $user->get($uid);
            }
            $role = new Role();
            $loginInfo = $this->getLoginInfo();
            $info = $role->get($loginInfo['role_type']);
            $menus = explode(',', $info['role_permission']);
            $default_menu = config('display_menu'); 
            foreach ($default_menu as $key => $value) {
                if (!in_array($key, $menus)) {
                    $default_menu[$key] = 0;
                }
            }
            $userInfo->role_type = $info['role_name'];
            $this->assign('userinfo', $userInfo);
            $this->assign('default_menu', $default_menu);

        }
    }
}