<?php 

function phpshop123_send_email($send_email_to,$subject,$message,$attch_array=array() ){
	require $_SERVER['DOCUMENT_ROOT'].'/Connections/lib/phpmailer/class.phpmailer.php';
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
 		$mail->AddAddress($send_email_to);
		$mail->Subject  = $subject;
		$mail->Body = $message;
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
		$mail->WordWrap   = 80; // 设置每行字符串的长度
		if($attch_array!=array() &&　trim($attch_array)!=''　){
			$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/uploads/product/20150928093404_444.zip");  //可以添加附件
		}
		if($attch_array=array() && count($attch_array)>0){
			foreach($attch_array as $item){
				$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].$item;  //可以添加附件
			}
		}
		
  		$mail->IsHTML(true); 
		$mail->Send();
		return true;
	} catch (phpmailerException $e) {
		$e->errorMessage();
		return false;
	}

}
