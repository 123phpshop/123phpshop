<?php
header("content-type:text/html;charset=utf-8");
//ini_set("magic_quotes_runtime",0);
require 'class.phpmailer.php';
try {
	$mail = new PHPMailer(true); 
	$mail->IsSMTP();
	$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
	$mail->SMTPAuth   = true;                  //开启认证
	$mail->SMTPSecure = "ssl"; 
	$mail->Port       = 465;                    
	$mail->Host       = "smtp.qq.com"; 
	$mail->Username   = "delivery@123phpshop.com";    
	$mail->Password   = "";            
	//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
	$mail->AddReplyTo("delivery@123phpshop.com","mckee");//回复地址
	$mail->From       = "delivery@123phpshop.com";
	$mail->FromName   = "www.123phpshop.com";
	$to = "163.com";
	$mail->AddAddress($to);
	$mail->Subject  = "123phpshop发货";
	$mail->Body = "<h3>亲爱的123phpshop用户:</h3>附件中是您所需要的货物，请查收.<p><font color=red><a href='www.123phpshop.com'>www.123phpshop.com</a></font></p>";
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
	$mail->WordWrap   = 80; // 设置每行字符串的长度
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/uploads/product/20150928093404_444.zip");  //可以添加附件
	$mail->IsHTML(true); 
	$mail->Send();
	echo '邮件已发送';
} catch (phpmailerException $e) {
	echo "邮件发送失败：".$e->errorMessage();
}
?>