<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php 
function get_shop_info(){
	global $db_conn;
	global $db_database_localhost;
	mysql_select_db($db_database_localhost, $db_conn);
 	$query_shop_info = "SELECT * FROM shop_info WHERE id = 1";
	$shop_info = mysql_query($query_shop_info, $db_conn) or die(mysql_error());
	$row_shop_info = mysql_fetch_assoc($shop_info);
	$totalRows_shop_info = mysql_num_rows($shop_info);
	return $row_shop_info ;
}

function phpshop123_send_email_template($code,$para=array()){
 
	global $db_conn;
	global $db_database_localhost;
	mysql_select_db($db_database_localhost, $db_conn);
 	$row_shop_info=get_shop_info();
  	$query_email_template = "SELECT * FROM email_templates WHERE code = ".$code;
	$email_template = mysql_query($query_email_template, $db_conn) or die(mysql_error());
	$row_email_template = mysql_fetch_assoc($email_template);
	$totalRows_email_template = mysql_num_rows($email_template);
	if($totalRows_email_template==0){
		return false;
	}
	 
	phpshop123_send_email($row_shop_info['email'],$row_email_template['title'],$row_email_template['content']);
 }
 
function phpshop123_send_email($send_email_to,$subject,$message,$attch_array=array() ){
	 
	require $_SERVER['DOCUMENT_ROOT'].'/Connections/lib/phpmailer/class.phpmailer.php';
	$row_shop_info=get_shop_info();
	
	try {
		$mail = new PHPMailer(true); 
		$mail->IsSMTP();
		$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
		$mail->SMTPAuth   = true;                  //开启认证
		 //if($row_shop_info['smtp_ssl']==1){
			$mail->SMTPSecure = "ssl"; 
		//}
 		echo $mail->Port       = $row_shop_info['smtp_port'];                    
		$mail->Host       = $row_shop_info['smtp_server']; 
		$mail->Username   = $row_shop_info['smtp_username'];   
		echo  $mail->Password   = $row_shop_info['smtp_password'];         
		//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
		$mail->AddReplyTo($row_shop_info['smtp_username'],"123phpshop");//回复地址
		$mail->From       = $row_shop_info['smtp_username'];
		$mail->FromName   = "www.123phpshop.com";
		
 		$mail->AddAddress($send_email_to);
		$mail->Subject  = $subject;
		$mail->Body = $message;
		$mail->AltBody    = "请使用可以浏览html邮件的客户端进行浏览"; //当邮件不支持html时备用显示，可以省略
		$mail->WordWrap   = 80; // 设置每行字符串的长度
		if(is_array($attch_array) && count($attch_array)>0){
			foreach($attch_array as $item){
				$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].$item);
			}
		}
		
  		$mail->IsHTML(true); 
		$mail->Send();
		return true;
	} catch (phpmailerException $e) {
		echo $e->errorMessage();
		return false;
	}
}