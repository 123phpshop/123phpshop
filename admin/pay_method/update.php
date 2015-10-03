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
  $updateSQL = sprintf("UPDATE pay_method SET name=%s, folder=%s, is_activated=%s, www=%s, logo=%s, intro=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['folder'], "text"),
                       GetSQLValueString($_POST['is_activated'], "int"),
                       GetSQLValueString($_POST['www'], "text"),
                       GetSQLValueString($_POST['logo'], "text"),
                       GetSQLValueString($_POST['intro'], "text"),
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

$colname_pay_method = "-1";
if (isset($_GET['id'])) {
  $colname_pay_method = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_pay_method = sprintf("SELECT * FROM pay_method WHERE id = %s", $colname_pay_method);
$pay_method = mysql_query($query_pay_method, $localhost) or die(mysql_error());
$row_pay_method = mysql_fetch_assoc($pay_method);
$totalRows_pay_method = mysql_num_rows($pay_method);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title"><?php echo $row_pay_method['name']; ?> - 编辑</p>
<p>&nbsp;</p>

<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="<?php echo $row_pay_method['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Folder:</td>
      <td><input type="text" name="folder" value="<?php echo $row_pay_method['folder']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_activated:</td>
      <td valign="baseline"><table>
        <tr>
          <td><input type="radio" name="is_activated" value="1" <?php if (!(strcmp($row_pay_method['is_activated'],"1"))) {echo "CHECKED";} ?>>
            是
              <input type="radio" name="is_activated" value="0" <?php if (!(strcmp($row_pay_method['is_activated'],"0"))) {echo "CHECKED";} ?> />
 否 </td>
        </tr>
      </table></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Www:</td>
      <td><input type="text" name="www" value="<?php echo $row_pay_method['www']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Logo:</td>
      <td><input type="file" name="logo" value="<?php echo $row_pay_method['logo']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Intro:</td>
      <td><textarea name="intro" cols="50" rows="5"><?php echo $row_pay_method['intro']; ?></textarea>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_pay_method['id']; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($pay_method);
?>
