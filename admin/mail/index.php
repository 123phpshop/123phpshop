<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="ad.html#list";
$support_email_question="广告列表";
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
  $updateSQL = sprintf("UPDATE shop_info SET smtp_email=%s, smtp_server=%s, smtp_port=%s, smtp_username=%s, smtp_password=%s WHERE id=%s",
                       GetSQLValueString($_POST['smtp_email'], "text"),
                       GetSQLValueString($_POST['smtp_server'], "text"),
                       GetSQLValueString($_POST['smtp_port'], "int"),
                       GetSQLValueString($_POST['smtp_username'], "text"),
                       GetSQLValueString($_POST['smtp_password'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

mysql_select_db($database_localhost, $localhost);
$query_smtp_info = "SELECT * FROM shop_info WHERE id = 1";
$smtp_info = mysql_query($query_smtp_info, $localhost) or die(mysql_error());
$row_smtp_info = mysql_fetch_assoc($smtp_info);
$totalRows_smtp_info = mysql_num_rows($smtp_info);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">Smtp_email:</td>
      <td><input type="text" name="smtp_email" value="<?php echo $row_smtp_info['smtp_email']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp_server:</td>
      <td><input type="text" name="smtp_server" value="<?php echo $row_smtp_info['smtp_server']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp_port:</td>
      <td><input type="text" name="smtp_port" value="<?php echo $row_smtp_info['smtp_port']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp_username:</td>
      <td><input type="text" name="smtp_username" value="<?php echo $row_smtp_info['smtp_username']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Smtp_password:</td>
      <td><input type="text" name="smtp_password" value="<?php echo $row_smtp_info['smtp_password']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_smtp_info['id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($smtp_info);
?>
