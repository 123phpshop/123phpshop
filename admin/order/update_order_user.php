<?php require_once('../../Connections/localhost.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE orders SET user_id=%s, consignee_id=%s, invoice_is_needed=%s, invoice_title=%s, invoice_message=%s, please_delivery_at=%s, consignee_name=%s, consignee_province=%s, consignee_city=%s, consignee_district=%s, consignee_address=%s, consignee_zip=%s, consignee_mobile=%s WHERE id=%s",
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['consignee_id'], "int"),
                       GetSQLValueString($_POST['invoice_is_needed'], "int"),
                       GetSQLValueString($_POST['invoice_title'], "text"),
                       GetSQLValueString($_POST['invoice_message'], "text"),
                       GetSQLValueString($_POST['please_delivery_at'], "int"),
                       GetSQLValueString($_POST['consignee_name'], "text"),
                       GetSQLValueString($_POST['consignee_province'], "text"),
                       GetSQLValueString($_POST['consignee_city'], "text"),
                       GetSQLValueString($_POST['consignee_district'], "text"),
                       GetSQLValueString($_POST['consignee_address'], "text"),
                       GetSQLValueString($_POST['consignee_zip'], "text"),
                       GetSQLValueString($_POST['consignee_mobile'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "index.php?id=" . $row_order['id'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_order = "-1";
if (isset($_GET['id'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE id = %s", $colname_order);
$order = mysql_query($query_order, $localhost) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>更新订单用户</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">更新订单用户</p>
<p>&nbsp; </p>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" class="phpshop123_form_box">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">User_id:</td>
      <td><input type="text" name="user_id" value="<?php echo $row_order['user_id']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_id:</td>
      <td><input type="text" name="consignee_id" value="<?php echo $row_order['consignee_id']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Invoice_is_needed:</td>
      <td><input type="text" name="invoice_is_needed" value="<?php echo $row_order['invoice_is_needed']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Invoice_title:</td>
      <td><input type="text" name="invoice_title" value="<?php echo $row_order['invoice_title']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Invoice_message:</td>
      <td><input type="text" name="invoice_message" value="<?php echo $row_order['invoice_message']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Please_delivery_at:</td>
      <td><select name="please_delivery_at">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", $row_order['please_delivery_at']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
       
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_name:</td>
      <td><input type="text" name="consignee_name" value="<?php echo $row_order['consignee_name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_province:</td>
      <td><select name="consignee_province">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1",  $row_order['consignee_province']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
         
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_city:</td>
      <td><select name="consignee_city">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", $row_order['consignee_city']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
         
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_district:</td>
      <td><select name="consignee_district">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", $row_order['consignee_district']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
        
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_address:</td>
      <td><input type="text" name="consignee_address" value="<?php echo $row_order['consignee_address']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_zip:</td>
      <td><input type="text" name="consignee_zip" value="<?php echo $row_order['consignee_zip']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_mobile:</td>
      <td><input type="text" name="consignee_mobile" value="<?php echo $row_order['consignee_mobile']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_order['id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($order);
?>
