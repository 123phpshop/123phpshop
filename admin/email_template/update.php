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

$doc_url="email_template.html#update";
$support_email_question="更新邮件模板";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$colname_email_template = "-1";
if (isset($_GET['id'])) {
  $colname_email_template = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	 mysql_select_db($database_localhost, $localhost);
	$query_product = sprintf("SELECT * FROM email_templates WHERE code = '%s' and is_delete=0 and id!='%s'", trim($_POST['code']),$colname_email_template);
	$product = mysql_query($query_product, $localhost) ;
	if(!$product){$logger->fatal("数据库操作失败:".$query_product);}
	$row_product = mysql_fetch_assoc($product);
	$totalRows_product = mysql_num_rows($product);
	if($totalRows_product>0){
		$error="一个【发送时间】只能有添加一个模板哦";
	}else{
   $updateSQL = sprintf("UPDATE email_templates SET name=%s, code=%s, title=%s, content=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['code'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['content'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) ;
  if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}else{
  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}}
}

$colname_email_template = "-1";
if (isset($_GET['id'])) {
  $colname_email_template = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_email_template = sprintf("SELECT * FROM email_templates WHERE id = %s", $colname_email_template);
$email_template = mysql_query($query_email_template, $localhost) ;
if(!$email_template){$logger->fatal("数据库操作失败:".$query_email_template);}
$row_email_template = mysql_fetch_assoc($email_template);
$totalRows_email_template = mysql_num_rows($email_template);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_email_template > 0) { // Show if recordset not empty ?>
  <form method="post" name="form1" id="form1"  action="<?php echo $editFormAction; ?>">
    <span class="phpshop123_title">更新邮件模板</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
	<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php");?>
    <a href="index.php">
    <input style="float:right;" type="submit" name="Submit2" value="邮件模板列表" />
    </a>
    <table align="center" class="phpshop123_form_box">
      <tr valign="baseline">
        <td nowrap align="right">模板名称:</td>
        <td><input type="text" name="name" value="<?php echo $row_email_template['name']; ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">发送时间:</td>
        <td><select name="code">
			<?php foreach($global_phpshop123_email_send_time as $key=>$value){ ?>
          	<option value="<?php echo $key;?>" <?php if ($key==$row_email_template['code']) {echo "SELECTED";} ?>><?php echo $value;?></option>
		  <?php } ?>
           </select>        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">标题:</td>
        <td><input type="text" name="title" value="<?php echo $row_email_template['title']; ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right" valign="top">内容:</td>
        <td><textarea name="content" cols="50" rows="5"><?php echo $row_email_template['content']; ?></textarea>        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">&nbsp;</td>
        <td><input type="submit" value="更新记录"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id" value="<?php echo $row_email_template['id']; ?>">
  </form>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_email_template == 0) { // Show if recordset empty ?>
  <p class="phpshop123_infobox">邮件模板不存在</p>
  <?php } // Show if recordset empty ?>
 <script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#form1").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
				maxlength: 32
             },
            title: {
                required: true,
                minlength: 2,
 				maxlength: 50   
            },
            content: {
                required: true,
                minlength: 3
            }
        } 
    });
});</script>
  
  </body>
</html>
<?php
mysql_free_result($email_template);
?>