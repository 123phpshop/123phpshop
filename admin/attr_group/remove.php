<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
?>
<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_admin = "-1";
$could_delete=1;
$doc_url="ad.html#list";
$support_email_question="删除属性";
$remove_succeed_url="index.php";
if (isset($_GET['id'])) {
  $colname_admin = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_admin = sprintf("SELECT * FROM product_type_attr WHERE id = %s", $colname_admin);
$admin = mysql_query($query_admin, $localhost) or die(mysql_error());
$row_admin = mysql_fetch_assoc($admin);
$totalRows_admin = mysql_num_rows($admin);
 if($totalRows_admin==0){
	$could_delete=0;
}
if($could_delete==1){
	
	if($row_admin['is_delete']=='1'){
		header("Location: " . $remove_succeed_url );
	}
	
	$update_catalog = sprintf("update `product_type_attr` set is_delete=1 where id = %s", $colname_admin);
	$update_catalog_query = mysql_query($update_catalog, $localhost);
	if(!$update_catalog_query){
		$could_delete=0;
	}else{
		$remove_succeed_url="index.php?product_type_id=".$row_admin['product_type_id'];
 		header("Location: " . $remove_succeed_url );
 	}
	
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if($could_delete==0){ ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<div class="phpshop123_infobox">
  <p>由于一下原因，您不能删除这个类型属性：</p>
  <p>1. 记录不存在，请检查参数之后再试。</p>
  <p>2. 系统错误，无法删除，请示稍后再试。 </p>
  <p>您也可以<a href="index.php">点击这里返回</a>。
     </p>
</div>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($admin);
?>
