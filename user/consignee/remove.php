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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
$could_delete=1;
$colname_consignee = "-1";
if (isset($_GET['id'])) {
  $colname_consignee = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_consignee = sprintf("SELECT * FROM user_consignee WHERE id = %s", $colname_consignee);
$consignee = mysql_query($query_consignee, $localhost) or die(mysql_error());
$row_consignee = mysql_fetch_assoc($consignee);
$totalRows_consignee = mysql_num_rows($consignee);

if($totalRows_consignee==0){
	$could_delete=0;
} 

// 检查这个收货人的用户id是否为当前用户的id，不过不是的话，那么不能删除，用户是无法删除不属于自己的收获地址
if($_SESSION['user_id']!=$row_consignee['user_id']){
	$could_delete=0;
}

if($could_delete==1){

	$update_catalog = sprintf("update `user_consignee` set is_delete=1 where id = %s", $colname_consignee);
	$update_catalog_query = mysql_query($update_catalog, $localhost);
	if(!$update_catalog_query){
		$could_delete=0;
	}else{
		$remove_succeed_url="index.php";
		header("Location: " . $remove_succeed_url );
 	}
}
 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php if($could_delete==0){ ?>
<p>由于一下原因，您不能删除这个分类：</p>
<p>1. 	地址不存在，请检查参数之后再试。</p>
<p>2. 系统错误，无法删除，请示稍后再试。 </p>
<p>3.这个地址不属于您</p>
<p>您也可以<a href="index.php">点击这里返回</a>。
  <?php } ?>
</p>
</body>
</html>
<?php
mysql_free_result($consignee);
?>