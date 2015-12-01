<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once('../../Connections/lib/order.php'); ?>

<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
	$theValue = (! get_magic_quotes_gpc ()) ? addslashes ( $theValue ) : $theValue;
	
	switch ($theType) {
		case "text" :
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
		case "long" :
		case "int" :
			$theValue = ($theValue != "") ? intval ( $theValue ) : "NULL";
			break;
		case "double" :
			$theValue = ($theValue != "") ? "'" . doubleval ( $theValue ) . "'" : "NULL";
			break;
		case "date" :
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
		case "defined" :
			$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			break;
	}
	return $theValue;
}
$error="";

$colname_order = "-1";
if (isset ( $_GET ['order_id'] )) {
	$colname_order = (get_magic_quotes_gpc ()) ? $_GET ['order_id'] : addslashes ( $_GET ['order_id'] );
}
 
mysql_select_db ( $database_localhost, $localhost );
$query_order = sprintf ( "SELECT * FROM orders WHERE id = %s", $colname_order );
$order = mysql_query ( $query_order, $localhost ) or die ( mysql_error () );
$row_order = mysql_fetch_assoc ( $order );
$totalRows_order = mysql_num_rows ( $order );

$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ( $_SERVER ['QUERY_STRING'] )) {
	$editFormAction .= "?" . htmlentities ( $_SERVER ['QUERY_STRING'] );
}


if ((isset ( $_POST ["MM_insert"] )) && ($_POST ["MM_insert"] == "order_add_product_form")) {
 	
	try{
 		$order_array=get_order_full_by_id($colname_order);
		$product_item=$_POST;
  		phpshop123_order_add_product($order_array,$product_item );
	}catch(Exception $ex){
		$error=$ex->getMessage();
 	}
 	if($error==''){
		// 如果一切都ok，那么进行跳转
		$insertGoTo = "detail.php?recordID=" . $colname_order;
		header ( sprintf ( "Location: %s", $insertGoTo ) );
	}
	
}


$doc_url="order.html#add_product";
$support_email_question="添加订单商品";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<span class="phpshop123_title">添加订单商品</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<?php if($error!=''){?>
	<p 	class="phpshop123_infobox"><?php echo $error;?></p>
<?php }?>
	<form action="<?php echo $editFormAction; ?>" method="POST"
		name="order_add_product_form" id="order_add_product_form">
		<table align="center" class="phpshop123_form_box">
			<tr valign="baseline">
				<td nowrap align="right">商品:</td>
				<td><input name="product_name" type="text" id="product_name"
					oninput="show_products()" value="" size="32"></td>
			</tr>
			<tr valign="baseline" id="goods_select_tr">
				<td nowrap align="right">选择商品</td>
				<td id="goods_select_td">&nbsp;</td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">数量:</td>
				<td><input type="text" name="quantity" value="1" size="32"> *</td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">设置属性:</td>
				<td id="attr_valu_td"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">是否为赠送礼物:</td>
				<td><input type="checkbox" name="is_present" value=""></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">&nbsp;</td>
				<td><input type="submit" value="添加"></td>
			</tr>
		</table>
		<input type="hidden" name="attr_value" id="attr_value" value=""> <input
			type="hidden" name="MM_insert" value="order_add_product_form">
 	</form>
	<p>&nbsp;</p>
</body>
</html>
<script language="JavaScript" type="text/javascript"
	src="/js/jquery-1.7.2.min.js"></script>
<script>
function show_products(){
	var product_name=$("#product_name").val();
	var url="/admin/widgets/order/_goods_filter.php?product_name="+product_name;
 	if(product_name.trim()==""){
		return;
	}
	$("#goods_select_td").load(url);
}

function load_attr(product_id){
	var url="/admin/widgets/order/_attr_filter.php?product_id="+product_id;
	$("#attr_valu_td").load(url);	
}

function set_attr_val(that){
 	var attr_name=$(that).attr("attr_name");	
	var attr_val=$(that).val().trim();
 	$("#attr_value").val(attr_name+":"+attr_val);
}


</script>
