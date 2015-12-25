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
 ?><?php require_once('Connections/localhost.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Connections/lib/product.php");?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Connections/lib/order.php");?>

<?php
if(!isset($_SESSION['user_id'])){
	 $url="/login.php";
  	header("Location: " .  $url );
}
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


$cart_obj = new Cart ();


$cart = $cart_obj->get ();

$cart_obj->update_fee(); //  这里可以防止用户在购物车和订单确认之间进行跳转的时候管理员更新了货物的价格所可能造成的价格错误
 $cart_products = $cart ['products'];


 //	检查购物车是否有产品，如果没有产品的话，那么直接跳转到购物车页面
if(count($cart_products)==0){
	 $url="/cart.php";
  	header("Location: " .  $url );
}




$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
    $insertSQL = sprintf("INSERT INTO user_consignee (user_id,name, mobile, province, city, district, address, zip) VALUES (%s,%s, %s, %s, %s, %s, %s, %s)",
  					   GetSQLValueString($_SESSION['user_id'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['mobile'], "text"),
                       GetSQLValueString($_POST['province'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['district'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['zip'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

// 获取这个用户的所有的收货人信息
$colname_consignee = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_consignee = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_consignee = sprintf("SELECT * FROM user_consignee WHERE user_id = %s and is_delete=0 order by is_default desc", $colname_consignee);
$consignee_obj = mysql_query($query_consignee, $localhost) or die(mysql_error());
$row_consignee = mysql_fetch_assoc($consignee_obj);
$totalRows_consignee = mysql_num_rows($consignee_obj);
 
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "order_form")) {
	
//		检查session中是否有收货人信息，如果没有设置的话，那么设置收货人信息
	
	//	如果只有1个收货人信息的话，那么默认就是这个
 	//	插入订单信息，().
	 
	require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/order.php');
	$sn=gen_order_sn();
	$should_paid=$_SESSION['cart']['order_total'];
	$actual_paid="0.00";
    $insertSQL = sprintf("INSERT INTO orders (products_total,shipping_fee,sn, user_id, should_paid, actual_paid, shipping_method, payment_method, invoice_is_needed, invoice_title, invoice_message,please_delivery_at,consignee_id,consignee_name,consignee_province,consignee_city,consignee_district,consignee_address,consignee_zip,consignee_mobile,promotion_id,promotion_fee) VALUES (%s,%s,%s,%s,%s,%s,%s, %s, %s, %s, %s, %s, %s,  %s, %s, %s, %s, %s, %s,%s, %s, %s)",
					   GetSQLValueString($_SESSION['cart']['products_total'], "double"),
 					   GetSQLValueString($_SESSION['cart']['shipping_fee'], "double"),
					   GetSQLValueString($sn, "text"),
                       GetSQLValueString($_SESSION['user_id'], "text"),
                       GetSQLValueString($should_paid, "text"),
                       GetSQLValueString($actual_paid, "text"),
                       GetSQLValueString($_SESSION['cart']['shipping_method_id'], "int"),
                       GetSQLValueString($_POST['payment_method'], "int"),
                       GetSQLValueString(isset($_POST['invoice_is_needed'])?1:0, "int"),
					   GetSQLValueString($_POST['invoice_title'], "text"),
					   GetSQLValueString($_POST['invoice_message'], "text"),
					   GetSQLValueString($_POST['please_delivery_at'], "int"),
                       GetSQLValueString($_POST['consignee_id'], "int"),
					   GetSQLValueString($_SESSION['consignee']['name'], "text"),
					   GetSQLValueString($_SESSION['consignee']['province'], "text"),
					   GetSQLValueString($_SESSION['consignee']['city'], "text"),
					   GetSQLValueString($_SESSION['consignee']['district'], "text"),
					   GetSQLValueString($_SESSION['consignee']['address'], "text"),
					   GetSQLValueString($_SESSION['consignee']['zip'], "text"),
					   GetSQLValueString($_SESSION['consignee']['mobile'], "text"),
					   GetSQLValueString($_SESSION['cart']['promotion_id'], "text"),
					   GetSQLValueString($_SESSION['cart']['promotion_fee'], "double")					   );
  
	$Result1 = mysql_query($insertSQL) or die(mysql_error());
	$order_id=mysql_insert_id();
  
  //	检查参数，如果参数不正确的话，能否告知？
  
  //	如果参数正确的话，那么进行数据插入
  foreach($cart_products as $product){
  	$sql="insert into order_item(attr_value,product_id,quantity,should_pay_price,actual_pay_price,order_id)values('".$product['attr_value']."','".$product['product_id']."','".$product['quantity']."','".$product['product_price']."','".$product['product_price']."','".$order_id."')";
  	mysql_query($sql);
  }
  
	//	如果插入成功，那么清空购物有车
	$cart_obj->clear();
	
	// 删除缓存中的收货人数据
	unset($_SESSION['consignee']);
	  
	//	如果插入失败，那么告知；
	
	//	记录进入日志操作
	  
		phpshop123_log_order_new($new_order_id);

	
	// 发送邮件通知
	try{
		$email_template_code=200;
		require_once($_SERVER['DOCUMENT_ROOT']."/Connections/lib/send_email.php");
	}catch(Exception $ex){
		// 如果发送失败，这里需要记录进入日志
		phpshop_log("通知邮件发送错误：200 订单号码是:".$new_order_id);
  	}
	//	  如果成功，那么跳转到付款页面
	$MM_redirectLoginSuccess="payoff.php?order_sn=".$sn;
	header("Location: " . $MM_redirectLoginSuccess );
}
include($template_path."confirm.php");

?>