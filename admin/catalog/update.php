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
$doc_url="catalogs.html#udpate";
$support_email_question="更新商品分类";
log_admin($support_email_question);
$colname_catalog = "-1";
if (isset($_GET['id'])) {
  $colname_catalog = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_catalog = sprintf("SELECT * FROM `catalog` WHERE id = %s", $colname_catalog);
$catalog = mysqli_query($localhost,$query_catalog);
if(!$catalog){$logger->fatal("数据库操作失败:".$query_catalog);}
$row_catalog = mysqli_fetch_assoc($catalog);
$totalRows_catalog = mysql_num_rows($catalog);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE catalog SET name=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysqli_query($localhost,$updateSQL);
  if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}

  $updateGoTo = "index.php?pid=" . $row_catalog['pid'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_catalog = "-1";
if (isset($_GET['id'])) {
  $colname_catalog = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_catalog = sprintf("SELECT * FROM `catalog` WHERE id = %s", $colname_catalog);
$catalog = mysqli_query($localhost,$query_catalog);
if(!$catalog){$logger->fatal("数据库操作失败:".$query_catalog);}
$row_catalog = mysqli_fetch_assoc($catalog);
$totalRows_catalog = mysql_num_rows($catalog);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">更新商品分类</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>


<a href="index.php">
<input style="float:right;" type="submit" name="Submit2" value="分类列表" />
</a>
<form method="post" name="form1"  id="form1"action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_search_box">
    <tr valign="baseline">
      <td align="right" nowrap >分类名称:</td>
      <td><input type="text" name="name"  id="name" class="required" value="<?php echo $row_catalog['name']; ?>" size="32">
      *</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id"  id="id"value="<?php echo $row_catalog['id']; ?>">
</form>
<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../../js/jquery.validate.min.js"></script>
<script>
$().ready(function(){

	$("#form1").validate({
        rules: {
            name: {
                required: true,
 				remote:{
                    url: "ajax_update_catalog_name.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'name': function(){return $("#name").val();},
						'id': function(){return $("#id").val();}
                    	}
					}
            } 
			
        },
        messages: {
			name: {
  				remote:"分类名称已存在"
            } 
        }
    });
	
});</script>
</body>
</html>
<?php
mysql_free_result($catalog);
?>