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

$colname_order = "-1";
if (isset($_GET['order_id'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['order_id'] : addslashes($_GET['order_id']);
}

mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE id = %s", $colname_order);
$order = mysql_query($query_order, $localhost) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

 
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "order_add_product_form")) {
	
	$should_update_fee=false;

	// 这里需要检查这张订单里面是否有相同的产品了，如果产品相同那么数量直接+1即可
 	$colname_check_product = "-1";
	if (isset($_POST['product_id'])) {
	  $colname_check_product = (get_magic_quotes_gpc()) ? $_POST['product_id'] : addslashes($_POST['product_id']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_check_product = sprintf("SELECT * FROM order_item WHERE product_id = %s", $colname_check_product);
	$check_product = mysql_query($query_check_product, $localhost) or die(mysql_error());
	$row_check_product = mysql_fetch_assoc($check_product);
	$totalRows_check_product = mysql_num_rows($check_product);
	
	
	// 如果这张订单中已经有了相同的产品，而且当前需要添加的产品不是赠品，那么直接将这个订单产品的记录+1		 
 	if($totalRows_check_product>0 && !isset($_POST['is_present']){	
 		$update_quantity_sql="";
		mysql_query($update_quantity_sql, $localhost);
  	}
	
	// 如果这张订单中已经有了相同的产品，而且当前需要添加的产品是赠品，那么直接添加即可，然后进行跳转即可
	if(isset($_POST['is_present']){	
		
   		// 如果是赠品，那么直接添加即可，然后进行跳转
 		$insertSQL = sprintf("INSERT INTO order_item (should_pay_price,order_id,product_id, quantity, attr_value, is_present) VALUES (%s,%s, %s,%s, %s, %s)",
					   GetSQLValueString(0.00, "double"),
                       GetSQLValueString($colname_order, "int"),
					   GetSQLValueString($_POST['product_id'], "int"),
                       GetSQLValueString($_POST['quantity'], "int"),
                       GetSQLValueString($_POST['attr_value'], "text"),
                       GetSQLValueString(isset($_POST['is_present']) ? "true" : "", "defined","1","0"));

		 	// 如果一切都ok，那么进行跳转		
		  $insertGoTo = "detail.php?recordID=" . $colname_order;
		  header(sprintf("Location: %s", $insertGoTo));
 	}
 	
	// 	这里需要检查这个产品是否作为一个赠品赠送的，如果是赠品的话，那么不需要进行计算
		 
	
	// 	如果产品不是赠品的话，那么这里需要获取产品的price
	
	
	//  这里检查这个产品是否在优惠期之内，如果在优惠期之内，那么使用优惠价格
		 
	
 	// 	然后插入到订单产品表格中
 $insertSQL = sprintf("INSERT INTO order_item (order_id,product_id, quantity, attr_value, is_present) VALUES (%s, %s,%s, %s, %s)",
                       GetSQLValueString($colname_order, "int"),
					   GetSQLValueString($_POST['product_id'], "int"),
                       GetSQLValueString($_POST['quantity'], "int"),
                       GetSQLValueString($_POST['attr_value'], "text"),
                       GetSQLValueString(isset($_POST['is_present']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
  
  // 	更新订单的产品总价，运费总价和订单总额
	$products_total_fee="";
	$shipping_fee="";
	$order_total="";
		
		
	// 如果一切都ok，那么进行跳转		
  $insertGoTo = "detail.php?recordID=" . $colname_order;
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
<p class="phpshop123_title">添加订单商品</p>
<form action="<?php echo $editFormAction; ?>" method="POST" name="order_add_product_form" id="order_add_product_form">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">产品:</td>
      <td><input name="product_name" type="text" id="product_name" onChange="show_products()" value="" size="32"></td>
    </tr>
    <tr valign="baseline" id="goods_select_tr">
      <td nowrap align="right">选择商品</td>
      <td id="goods_select_td">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">数量:</td>
      <td><input type="text" name="quantity" value="1" size="32">
        *</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Attr_value:</td>
      <td><input type="text" name="attr_value" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_present:</td>
      <td><input type="checkbox" name="is_present" value="" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </table>
   <input type="hidden" name="MM_insert" value="order_add_product_form">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($check_product);
?>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script>
function show_products(){
	var product_name=$("#product_name").val();
	var url="/admin/widgets/order/_goods_filter.php?product_name="+product_name;
 	if(product_name.trim()==""){
		return;
	}
	$("#goods_select_td").load(url);
}
</script>
