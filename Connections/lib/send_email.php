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
$shop_email = mysql_query($query_shop_email, $localhost) ;if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
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
$email_template = mysql_query($query_email_template, $localhost) ;if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
$row_email_template = mysql_fetch_assoc($email_template);
$totalRows_email_template = mysql_num_rows($email_template);
if($totalRows_email_template==0){
		return;
}

require_once($_SERVER['DOCUMENT_ROOT']."/Connections/lib/email.php");
// 如果可以获取的话，那么发送邮件即可
phpshop123_send_email($shop_email,$row_email_template['code'],$row_email_template['content']);
?>