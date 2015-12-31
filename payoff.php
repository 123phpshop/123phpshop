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
<?php

// 这里对字段进行验证,如果字段不合法，那么直接跳转到主页
$_POST=$_GET;
$validation->set_rules('order_sn', '', 'required|is_natural_no_zero');
if (!$validation->run())
{
	$MM_redirectLoginFailed = "/index.php";
	header("Location: ". $MM_redirectLoginFailed );return;
}

// 通过s3来获取订单， 。通过订单的序列号来获取订单的详细情况。
$colname_order = "-1";
if (isset($_GET['order_sn'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['order_sn'] : addslashes($_GET['order_sn']);
}
mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE sn = '%s' and is_delete=0 ", $colname_order);
$order = mysql_query($query_order, $localhost) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

//	如果没有办法找到这个订单的话,那么告知
if($totalRows_order==0){
		$url="/";
	 header("Location: " . $url );
}
//	如然后检查订单的消息状态，如果说这个订单已经被支付的话，那么需要跳转
if($row_order['order_status']!=0){
	$url="/";
	 header("Location: " . $url );
}

mysql_select_db($database_localhost, $localhost);
$query_pay_method = "SELECT * FROM pay_method WHERE is_activated = 1";
$pay_method = mysql_query($query_pay_method, $localhost) or die(mysql_error());
$row_pay_method = mysql_fetch_assoc($pay_method);
$totalRows_pay_method = mysql_num_rows($pay_method);
$consignee_id=0;
include($template_path."payoff.php");
?>