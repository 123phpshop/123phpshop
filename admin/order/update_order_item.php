<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
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
$colname_order = "-1";
if (isset($_GET['order_id'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['order_id'] : addslashes($_GET['order_id']);
}

mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE id = %s", $colname_order);
$order = mysql_query($query_order, $localhost) ;
if(!$order){$logger->fatal("数据库操作失败:".$query_order);}
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

 
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_order_item = "-1";
if (isset($_GET['id'])) {
  $colname_order_item = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_order_item = sprintf("SELECT * FROM order_item WHERE is_delete=0 and  id = %s", $colname_order_item);
$order_item = mysql_query($query_order_item, $localhost) ;
if(!$order_item){$logger->fatal("数据库操作失败:".$query_order_item);}
$row_order_item = mysql_fetch_assoc($order_item);
$totalRows_order_item = mysql_num_rows($order_item);

mysql_select_db($database_localhost, $localhost);
$query_product = "SELECT * FROM product WHERE is_delete=0 and  id = ".$row_order_item['product_id'];
$product = mysql_query($query_product, $localhost) ;
if(!$product){$logger->fatal("数据库操作失败:".$query_product);}
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "order_add_product_form")) {
	
	$should_update_fee=false;

	// 这里需要检查这张订单里面是否有相同的产品了，如果产品相同那么数量直接+1即可
 	$colname_check_product = "-1";
	if (isset($_POST['product_id'])) {
	  $colname_check_product = (get_magic_quotes_gpc()) ? $_POST['product_id'] : addslashes($_POST['product_id']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_check_product = sprintf("SELECT * FROM order_item WHERE product_id = %s", $colname_check_product);
	$check_product = mysql_query($query_check_product, $localhost) ;
	if(!$check_product){$logger->fatal("数据库操作失败:".$query_check_product);}
	$row_check_product = mysql_fetch_assoc($check_product);
	$totalRows_check_product = mysql_num_rows($check_product);



	
 	// 如果这张订单中么有这个产品，而且这个产品是赠品的话，那么直接插入即可,也不需要更新任何费用信息
	if($totalRows_check_product==0 && isset($_POST['is_present'])){	
	
  		$insertSQL = sprintf("INSERT INTO order_item (should_pay_price,order_id,product_id, quantity, attr_value, is_present) VALUES (%s,%s, %s,%s, %s, %s)",
					   GetSQLValueString(0.00, "double"),
                       GetSQLValueString($colname_order, "int"),
					   GetSQLValueString($_POST['product_id'], "int"),
                       GetSQLValueString($_POST['quantity'], "int"),
                       GetSQLValueString($_POST['attr_value'], "text"),
                       GetSQLValueString(isset($_POST['is_present']) ? "true" : "", "defined","1","0"));
		$check_product = mysql_query($insertSQL, $localhost) ;
		if(!$check_product){$logger->fatal("数据库操作失败:".$insertSQL);}
		 	// 如果一切都ok，那么进行跳转		
		  $insertGoTo = "detail.php?recordID=" . $colname_order;
		  header(sprintf("Location: %s", $insertGoTo));
 	}
	
	//	 如果订单中没有这个产品，而且用户想要添加的产品页不属于的话，那么直接插入，但是需要更新产品的费用信息
	if($totalRows_check_product==0 && !isset($_POST['is_present'])){	
		
		$insertSQL = sprintf("INSERT INTO order_item (should_pay_price,order_id,product_id, quantity, attr_value, is_present) VALUES (%s,%s, %s,%s, %s, %s)",
					   GetSQLValueString(0.00, "double"),
                       GetSQLValueString($colname_order, "int"),
					   GetSQLValueString($_POST['product_id'], "int"),
                       GetSQLValueString($_POST['quantity'], "int"),
                       GetSQLValueString($_POST['attr_value'], "text"),
                       GetSQLValueString(isset($_POST['is_present']) ? "true" : "", "defined","1","0"));
		
		$check_product = mysql_query($insertSQL, $localhost) ;
		if(!$check_product){$logger->fatal("数据库操作失败:".$insertSQL);}
		 	// 如果一切都ok，那么进行跳转		
		  $insertGoTo = "detail.php?recordID=" . $colname_order;
		  header(sprintf("Location: %s", $insertGoTo));
		  
		  update_order_fee($colname_order);
	}
	
	
	
	// 如果这张订单中已经有了相同的产品，而且当前需要添加的产品不是赠品	 
 	if($totalRows_check_product>0 && !isset($_POST['is_present'])){	
	
		// 这里需要检查前面的那个产品是否是赠品
		
		
		// 如果前面那个商品是赠品，那么直接插入，然后更新价格信息
		
		// 如果前面那个商品不属于赠品，那么检查是否需要享受优惠
		
		// 如果需要享受优惠，那么获取优惠价格，然后更新订单费用信息
			
		// 如果不需要享受优惠，那么直接更新订单费用即可
		
   		// 如果是赠品，那么直接添加即可，然后进行跳转		
 		
		// 这里需要获取已有产品的产品的价格
		$price=$row_check_product['should_pay_price'];
		$quantity=$row_check_product['quantity'];
		
		// 如果是优惠价怎么办？
		
 		$update_quantity_sql="";
		$check_product =mysql_query($update_quantity_sql, $localhost);
 		if(!$check_product){$logger->fatal("数据库操作失败:".$update_quantity_sql);}
  	}
	
	// 如果这张订单中已经有了相同的产品，而且当前需要添加的产品是赠品，那么直接添加即可，然后进行跳转即可
	if($totalRows_check_product>0 && isset($_POST['is_present'])){	
		
		// 这里需要检查前面的那个产品是否是赠品，
		
		// 如果前面那个商品也是赠品，那么直接将其数量+1即可，不用更新产品费用信息
 		
		// 如果前面的那个产品不属于赠品，那么直接插入即可，不用更新费用信息
 		
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
  $Result1 = mysql_query($insertSQL, $localhost) ;if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
  
  	// 	更新订单的产品总价，运费总价和订单总额
	$products_total_fee="";
	$shipping_fee="";
	$order_total="";
		
		
	// 如果一切都ok，那么进行跳转		
  	$insertGoTo = "detail.php?recordID=" . $colname_order;
  	header(sprintf("Location: %s", $insertGoTo));
}

$doc_url="order.html#update_product";
$support_email_question="更新订单产品";
log_admin($support_email_question);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">更新订单商品<a href="index.php">
  <input style="float:right;" type="submit" name="Submit2" value="订单列表" />
</a></p>
<form action="<?php echo $editFormAction; ?>" method="POST" name="order_update_product_form" id="order_update_product_form">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">产品:</td>
      <td><input name="product_name" value="<?php echo $row_product['name'];?>" type="text" id="product_name" oninput="show_products()" size="32"></td>
    </tr>
    <tr valign="baseline" id="goods_select_tr">
      <td nowrap align="right">选择商品</td>
      <td id="goods_select_td"><?php
	  	$product_id=$row_product['id'];
		include_once($_SERVER['DOCUMENT_ROOT']."/admin/widgets/order/_goods_load.php");
	  
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">数量:</td>
      <td><input type="text" name="quantity" value="<?php echo $row_order_item['quantity']; ?>" size="32">
        *</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Attr_value:</td>
      <td id="attr_valu_td"><input type="text" name="attr_value" value="<?php echo $row_order_item['attr_value']; ?>" size="32">
	  <?php
	  	$product_id=$row_product['id'];
		include_once($_SERVER['DOCUMENT_ROOT']."/admin/widgets/order/_attr_load.php");
 	  ?>
	  </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Is_present:</td>
      <td><input type="checkbox" name="is_present" value="<?php echo $row_order_item['is_present']; ?>" <?php if($row_order_item['is_present']==1){ ?>checked<?php } ?>></td>
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
mysql_free_result($order_item);

mysql_free_result($product);
?>
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
	var url="/admin/widgets/order/_load_attr.php?product_id="+product_id;
	$("#attr_valu_td").load(url);	
}


</script>