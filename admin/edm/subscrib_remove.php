<?php require_once('../../Connections/localhost.php'); ?>
<?php

$could_delete=1;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_item = "-1";
if (isset($_GET['id'])) {
  $colname_item = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_item = sprintf("SELECT * FROM email_subscribe WHERE id = %s", $colname_item);
$item = mysql_query($query_item, $localhost) or die(mysql_error());
$row_item = mysql_fetch_assoc($item);
$totalRows_item = mysql_num_rows($item);
if($totalRows_item==0){
	$logger->fatal("用户企图删除一个不存在的订阅邮件");
	$could_delete=0;
}

if($could_delete==1){
	$updateSQL = sprintf("UPDATE email_subscribe SET is_delete=%s WHERE id=%s",
					   GetSQLValueString(1, "int"),
					   GetSQLValueString($_GET['id'], "int"));
	
	mysql_select_db($database_localhost, $localhost);
	$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
	
	$updateGoTo = "subscribe_list.php";
	header(sprintf("Location: %s", $updateGoTo));
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
  <p>由于以下原因，您不能删除这个订阅邮件，请及时修正，或是联系123phpshop.com的技术支持人员！<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div></p>
  <p>1. 记录不存在，请检查参数之后再试。</p>
  <p>2. 系统错误，无法删除，请稍后再试。 </p>
  <p>您也可以<a href="index.php">点击这里返回</a>。
     </p>
</div>
<?php } ?>
</body>
</html>
