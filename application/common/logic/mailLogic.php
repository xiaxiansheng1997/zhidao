<?php
namespace app\common\logic;
use PHPMailer\PHPMailer;
use \think\Config;
class mailLogic
{
	public static function sendMail($toEmail, $subject, $body)
	{
	    $mail = new PHPMailer\PHPMailer();
	    $mail->isSMTP();// 使用SMTP服务  
	    $mail->CharSet = Config::get('mail.charSet');// 编码格式为utf8，不设置编码的话，中文会出现乱码  
	    $mail->Host = Config::get('mail.host');// 发送方的SMTP服务器地址  
	    $mail->SMTPAuth = Config::get('mail.SMTPAuth');// 是否使用身份验证  
	    $mail->Username = Config::get('mail.username');// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱</span><span style="color:#333333;">  
	    $mail->Password = Config::get('mail.password');// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！</span><span style="color:#333333;">  
	    $mail->SMTPSecure = Config::get('mail.SMTPSecure');// 使用ssl协议方式</span><span style="color:#333333;">  
	    $mail->Port = Config::get('mail.port');// 163邮箱的ssl协议方式端口号是465/994  

	    $mail->setFrom(Config::get('mail.username'),"Mailer");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示  
	    $mail->addAddress($toEmail,'Wang');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)  
	    $mail->addReplyTo(Config::get('mail.username'),"Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址  

        $mail->isHTML(true);

	    $mail->Subject = $subject;// 邮件标题  
	    $mail->Body = $body;// 邮件正文  
	    //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用  

	    if(!$mail->send()){// 发送邮件  
	        return false;
	    }else{  
	        return true;
	    }  
	}
}