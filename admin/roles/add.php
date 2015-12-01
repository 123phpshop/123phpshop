<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="role.html#list";
$support_email_question="添加角色";
$colname_getByName = "-1";
if (isset($_POST['name'])) {
  $colname_getByName = (get_magic_quotes_gpc()) ? $_POST['name'] : addslashes($_POST['name']);
}
mysql_select_db($database_localhost, $localhost);
$query_getByName = sprintf("SELECT * FROM `role` WHERE name = '%s'", $colname_getByName);
$getByName = mysql_query($query_getByName, $localhost) or die(mysql_error());
$row_getByName = mysql_fetch_assoc($getByName);
$totalRows_getByName = mysql_num_rows($getByName);

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

if ($totalRows_getByName < 1 && (isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
  		$pid=isset($_GET['pid'])?$_GET['pid']:"0";
		$insertSQL = sprintf("INSERT INTO role (name,pid ) VALUES (%s,%s)",
						   GetSQLValueString($_POST['name'], "text"),
						   GetSQLValueString($pid, "int"));
	 
  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
  $insertGoTo = "index.php";
  header(sprintf("Location: %s", $insertGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <span class="phpshop123_title">添加角色</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
  <?php if ($totalRows_getByName > 0) { // Show if recordset not empty ?>
    <table  class="error_box" width="100%" border="0">
      <tr>
        <th scope="row">错误：角色名称重复</th>
      </tr>
    </table>
    <?php } // Show if recordset not empty ?><table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">角色名称:</td>
      <td><input type="text" name="name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($getByName);
?>
