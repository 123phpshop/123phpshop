<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once('../../Connections/localhost.php'); 
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
  $updateSQL = sprintf("UPDATE shipping_method SET name=%s, config_file_path=%s, is_activated=%s, is_cod=%s, is_free=%s, `desc`=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['config_file_path'], "text"),
                       GetSQLValueString(isset($_POST['is_activated']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['is_cod']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['is_free']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['desc'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

 
$colname_shipping_method = "-1";
if (isset($_GET['id'])) {
  $colname_shipping_method = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_shipping_method = sprintf("SELECT id, name, `desc`, config_file_path, is_activated, is_cod, is_free FROM shipping_method WHERE id = %s", $colname_shipping_method);
$shipping_method = mysql_query($query_shipping_method, $localhost) or die(mysql_error());
$row_shipping_method = mysql_fetch_assoc($shipping_method);
$totalRows_shipping_method = mysql_num_rows($shipping_method);
?>
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<p><?php echo $row_shipping_method['name']; ?>：编辑</p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="<?php echo $row_shipping_method['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Config_file_path:</td>
      <td><input type="text" name="config_file_path" value="<?php echo $row_shipping_method['config_file_path']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_activated:</td>
      <td><input type="checkbox" name="is_activated" value=""  <?php if (!(strcmp($row_shipping_method['is_activated'],""))) {echo "@@checked@@";} ?>></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_cod:</td>
      <td><input type="checkbox" name="is_cod" value=""  <?php if (!(strcmp($row_shipping_method['is_cod'],""))) {echo "@@checked@@";} ?>></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_free:</td>
      <td><input type="checkbox" name="is_free" value=""  <?php if (!(strcmp($row_shipping_method['is_free'],""))) {echo "@@checked@@";} ?>></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Desc:</td>
      <td><textarea name="desc" cols="50" rows="5"><?php echo $row_shipping_method['desc']; ?></textarea>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_shipping_method['id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($shipping_method);
?>
