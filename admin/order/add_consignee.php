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
 ?><?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
//		检查用户是否已经有了收货人记录，如果没有的话，那么自动设置为默认
	$is_default=0;
	$colname_consignees = "-1";
	if (isset($_GET['user_id'])) {
	  $colname_consignees = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_consignees = sprintf("SELECT * FROM user_consignee WHERE is_delete=0 and user_id = %s order by is_default desc", $colname_consignees);
	$consignees = mysqli_query($localhost,$query_consignees);
	if(!$consignees){$logger->fatal("数据库操作失败:".$query_consignees);}
 	$totalRows_consignees = mysql_num_rows($consignees);
	if($totalRows_consignees==0){
		$is_default=1;
	}
	
	$update_catalog = sprintf("update `user_consignee` set is_default=0 where user_id=%s and id != %s",$_GET['user_id'], $colname_consignees);
	$update_catalog_query = mysqli_query($localhost,$update_catalog);
		
   $insertSQL = sprintf("INSERT INTO user_consignee (is_default,name, mobile, province, city, district, address, zip, user_id) VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s)",
  					   GetSQLValueString($is_default, "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['mobile'], "text"),
                       GetSQLValueString($_POST['province'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['district'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['zip'], "text"),
                       GetSQLValueString($_GET['user_id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysqli_query($localhost,$insertSQL);
  if(!$Result1){$logger->fatal("数据库操作失败:".$insertSQL);}
  
   $insertGoTo = "update_order_user.php?id=".$_GET['order_id'];
   header(sprintf("Location: %s", $insertGoTo));
  
}

$colname_consignees = "-1";
if (isset($_GET['user_id'])) {
  $colname_consignees = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_consignees = sprintf("SELECT * FROM user_consignee WHERE is_delete=0 and user_id = %s order by is_default desc", $colname_consignees);
$consignees = mysqli_query($localhost,$query_consignees);
if(!$consignees){$logger->fatal("数据库操作失败:".$query_consignees);}
$row_consignees = mysqli_fetch_assoc($consignees);
$totalRows_consignees = mysql_num_rows($consignees);

$doc_url="order.html#add_consignee";
$support_email_question="添加订单";
log_admin($support_email_question);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
<!--
table{
	border-collapse:collapse;
}
-->
</style>
 <link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
 <style type="text/css">
<!--
.STYLE3 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<span class="phpshop123_title">添加收货人</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
  <a href="index.php">
  <input style="float:right;" type="submit" name="Submit2" value="订单详细页面" />
  </a>
  <form method="post" name="form1" id="new_consignee_form" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">收货人:</td>
      <td><input name="name" type="text" value="" size="32" maxlength="10"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">手机:</td>
      <td><input name="mobile" type="text" value="" size="32" maxlength="11"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">省市:</td>
      <td><?php include($_SERVER['DOCUMENT_ROOT'].'/widget/area/index.php');?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">地址:</td>
      <td><input name="address" type="text" value="" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">邮编:</td>
      <td><input name="zip" type="text" value="" size="32" maxlength="6"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </table>
   <input type="hidden" name="MM_insert" value="form1">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#new_consignee_form").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
             },
            mobile: {
                required: true,
                minlength: 11,
				digits:true   
            },
            address: {
                required: true,
                minlength: 3   
            },
 			zip: {
                required: true,
                minlength: 6,
				digits:true
            }
        } 
    });
});</script>
  
</body>
</html>