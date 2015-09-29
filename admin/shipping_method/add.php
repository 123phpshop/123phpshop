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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO shipping_method (name, `desc`, config_file_path, is_activated, is_cod, is_free) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['desc'], "text"),
                       GetSQLValueString($_POST['config_file_path'], "text"),
                       GetSQLValueString(isset($_POST['is_activated']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['is_cod']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['is_free']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <p>添加配送方式</p>
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_activated:</td>
      <td><input name="is_activated" type="checkbox" value="" checked="checked" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">配送区域配置文件位置:</td>
      <td><input name="config_file_path" type="text" id="config_file_path" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">是否为货到付款:</td>
      <td><input type="checkbox" name="is_cod" value="" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_free:</td>
      <td><input type="checkbox" name="is_free" value="" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Desc:</td>
      <td><textarea name="desc" cols="50" rows="5"></textarea>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="插入记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
