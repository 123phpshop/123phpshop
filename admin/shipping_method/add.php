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
  $insertSQL = sprintf("INSERT INTO shipping_method (name, `desc`, is_activated, is_cod, is_fixed_fee, fixed_fee, is_free, jiangzhehu_first_kg_fee, jiangzhehu_continue_kg_fee, other_city_first_kg_fee, other_city_continue_kg_fee) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['desc'], "text"),
                       GetSQLValueString(isset($_POST['is_activated']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['is_cod']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['is_fixed_fee']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['fixed_fee'], "double"),
                       GetSQLValueString(isset($_POST['is_free']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['jiangzhehu_first_kg_fee'], "double"),
                       GetSQLValueString($_POST['jiangzhehu_continue_kg_fee'], "double"),
                       GetSQLValueString($_POST['other_city_first_kg_fee'], "double"),
                       GetSQLValueString($_POST['other_city_continue_kg_fee'], "double"));

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
      <td nowrap align="right">Is_cod:</td>
      <td><input type="checkbox" name="is_cod" value="" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_fixed_fee:</td>
      <td><input type="checkbox" name="is_fixed_fee" value="" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Fixed_fee:</td>
      <td><input type="text" name="fixed_fee" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_free:</td>
      <td><input type="checkbox" name="is_free" value="" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Jiangzhehu_first_kg_fee:</td>
      <td><input type="text" name="jiangzhehu_first_kg_fee" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Jiangzhehu_continue_kg_fee:</td>
      <td><input type="text" name="jiangzhehu_continue_kg_fee" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Other_city_first_kg_fee:</td>
      <td><input type="text" name="other_city_first_kg_fee" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Other_city_continue_kg_fee:</td>
      <td><input type="text" name="other_city_continue_kg_fee" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Desc:</td>
      <td><textarea name="desc" cols="50" rows="5"></textarea>
      </td>
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
