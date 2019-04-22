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
$doc_url="mail.html#send_when";
$support_email_question="设置邮件发送时间";
log_admin($support_email_question);
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE shop_info SET send_when=%s WHERE id=%s",
                       GetSQLValueString(isset($_POST['send_when'])?implode(",",$_POST['send_when']):"","text"),
                       GetSQLValueString(1,"int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) ;
  if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
}

$send_when_array=array();
mysql_select_db($database_localhost, $localhost);
$query_send_when = "SELECT id, send_when FROM shop_info WHERE id = 1 and send_when is not null";
$send_when = mysql_query($query_send_when, $localhost) ;
if(!$send_when){$logger->fatal("数据库操作失败:".$query_send_when);}
$row_send_when = mysql_fetch_assoc($send_when);
$totalRows_send_when = mysql_num_rows($send_when);
if($totalRows_send_when>0){
 	$send_when_array=explode(",",$row_send_when["send_when"]);
}
 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">邮件发送设置</span>
<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <p>&nbsp;</p>
  <table width="200" border="0" class="phpshop123_form_box">
  	<?php foreach($global_phpshop123_email_send_time as $key=>$value){ ?>
    <tr>
      <td width="4%"> 
        <input type="checkbox" name="send_when[]" value="<?php echo $key;?>" <?php if(in_array((string)$key,$send_when_array)){ ?> checked <?php } ?>/>
      </td>
      <td width="96%"><?php echo $value;?></td>
	  <?php } ?>
    </tr>
  </table>
  <p>
    <label>
    <input type="submit" name="Submit" value="提交" />
    </label>
  </p>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($send_when);
?>