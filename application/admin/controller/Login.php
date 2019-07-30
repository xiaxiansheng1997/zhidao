<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\User;
use app\common\model\LoginLog;
use app\common\logic\mailLogic;
use \think\Url;
class Login extends Base 
{

    public function __construct()
    {
        parent::__construct();
    }
	public function login(User $user)
	{
        if ($this->checkIsLogin()) {
            $this->redirect('config/index');
        }
        if ($this->request->method() == 'GET') {
            return $this->fetch();
        } else {
           $nickname = $this->request->param('nickname');
           $email = $this->request->param('email');
           $password = $this->request->param('password');
           $userInfo = $user->where('nickname', $nickname)->whereOr('email',$email)->find();
           if (empty($userInfo)) {
           	    $this->error('用户不存在,请检查输入是否正确');
           }
           if (md5($password.$userInfo->salt) != $userInfo->password) {
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
                $url = Url::build('user/active','token='.$token, false, true);
                $body = '您好，'.$userInfo['nickname']."：\r\n请点击下面的链接来激活您的账号。\r\n".$url."\r\n如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。\r\n该验证邮件有效期为1小时，超时请重新发送邮件。";
                if ($mailLogic::sendMail($userInfo['email'],$subject,$body)) {
                    $this->saveTokenAndEmail($token, $userInfo['email']);
                    $this->success('账号未激活，系统会发送邮件，请点击邮件内链接激活账号');
                } else {
                    $this->error('账号未激活，系统发送邮件失败，请重试');
                }
                    
            }
            if ($userInfo->user_type == 1) {
                $this->error('你不是管理员');
            }
           $this->saveLoginStatus(array('uid' => $userInfo->uid, 'nickname' => $userInfo->nickname, 'email' => $userInfo->email, 'role_type' => $userInfo->role_type));
           LoginLog::saveLoginLog($userInfo->uid,config('login.suc'));
           $handle = $user->get($userInfo->uid);
           $handle->last_login_ip = $this->request->ip();
           $handle->last_login_time = date('Y-m-d H:i:s', time());
           $handle->is_freeze = 0;
           $handle->save();
           $this->success('登录成功', 'person/index');
        }
	}
    public function forgot(User $user)
    {
        if ($this->request->method() == 'GET') {
            return $this->fetch();
        } else {
            $mailLogic = new mailLogic();
            $email = $this->request->param('email');
            $info = $user->where('email', $email)->find();
            if (empty($info)) {
                $this->error('该邮箱未被注册');
            }
            $subject = '知道(找回密码)';
            $token = getTokenForPwd();
            $url = Url::build('user/reset','token='.$token, false, true);
            $body = '您好，'.$info['nickname']."：\r\n请点击下面的链接来重置您的密码。\r\n".$url."\r\n如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。\r\n该验证邮件有效期为1小时，超时请重新发送邮件。";
            if ($mailLogic::sendMail($email,$subject,$body)) {
                saveTokenAndEmailWithCache($token, $email);
                $this->success('发送成功');
            } else {
                $this->error('发送失败,请重新发送');
            }
        }
    }
    public function reset($token, User $user)
    {
        if ($this->request->method() == 'GET') {
            $suffix = substr($token, 20);
            $session_token = cache('token'.$suffix);
            if (empty($session_token)) {
                $this->error('时间已过期', 'user/login');
            }
            $email = cache($session_token);
            if (empty($email)) {
                $this->error('时间已过期', 'user/login');
            }
            if ($token != $session_token) {
                $this->error('验证信息不正确', 'user/login');
            }
            $info = $user->where('email', $email)->find();
            if (empty($info)) {
                $this->error('邮箱未被登记', 'user/login');
            }
            $this->assign('token', $token);
            $this->assign('info', $info);
            return $this->fetch();
        } else {
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
                $this->error('时间已过期', 'user/login');
            }
            $email = cache($session_token);
            if (empty($email)) {
                $this->error('时间已过期', 'user/login');
            }
            if ($token != $session_token) {
                $this->error('验证信息不正确', 'user/login');
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
                $this->success('重置密码成功', 'user/login');
            }
            $this->error('重置失败');
        }
    }
    public function active($token, User $user)
    {
        $suffix = substr($token, 20);
        $session_token = getSessionInfo('token'.$suffix);
        if (empty($session_token)) {
            $this->error('时间已过期', 'user/login');
        }
        $email = getSessionInfo($session_token);
        if (empty($email)) {
            $this->error('时间已过期', 'user/login');
        }
            
        if ($token != $session_token) {
            $this->error('验证信息不正确', 'user/login');
        }
        $info = $user->where('email', $email)->find();
        if (empty($info)) {
            $this->error('邮箱未被登记', 'user/login');
        }
        $info->is_active = 1;
        if ($info->save()) {
            clearSession(['token'.$suffix,$session_token]);
            $this->success('激活成功', 'user/login');
        }
        $this->error('激活失败', 'user/login');
    }
    public function logot($uid, User $user)
    {
        $info = $user->get($uid);
        if (empty($info)) {
            $this->error('登录信息错误');
        }
        $this->clearSession();
        $this->redirect('user/login');
    }
}