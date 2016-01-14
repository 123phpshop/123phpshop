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
$doc_url="mail.html";
$support_email_question="设置邮件服务器";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE shop_info SET smtp_ssl=%s,smtp_replay_email=%s,smtp_email=%s, smtp_server=%s, smtp_port=%s, smtp_username=%s, smtp_password=%s WHERE id=%s",
				GetSQLValueString(isset($_POST['smtp_ssl'])?1:0, "int"),
				GetSQLValueString($_POST['smtp_replay_email'], "text"),
				GetSQLValueString($_POST['smtp_email'], "text"),
				GetSQLValueString($_POST['smtp_server'], "text"),
				GetSQLValueString($_POST['smtp_port'], "int"),
				GetSQLValueString($_POST['smtp_username'], "text"),
				GetSQLValueString($_POST['smtp_password'], "text"),
				GetSQLValueString($_POST['id'], "int"));
 
  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) ;
  if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
}

mysql_select_db($database_localhost, $localhost);
$query_smtp_info = "SELECT * FROM shop_info WHERE id = 1";
$smtp_info = mysql_query($query_smtp_info, $localhost) ;
if(!$smtp_info){$logger->fatal("数据库操作失败:".$query_smtp_info);}
$row_smtp_info = mysql_fetch_assoc($smtp_info);
$totalRows_smtp_info = mysql_num_rows($smtp_info);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">邮件服务器设置</span>
<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<form method="post" name="form1" id="form1"  action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">Smtp邮件地址:</td>
      <td><input name="smtp_email" type="text" value="<?php echo $row_smtp_info['smtp_email']; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp服务器地址:</td>
      <td><input name="smtp_server" type="text" value="<?php echo $row_smtp_info['smtp_server']; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp端口:</td>
      <td><input name="smtp_port" type="text" value="<?php echo $row_smtp_info['smtp_port']; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp登录账户:</td>
      <td><input name="smtp_username" type="text" value="<?php echo $row_smtp_info['smtp_username']; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp登录密码:</td>
      <td><input type="text" name="smtp_password" value="<?php echo $row_smtp_info['smtp_password']; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">SSL加密</td>
      <td><input type="checkbox" name="smtp_ssl" maxlength="32"  id="smtp_ssl" value="<?php echo isset($row_smtp_info['smtp_ssl'])?:$row_smtp_info['smtp_ssl'];1 ?>" <?php if($row_smtp_info['smtp_ssl']==1){?>checked <?php } ?>></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">回复邮件地址</td>
      <td><input type="text" name="smtp_replay_email" maxlength="32" value="<?php echo $row_smtp_info['smtp_replay_email']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_smtp_info['id']; ?>">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#form1").validate({
        rules: {
             smtp_email: {
                required: true,
				minlength: 2,
				maxlength: 32,
				email:true
             },
            smtp_server: {
                required: true,
                minlength: 11,
				maxlength: 32  
            },
            smtp_port: {
                required: true,
                minlength: 3,
				digits:true ,
 				maxlength: 32   
            },
 			smtp_username: {
                required: true,
                minlength: 3 ,
				maxlength: 32  
            },
			smtp_password: {
                required: true,
                minlength: 3 ,
				maxlength: 32  
            },
			smtp_replay_email: {
                required: true,
                minlength: 3 ,
				maxlength: 32,
				email:true  
            }
        } 
    });
});</script>
</body>
</html>