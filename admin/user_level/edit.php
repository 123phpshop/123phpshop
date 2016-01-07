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
  $updateSQL = sprintf("UPDATE user_levels SET name=%s, min_consumption_amount=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['min_consumption_amount'], "double"),
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

$colname_item = "-1";
if (isset($_GET['id'])) {
  $colname_item = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_item = sprintf("SELECT * FROM user_levels WHERE id = %s", $colname_item);
$item = mysql_query($query_item, $localhost) or die(mysql_error());
$row_item = mysql_fetch_assoc($item);
$totalRows_item = mysql_num_rows($item);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">用户等级更新</p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
        <tr valign="baseline">
          <td width="11%" align="right" nowrap>等级名称:</td>
          <td width="89%"><input type="text" name="name" value="<?php echo $row_item['name']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">最低消费金额:</td>
          <td><input type="text" name="min_consumption_amount" value="<?php echo $row_item['min_consumption_amount']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="更新记录"></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="id" value="<?php echo $row_item['id']; ?>">
    </form>
    <p>&nbsp;</p>
    <p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($item);
?>
