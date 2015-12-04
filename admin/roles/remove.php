<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
$doc_url="role.html#delete";
$support_email_question="删除角色";
$could_delete=1;
$colname_product = "-1";
if (isset($_GET['id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT * FROM role WHERE id = %s", $colname_product);
$product = mysql_query($query_product, $localhost) or die(mysql_error());
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);

if($row_product==0){
	$could_delete=0;
} 

if($could_delete==1){
 	$update_catalog = sprintf("update `role` set is_delete=1 where id = %s", $colname_product);
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
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if($could_delete==0){ ?>
<div class="phpshop123_infobox">
  <p>由于以下原因，您不能删除这个角色<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?></p>
  <p>1.	角色不存在，请检查参数之后再试。</p>
  <p>2. 系统错误，无法删除，请示稍后再试。 </p>
  <p>您也可以<a href="index.php">点击这里返回</a>。
    <?php } ?>
    </p>
</div>
</body>
</html>
