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
?><?php
require_once ('../../Connections/localhost.php');
?>
<?php

$could_return = 1;
try {
	
	$_POST = $_GET;
	// 这里对字段进行验证
	$validation->set_rules ( 'id', '', 'required|is_natural_no_zero' );
	if (! $validation->run ()) {
		throw new Exception ( "参数错误！" );
	}
	
	$colname_order = "-1";
	if (isset ( $_GET ['id'] )) {
		$colname_order = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
	}
	
	$query_order = sprintf ( "SELECT * FROM orders WHERE id = %s and user_id=%s and is_delete=0 ", $colname_order, $_SESSION ['user_id'] );
	$order = mysqli_query($localhost,$query_order);
	if (! $order) {
		$logger->fatal ( "用户在退货时订单查询失败:" . $query_order );
		throw new Exception ( "订单不存在！" );
	}
	$row_order = mysqli_fetch_assoc ( $order );
	$totalRows_order = mysqli_num_rows ( $order );
	
	if ($totalRows_order == 0) {
		$logger->fatal ( "用户在退货时订单不存在:" . $colname_order );
		throw new Exception ( "订单不存在！" );
	}
	
	if (! could_return ( $row_order ['order_status'] )) {
		$logger->fatal ( "用户在退货时由于订单状态无法退货:" . $colname_order );
		throw new Exception ( "用户在退货时由于订单状态无法退货！" );
	}
	
	$update_catalog = sprintf ( "update `orders` set order_status='" . ORDER_STATUS_RETURNED_APPLIED . "' where id = %s", $colname_order );
	$update_catalog_query = mysqli_query($localhost,$update_catalog);
	if (! $update_catalog_query) {
		$logger->fatal ( "用户在退货时更新订单状态失败:" . $update_catalog );
		throw new Exception ( "用户在退货时更新订单状态失败！" );
	}
	
	$order_log_sql = "insert into order_log(order_id,message)values('" . $colname_order . "','" . 申请退货 . "')";
	if (! mysql_query ( $order_log_sql, $localhost )) {
		$logger->fatal ( "用户在退货时添加订单处理记录失败:" . $update_catalog );
		throw new Exception ( "用户在退货时添加订单处理记录失败！" );
	}
	
	$remove_succeed_url = "index.php";
	header ( "Location: " . $remove_succeed_url );
} catch ( Exception $ex ) {
	$could_return = 0;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if($could_return==0){ ?>
<div class="phpshop123_infobox">
		<p>由于一下原因，您不能申请退货：</p>
		<p>1. 订单不存在，请检查参数之后再试。</p>
		<p>2. 系统错误，请稍后再试。</p>
		<p>3. 这个订单不属于您</p>
		<p>4. 订单已经被删除</p>
		<p>5. 订单只有在处于已经收获的状态下才可以要求退货</p>
		<p>
			您也可以<a href="index.php">点击这里返回</a>。
		</p>
	</div>
	<p>
  <?php } ?>
</p>
</body>
</html>
<?php
mysqli_free_result ( $order );
?>