<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="role.html#list";
$support_email_question="更新角色信息";
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
   	echo $updateSQL = sprintf("UPDATE role SET name=%s WHERE id=%s",
				   GetSQLValueString($_POST['name'], "text"),
 				   GetSQLValueString($_POST['id'], "int"));
	 
  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_getById = "-1";
if (isset($_GET['id'])) {
  $colname_getById = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_getById = sprintf("SELECT * FROM `role` WHERE id = %s", $colname_getById);
$getById = mysql_query($query_getById, $localhost) or die(mysql_error());
$row_getById = mysql_fetch_assoc($getById);
$totalRows_getById = mysql_num_rows($getById);

$colname_getByName = "-1";
if (isset($_POST['name'])) {
  $colname_getByName = (get_magic_quotes_gpc()) ? $_POST['name'] : addslashes($_POST['name']);
}
mysql_select_db($database_localhost, $localhost);
$query_getByName = sprintf("SELECT * FROM `role` WHERE name = '%s'", $colname_getByName);
$getByName = mysql_query($query_getByName, $localhost) or die(mysql_error());
$row_getByName = mysql_fetch_assoc($getByName);
$totalRows_getByName = mysql_num_rows($getByName);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">更新角色</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<?php if ($totalRows_getById == 0) { // Show if recordset empty ?>
  <table  class="error_box" width="100%" border="0">
    <tr>
      <th class="phpshop123_infobox" scope="row">错误：角色不存在</th>
    </tr>
  </table>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_getByName > 0) { // Show if recordset not empty ?>
  <table  class="error_box" width="100%" border="0">
    <tr>
      <th class="phpshop123_infobox" scope="row">错误：角色名重复</th>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_getById > 0) { // Show if recordset not empty ?>
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table align="center" class="phpshop123_form_box">
      <tr valign="baseline">
        <td nowrap align="right">角色名称:</td>
        <td><input type="text" name="name" value="<?php echo $row_getById['name']; ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">&nbsp;</td>
        <td><input type="submit" value="更新记录"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id" value="<?php echo $row_getById['id']; ?>">
  </form>
  <?php } // Show if recordset not empty ?><p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($getById);

mysql_free_result($getByName);
?>
