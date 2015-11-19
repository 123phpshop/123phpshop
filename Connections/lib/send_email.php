<?php
$shop_email='';
$subject='';
$message='';

// 检查发送邮件的编码，如果编码没有设置，那么直接返回
if(!isset($email_template_code)){
	return;
}
// 如果设置了话，那么代码是否在允许的范围内，如果不存在的话，那么直接返回
if(!in_array($email_template_code,$global_phpshop123_email_send_time_array)){
	return;
}

// 获取这个网店的邮件地址
mysql_select_db($database_localhost, $localhost);
$query_shop_email = "SELECT email FROM shop_info WHERE id = 1";
$shop_email = mysql_query($query_shop_email, $localhost) or die(mysql_error());
$row_shop_email = mysql_fetch_assoc($shop_email);
$totalRows_shop_email = mysql_num_rows($shop_email);

//	如果没有设置的话，那么返回
if($totalRows_shop_email==0){
	return;
}

// 如果有设置的话，那么初始化email变量
$shop_email=$row_shop_email['email'];

// 检查邮件发送模板的id，然后获取相应的模板，如果不能获取的话，那么直接返回
mysql_select_db($database_localhost, $localhost);
$query_email_template = "SELECT * FROM email_templates WHERE is_delete=0 and  code = '".$email_template_code."'";
$email_template = mysql_query($query_email_template, $localhost) or die(mysql_error());
$row_email_template = mysql_fetch_assoc($email_template);
$totalRows_email_template = mysql_num_rows($email_template);
if($totalRows_email_template==0){
		return;
}

// 如果可以获取的话，那么发送邮件即可
phpshop123_send_email($shop_email,$row_email_template['code'],$row_email_template['content']);
?>