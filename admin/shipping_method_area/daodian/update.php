<?php require_once('../../../Connections/localhost.php'); ?><?php require_once('../../../Connections/localhost.php'); 


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

$colname_shipping_method_area = "-1";
if (isset($_GET['id'])) {
  $colname_shipping_method_area = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_shipping_method_area = sprintf("SELECT * FROM shipping_method_area WHERE id = %s", $colname_shipping_method_area);
$shipping_method_area = mysql_query($query_shipping_method_area, $localhost) or die(mysql_error());
$row_shipping_method_area = mysql_fetch_assoc($shipping_method_area);
$totalRows_shipping_method_area = mysql_num_rows($shipping_method_area);


mysql_select_db($database_localhost, $localhost);
$query_shipping_method = sprintf("SELECT * FROM shipping_method WHERE config_file_path = 'daodian'");
$shipping_method = mysql_query($query_shipping_method, $localhost) or die(mysql_error());
$row_shipping_method = mysql_fetch_assoc($shipping_method);
$totalRows_shipping_method = mysql_num_rows($shipping_method);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$updateSQL = sprintf("UPDATE shipping_method_area SET area=%s, name=%s WHERE id=%s",
				   GetSQLValueString($_POST['area'], "text"),
				   GetSQLValueString($_POST['name'], "text"),
				   GetSQLValueString($colname_shipping_method_area, "int"));

mysql_select_db($database_localhost, $localhost);
$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

$insertGoTo = "/admin/shipping_method_area/index.php?shipping_method_id=".$row_shipping_method['id'];
header(sprintf("Location: %s", $insertGoTo));
}

$colname_shipping_method = "-1";
if (isset($_GET['config_file_path'])) {
  $colname_shipping_method = (get_magic_quotes_gpc()) ? $_GET['config_file_path'] : addslashes($_GET['config_file_path']);
}

?><?php

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form method="POST" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <p class="phpshop123_title">到店自提:更新配送区域</p>
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input name="name" type="text" value="<?php echo $row_shipping_method_area['name']; ?>" size="32" maxlength="32">
*</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">配送区域</td>
      <td><?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/location_sel.php');?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新"></td>
    </tr>
  </table>
<input type="hidden" name="area" value="<?php echo $row_shipping_method_area['area']; ?>">
<input type="hidden" name="MM_update" value="form1">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/shipping_method.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){

	$("#form1").validate({
        rules: {
            name: {
                required: true
            }
        },
        messages: {
            name: {
                required: "必填" 
            }
        }
    });
	
});
</script>
</body>
</html>
<?php
mysql_free_result($shipping_method_area);

mysql_free_result($shipping_method);
?>
