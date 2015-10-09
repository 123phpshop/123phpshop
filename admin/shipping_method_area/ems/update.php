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
$query_shipping_method = sprintf("SELECT * FROM shipping_method WHERE config_file_path = 'ems'");
$shipping_method = mysql_query($query_shipping_method, $localhost) or die(mysql_error());
$row_shipping_method = mysql_fetch_assoc($shipping_method);
$totalRows_shipping_method = mysql_num_rows($shipping_method);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 
?>
<?php
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE shipping_method_area SET area=%s, shipping_by_quantity=%s, free_quota=%s, name=%s, single_product_fee=%s, half_kg_fee=%s, continue_half_kg_fee=%s WHERE id=%s",
                       GetSQLValueString($_POST['area'], "text"),
                       GetSQLValueString($_POST['shipping_by_quantity'], "int"),
                       GetSQLValueString($_POST['free_quota'], "double"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['single_product_fee'], "double"),
                       GetSQLValueString($_POST['half_kg_fee'], "double"),
                       GetSQLValueString($_POST['continue_half_kg_fee'], "double"),
                       GetSQLValueString($colname_shipping_method_area, "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
  
   $insertGoTo = "/admin/shipping_method_area/index.php?shipping_method_id=".$row_shipping_method['id'];
   header(sprintf("Location: %s", $insertGoTo));
   
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">EMS国内邮政快递：更新配送区域</p>
<form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="<?php echo $row_shipping_method_area['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Shipping_by_quantity:</td>
      <td valign="baseline"><input <?php if (!(strcmp($row_shipping_method_area['shipping_by_quantity'],"0"))) {echo "checked=\"checked\"";} ?> name="shipping_by_quantity" type="radio" value="0" checked="checked" onchange="by_weight()" />
按重量
  <input <?php if (!(strcmp($row_shipping_method_area['shipping_by_quantity'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="shipping_by_quantity" value="1" onchange="by_quantity()" />
按数量</td>
    </tr>
    <tr valign="baseline" class="by_quantity" style="display:none;">
      <td nowrap align="right">单件商品费用:</td>
      <td><input type="text" name="single_product_fee" value="<?php echo $row_shipping_method_area['single_product_fee']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline" class="by_weight">
      <td nowrap align="right">500克之内费用:</td>
      <td><input type="text" name="half_kg_fee" value="<?php echo $row_shipping_method_area['half_kg_fee']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline" class="by_weight">
      <td nowrap align="right">续费500克费用:</td>
      <td><input type="text" name="continue_half_kg_fee" value="<?php echo $row_shipping_method_area['continue_half_kg_fee']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">免费额度:</td>
      <td><input type="text" name="free_quota" value="<?php echo $row_shipping_method_area['free_quota']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">区域：</td>
      <td><?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/location_sel.php');?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="插入记录"></td>
    </tr>
  </table>
   <input name="area" type="text" value="<?php echo $row_shipping_method_area['area']; ?>" size="150">
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/shipping_method.js"></script>
</body>
</html>
<?php
mysql_free_result($shipping_method_area);
?>
