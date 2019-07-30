<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\User;
use app\common\model\LoginLog;
use app\common\logic\mailLogic;
use \think\Url;
class Login extends Base
{
    public function __construct()
    {
        parent::__construct();
    }
    //登录
    public function login(User $user)
    {
        if ($this->request->method() == 'GET') {
            return $this->fetch();
        } else {
            $data = $this->request->param();
            if (empty($data['account'])) {
                $this->error('用户名/邮箱不能为空');
            }
            if (empty($data['password'])) {
                $this->error('密码不能为空');
            }
            if(!captcha_check($data['verify'])){
                $this->error('验证码错误');
            }
            $userInfo = $user->where('nickname', $data['account'])->whereOr('email',$data['account'])->find();
            if (empty($userInfo)) {
                $this->error('用户不存在,请检查输入是否正确');
            }
            if (md5($data['password'].$userInfo->salt) != $userInfo->password) {
                LoginLog::saveLoginLog($userInfo->uid,config('login.fail'));
                $row = LoginLog::queryLoginFailInfo($userInfo->uid);
                if ($row['num'] >= 5) {
                    $userInfo->is_freeze = 1;
                    $userInfo->save();
                    $this->error('密码输入错误次数过多，账号将冻结15分钟');
                }
                $this->error('密码输入错误');
            }
            if ($userInfo->status == 2) {
                $this->error('账号已被禁用');
            }
            if ($userInfo->is_freeze == 1 && (time() - strtotime($userInfo->update_time)) < 900) {
                $this->error('账号已冻结，请等解冻后再试');
            }
            if ($userInfo->is_active == 2) {
                $mailLogic = new mailLogic();
                $subject = '知道(激活账号)';
                $token = getToken();
                $url = Url::build('web_login/active','token='.$token, false, true);
                $body = '您好，'.$userInfo['nickname']."：\r\n请点击下面的链接来激活您的账号。\r\n".$url."\r\n如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。\r\n该验证邮件有效期为1小时，超时请重新发送邮件。";
                if ($mailLogic::sendMail($userInfo['email'],$subject,$body)) {
                    $this->saveTokenAndEmail($token, $userInfo['email']);
                    $this->success('账号未激活，系统会发送邮件，请点击邮件内链接激活账号');
                } else {
                    $this->error('账号未激活，系统发送邮件失败，请重试');
                }
                    
            }
            $this->saveLoginStatus(array('uid' => $userInfo->uid, 'nickname' => $userInfo->nickname, 'email' => $userInfo->email, 'role_type' => $userInfo->role_type));
            LoginLog::saveLoginLog($userInfo->uid,config('login.suc'));
            $handle = $user->get($userInfo->uid);
            $handle->last_login_ip = $this->request->ip();
            $handle->last_login_time = date('Y-m-d H:i:s', time());
            $handle->is_freeze = 0;
            $handle->save();
            $this->success('登录成功', 'home/index');
        }
    }
    //注册
    public function register(User $user)
    {
    	if ($this->request->method() == 'GET') {
            return $this->fetch('register');
    	} else {
            //注册
            if(!captcha_check($this->request->param('verify'))){
	            $this->error('验证码错误');
	        }
	        if ($this->request->param('password') != $this->request->param('checkpwd')) {
	        	$this->error('两次输入密码不一致');
	        }
            $row = $user->where('nickname', $this->request->param('nickname'))->find();
            if (!empty($row)) {
                $this->errro('昵称已存在');
            }
            $row2 = $user->where('email', $this->request->param('email'))->find();
            if (!empty($row2)) {
                $this->errro('邮箱已存在');
            }
            $user->nickname = $this->request->param('nickname');
            $user->email = $this->request->param('email');
            $salt = getSalt();
            $user->password = md5($this->request->param('password').$salt);
            $user->salt = $salt;
            $user->user_type = 1;
            $user->role_type = 7;
            $user->is_active = 2;
            $user->save();
            if (empty($user->uid)) {
                $this->error('注册失败');
            }
            $mailLogic = new mailLogic();
            $subject = '知道(激活账号)';
            $token = getToken();
            $url = Url::build('web_login/active','token='.$token, false, true);
            $body = '您好，'.$this->request->param('nickname')."：\r\n请点击下面的链接来激活您的账号。\r\n".$url."\r\n如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。\r\n该验证邮件有效期为1小时，超时请重新发送邮件。";
            if ($mailLogic::sendMail($this->request->param('email'),$subject,$body)) {
                $this->saveTokenAndEmail($token, $this->request->param('email'));
                $this->success('系统会发送邮件，请点击邮件内链接激活账号');
            } else {
                $this->error('系统发送账号激活邮件失败，请重试');
            }
    	}
    }
    //退出登录
    public function logot($uid, User $user)
    {
        $info = $user->get($uid);
        if (empty($info)) {
            $this->error('登录信息错误');
        }
        $this->clearSession();
        $this->redirect('home/index');
    }
    //激活账号
    public function active($token, User $user)
    {
        $suffix = substr($token, 20);
        $session_token = getSessionInfo('token'.$suffix);
        if (empty($session_token)) {
            $this->error('时间已过期', 'web_login/register');
        }
        $email = getSessionInfo($session_token);
        if (empty($email)) {
            $this->error('时间已过期', 'web_login/register');
        }
            
        if ($token != $session_token) {
            $this->error('验证信息不正确', 'web_login/register');
        }
        $info = $user->where('email', $email)->find();
        if (empty($info)) {
            $this->error('邮箱未被登记', 'web_login/register');
        }
        $info->is_active = 1;
        if ($info->save()) {
            clearSession(['token'.$suffix,$session_token]);
            $this->success('激活成功', 'web_login/login');
        }
        $this->error('激活失败', 'web_login/register');
    }
    //忘记密码
    public function forgot(User $user)
    {
        if ($this->request->method() == 'GET') {
            return $this->fetch();
        } else {
            //忘记密码
            $mailLogic = new mailLogic();
            $email = $this->request->param('email');
            $info = $user->where('email', $email)->find();
            if (empty($info)) {
                $this->error('该邮箱未被注册');
            }
            $subject = '知道(找回密码)';
            $token = getTokenForPwd();
            $url = Url::build('web_login/reset','token='.$token, false, true);
            $body = '您好，'.$info['nickname']."：\r\n请点击下面的链接来重置您的密码。\r\n".$url."\r\n如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。\r\n该验证邮件有效期为1小时，超时请重新发送邮件。";
            if ($mailLogic::sendMail($email,$subject,$body)) {
                saveTokenAndEmailWithCache($token, $email);
                $this->success('系统已向该邮箱发送邮件');
            } else {
                $this->error('发送失败,请重新发送');
            }
        }
    }
    //重置密码
    public function reset($token, User $user)
    {
        if ($this->request->method() == 'GET') {
            $suffix = substr($token, 20);
            $session_token = cache('token'.$suffix);
            if (empty($session_token)) {
                $this->error('时间已过期', 'web_login/login');
            }
            $email = cache($session_token);
            if (empty($email)) {
                $this->error('时间已过期', 'web_login/login');
            }
            if ($token != $session_token) {
                $this->error('验证信息不正确', 'web_login/login');
            }
            $info = $user->where('email', $email)->find();
            if (empty($info)) {
                $this->error('邮箱未被登记', 'web_login/login');
            }
            $this->assign('token', $token);
            $this->assign('info', $info);
            return $this->fetch();
        } else {
            //重置密码
            $data = $this->request->param();
            $uid = $data['uid'];
            $token = $data['token'];
            $post_email = $data['email'];
            $newPwd = $data['new_password'];
            $checkPwd = $data['check_password'];
            $salt = getSalt();
            $suffix = substr($token, 20);
            $session_token = cache('token'.$suffix);
            if (empty($session_token)) {
                $this->error('时间已过期', 'web_login/login');
            }
            $email = cache($session_token);
            if (empty($email)) {
                $this->error('时间已过期', 'web_login/login');
            }
            if ($token != $session_token) {
                $this->error('验证信息不正确', 'web_login/login');
            }
            if ($post_email != $email) {
                $this->error('系统错误,请稍后重试');
            }
            if ($newPwd != $checkPwd) {
                $this->error('两次输入密码不一致');
            }
            $info = $user->get($uid);
            if (empty($info)) {
                $this->error('用户信息不存在');
            }
            $info->password = md5($newPwd.$salt);
            $info->salt = $salt;
            if ($info->save()) {
                clearCache(['token'.$suffix,$session_token]);
                $this->success('重置密码成功', 'web_login/login');
            }
            $this->error('重置失败');
        }
    }
}