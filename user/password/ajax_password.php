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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php

// 这里对字段进行验证
$validation->set_rules('password', '', 'required|min_length[8]|max_length[18]|alpha_dash');
if (!$validation->run())
{
	$result="false";
}

$result="true";
if(!isset($_SESSION['user_id'])){
		$result="false";
}else{

 	$colname_check_pass = "-1";
	if (isset($_POST['password'])) {
	  $colname_check_pass = (get_magic_quotes_gpc()) ?$_POST['password'] : addslashes($_POST['password']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_check_pass = sprintf("SELECT * FROM `user` WHERE password = '%s' and id= '%s'", md5($colname_check_pass),$_SESSION['user_id']);
	$check_pass = mysql_query($query_check_pass, $localhost) ;if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
	$row_check_pass = mysql_fetch_assoc($check_pass);
	$totalRows_check_pass = mysql_num_rows($check_pass);
	if($totalRows_check_pass==0){
		$result="false";
	}
}
?>
<?php
die($result);
?>