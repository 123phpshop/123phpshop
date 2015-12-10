<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php
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