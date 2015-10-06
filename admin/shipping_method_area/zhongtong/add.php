<?php require_once('../../../Connections/localhost.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO shipping_method_area (name, shipping_method_id, area, shipping_by_quantity, first_kg_fee, continue_kg_fee, free_quota, single_product_fee) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['shipping_method_id'], "int"),
                       GetSQLValueString($_POST['area'], "text"),
                       GetSQLValueString($_POST['shipping_by_quantity'], "int"),
                       GetSQLValueString($_POST['first_kg_fee'], "double"),
                       GetSQLValueString($_POST['continue_kg_fee'], "double"),
                       GetSQLValueString($_POST['free_quota'], "double"),
                       GetSQLValueString($_POST['single_product_fee'], "double"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "../index.php";
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
<p>中通配送区域配置</p>
<p>&nbsp; </p>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="" size="32"><input type="hidden" name="area" > </td>
    </tr>
      
     <tr valign="baseline">
      <td nowrap align="right">Shipping_by_quantity:</td>
      <td valign="baseline"><table width="100%">
        <tr>
          <td><input name="shipping_by_quantity" type="radio" value="按重量" checked="checked" onchange="by_weight()" >
            按重量
              <input type="radio" name="shipping_by_quantity" value="按数量" onchange="by_quantity()"/>
按数量</td>
        </tr>
      </table></td>
    </tr>
    <tr valign="baseline" class="by_weight">
      <td nowrap align="right">First_kg_fee:</td>
      <td><input type="text" name="first_kg_fee" value="" size="32"></td>
    </tr>
    <tr valign="baseline" class="by_weight">
      <td nowrap align="right">Continue_kg_fee:</td>
      <td><input type="text" name="continue_kg_fee" value="" size="32"></td>
    </tr>
   
    <tr valign="baseline" class="by_quantity">
      <td nowrap align="right">Single_product_fee:</td>
      <td><input type="text" name="single_product_fee" value="" size="32"></td>
    </tr>
	 <tr valign="baseline">
      <td nowrap align="right">Free_quota:</td>
      <td><input type="text" name="free_quota" value="" size="32"></td>
    </tr>
	
    <tr valign="baseline">
      <td nowrap align="right">区域设置</td>
      <td><?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/location_sel.php');?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="插入记录"></td>
    </tr>
  </table>
  <input type="hidden" name="shipping_method_id" value="">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/shipping_method.js"></script>
</body>
</html>
