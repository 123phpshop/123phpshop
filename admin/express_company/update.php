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
  $updateSQL = sprintf("UPDATE express_company SET code=%s, name=%s, disabled=%s, website=%s WHERE id=%s",
                       GetSQLValueString($_POST['code'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['disabled'], "text"),
                       GetSQLValueString($_POST['website'], "text"),
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

$colname_express_company = "-1";
if (isset($_GET['id'])) {
  $colname_express_company = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_express_company = sprintf("SELECT * FROM express_company WHERE id = %s", $colname_express_company);
$express_company = mysql_query($query_express_company, $localhost) or die(mysql_error());
$row_express_company = mysql_fetch_assoc($express_company);
$totalRows_express_company = mysql_num_rows($express_company);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">快递公司更新</p>
<p>&nbsp; </p>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">名称:</td>
      <td><input type="text" name="name" value="<?php echo $row_express_company['name']; ?>" size="32" />
        *</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">代码:</td>
      <td><input type="text" name="code" value="<?php echo $row_express_company['code']; ?>" size="32">
      *</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">失活:</td>
      <td valign="baseline"><input <?php if (!(strcmp($row_express_company['disabled'],"true"))) {echo "checked=\"checked\"";} ?> type="radio" name="disabled" value="1" <?php if (!(strcmp($row_express_company['disabled'],1))) {echo "CHECKED";} ?> />
是
  <input <?php if (!(strcmp($row_express_company['disabled'],"false"))) {echo "checked=\"checked\"";} ?> type="radio" name="disabled" value="0" <?php if (!(strcmp($row_express_company['disabled'],0))) {echo "CHECKED";} ?> />
否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">网站:</td>
      <td><input type="text" name="website" value="<?php echo $row_express_company['website']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_express_company['id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($express_company);
?>
