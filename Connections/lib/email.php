<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
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
require_once ($_SERVER ['DOCUMENT_ROOT'] . '/Connections/localhost.php');
?>
<?php

/**
 * 获取店铺信息
 */
function get_shop_info() {
	global $db_conn;
	global $db_database_localhost;
	
	$query_shop_info = "SELECT * FROM shop_info WHERE id = 1";
	$shop_info = mysqli_query($db_conn,$query_shop_info  ) or die ( mysqli_error ($localhost).$query_shop_info);
	$row_shop_info = mysqli_fetch_assoc ( $shop_info );
	$totalRows_shop_info = mysqli_num_rows ( $shop_info );
	return $row_shop_info;
}

/**
 * 根据code来发送电子邮件通知
 *
 * @param unknown $code        	
 * @param array $para        	
 * @return boolean
 */
function phpshop123_send_email_template($code, $para = array()) {
	
	// 初始化变量
	global $db_conn;
	global $db_database_localhost;
	$shop_info = get_shop_info (); // 获取店铺信息
	                               
	// 检查邮件服务器信息是否否已经设置完毕，如果没有设置完整的话，那么直接退出
	if ($shop_info ['smtp_server'] == null || $shop_info ['smtp_port'] == null || $shop_info ['smtp_username'] == null || $shop_info ['smtp_password'] == null || $shop_info ['smtp_email'] == null || $shop_info ['smtp_replay_email'] == null) {
		return false;
	}
	
	// 检查是否允许代表这个code的邮件模板可以发送
	
	$row_shop_info = get_shop_info ();
	$query_email_template = "SELECT * FROM email_templates WHERE code = '" . $code . "' and is_delete=0";
	$email_template = mysqli_query($db_conn,$query_email_template ) or die ( mysqli_error ($localhost).$query_email_template);
	$row_email_template = mysqli_fetch_assoc ( $email_template );
	$totalRows_email_template = mysqli_num_rows ( $email_template );
	if ($totalRows_email_template == 0) {
		return false;
	}
	
	// 如果验证都通过的话，那么替换其中的变量
	foreach ( $para as $key => $value ) {
		$row_email_template ['title'] = str_replace ( "<$" . $key . ">", $value, $row_email_template ['title'] );
		$row_email_template ['content'] = str_replace ( "<$" . $key . ">", $value, $row_email_template ['content'] );
	}
	
	// 正式发送邮件
	@phpshop123_send_email ( $row_shop_info ['email'], $row_email_template ['title'], $row_email_template ['content'] );
}

/**
 * 发送电子邮件
 *
 * @param unknown $send_email_to        	
 * @param unknown $subject        	
 * @param unknown $message        	
 * @param array $attch_array        	
 * @return boolean
 */
function phpshop123_send_email($send_email_to, $subject, $message, $attch_array = array()) {
	require $_SERVER ['DOCUMENT_ROOT'] . '/Connections/lib/phpmailer/class.phpmailer.php';
	$row_shop_info = get_shop_info ();
	
	$mail = new PHPMailer ( true );
	$mail->IsSMTP ();
	$mail->CharSet = 'UTF-8'; // 设置邮件的字符编码，这很重要，不然中文乱码
	$mail->SMTPAuth = true; // 开启认证
	if ($row_shop_info ['smtp_ssl'] == 1) {
		$mail->SMTPSecure = "ssl";
	}
	$mail->Port = $row_shop_info ['smtp_port'];
	$mail->Host = $row_shop_info ['smtp_server'];
	$mail->Username = $row_shop_info ['smtp_username'];
	$mail->Password = $row_shop_info ['smtp_password'];
	// $mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
	$mail->AddReplyTo ( $row_shop_info ['smtp_username'], "123phpshop" ); // 回复地址
	$mail->From = $row_shop_info ['smtp_username'];
	$mail->FromName = "www.123phpshop.com";
	
	$mail->AddAddress ( $send_email_to );
	$mail->Subject = $subject;
	$mail->Body = $message;
	$mail->AltBody = "请使用可以浏览html邮件的客户端进行浏览"; // 当邮件不支持html时备用显示，可以省略
	$mail->WordWrap = 80; // 设置每行字符串的长度
	if (is_array ( $attch_array ) && count ( $attch_array ) > 0) {
		foreach ( $attch_array as $item ) {
			$mail->AddAttachment ( $_SERVER ['DOCUMENT_ROOT'] . $item );
		}
	}
	
	$mail->IsHTML ( true );
	$mail->Send ();
	return true;
}