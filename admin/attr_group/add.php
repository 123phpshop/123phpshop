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
  $insertSQL = sprintf("INSERT INTO product_type_attr (name, is_selectable, input_method, selectable_value, product_type_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['is_selectable'], "int"),
                       GetSQLValueString($_POST['input_method'], "int"),
                       GetSQLValueString($_POST['selectable_value'], "text"),
                       GetSQLValueString($_POST['product_type_id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
  
   $insertGoTo = "index.php?product_type_id=".$_POST['product_type_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  
  
}

$colname_product_type = "-1";
if (isset($_GET['product_type_id'])) {
  $colname_product_type = (get_magic_quotes_gpc()) ? $_GET['product_type_id'] : addslashes($_GET['product_type_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_product_type = sprintf("SELECT * FROM product_type WHERE id = %s", $colname_product_type);
$product_type = mysql_query($query_product_type, $localhost) or die(mysql_error());
$row_product_type = mysql_fetch_assoc($product_type);
$totalRows_product_type = mysql_num_rows($product_type);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <p class="phpshop123_title"><?php echo $row_product_type['name']; ?>：添加属性  </p>
  <table width="100%" align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_selectable:</td>
      <td valign="baseline"><input name="is_selectable" type="radio" value="1" checked="checked" />
只是显示
  <input type="radio" name="is_selectable" value="2" />
可单选</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Input_method:</td>
      <td valign="baseline"><input name="input_method" type="radio" value="1" checked="checked" />
手动录
  <input type="radio" name="input_method" value="2" />
从以下列表中选</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Selectable_value:</td>
      <td><textarea name="selectable_value" cols="50" rows="5"></textarea>
	  <input type="hidden" name="product_type_id" value="<?php echo $_GET['product_type_id']; ?>" /></td>
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
<?php
mysql_free_result($product_type);
?>
