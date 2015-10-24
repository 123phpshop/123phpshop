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

mysql_select_db($database_localhost, $localhost);
$query_shipping_method = "SELECT * FROM shipping_method WHERE config_file_path = 'shunfeng'";
$shipping_method = mysql_query($query_shipping_method, $localhost) or die(mysql_error());
$row_shipping_method = mysql_fetch_assoc($shipping_method);
$totalRows_shipping_method = mysql_num_rows($shipping_method);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$insertSQL = sprintf("INSERT INTO shipping_method_area (shipping_method_id, area, name, shipping_by_quantity, first_kg_fee, continue_kg_fee, free_quota, single_product_fee) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_shipping_method['id'], "int"),
                       GetSQLValueString($_POST['area'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['shipping_by_quantity'], "int"),
                       GetSQLValueString($_POST['first_kg_fee'], "double"),
                       GetSQLValueString($_POST['continue_kg_fee'], "double"),
                       GetSQLValueString($_POST['free_quota'], "double"),
                       GetSQLValueString($_POST['single_product_fee'], "double"));

mysql_select_db($database_localhost, $localhost);
$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

$insertGoTo = "/admin/shipping_method_area/index.php?shipping_method_id=".$row_shipping_method['id'];
header(sprintf("Location: %s", $insertGoTo));
   
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">顺丰:新建配送区域</p>
<p>&nbsp; </p>

<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input type="text" name="name" value="" size="32">
*</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">运费计算:</td>
      <td valign="baseline"><input name="shipping_by_quantity" type="radio" value="0" checked="checked" onchange="by_weight()" />
按重量
  <input type="radio" name="shipping_by_quantity" value="1"  onchange="by_quantity()"/>
按数量</td>
    </tr>
    <tr valign="baseline" class="by_weight">
      <td nowrap align="right" >首重费用:</td>
      <td><input type="text" name="first_kg_fee" value="" size="32">
*</td>
    </tr>
    <tr valign="baseline" class="by_weight">
      <td nowrap align="right" >续重费用:</td>
      <td><input type="text" name="continue_kg_fee" value="" size="32">
*</td>
    </tr>
	
	<tr valign="baseline" class="by_quantity">
      <td nowrap align="right">单商品费用:</td>
      <td><input type="text" name="single_product_fee" value="" size="32">
*</td>
    </tr>
	
    <tr valign="baseline">
      <td nowrap align="right">免费额度:</td>
      <td><input type="text" name="free_quota" value="" size="32">
*</td>
    </tr>
   
    <tr valign="baseline">
      <td align="right" valign="top" nowrap>配送区域:</td>
      <td><?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/location_sel.php');?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </table>
   <input type="hidden" name="area" value="">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/shipping_method.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){

	$("#form1").validate({
        rules: {
            name: {
                required: true
            },
            first_kg_fee: {
                required: true,
				number:true
				  
            },
            continue_kg_fee: {
                required: true,
				number:true
				  
            },
			single_product_fee: {
                required: true,
				number:true
				  
            },
            free_quota: {
                 number:true
            }
        },
        messages: {
            name: {
                required: "必填" 
            },
            first_kg_fee: {
                required: "必填" ,
				number:"必须是整数哦"
              },
           continue_kg_fee: {
                required: "必填" ,
				number:"必须是整数哦"
              },
			  single_product_fee: {
                required: "必填" ,
				number:"必须是整数哦"
              },
            free_quota: {
                 number:"必须是整数哦"
            }
        }
    });
	
});</script>
 
</body>
</html>
