<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php require_once('../../Connections/localhost.php'); ?>
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
	<span class="phpshop123_title">添加订单商品</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<?php if($error!=''){?>
	<a href="index.php">
	<input style="float:right;" type="submit" name="Submit2" value="订单详细页面" />
	</a>
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
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#new_consignee_form").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
             },
            mobile: {
                required: true,
                minlength: 11,
				digits:true   
            },
            address: {
                required: true,
                minlength: 3   
            },
 			zip: {
                required: true,
                minlength: 6,
				digits:true
            }
        } 
    });
});</script>

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