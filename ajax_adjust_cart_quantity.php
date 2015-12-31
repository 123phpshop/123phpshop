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
?><?php

require_once ('Connections/localhost.php');
?>
<?php

$result = array (
		'code' => '0',
		'message' => 'SUCCEED',
		'data' => array () 
);
$cart_obj = new Cart ();
$product_id = ( int ) ($_POST ['product_id']);
$quantity = ( int ) ($_POST ['quantity']);
$attr_value = $_POST ['attr_value'];
try {
	
	// 这里对字段进行验证
	$validation->set_rules('product_id', '', 'required|is_natural_no_zero');
	$validation->set_rules('quantity', '', 'required|is_natural_no_zero');
	$validation->set_rules('attr_value', '', 'required|alpha_dash');
	if (!$validation->run())
	{
		$logger->fatal("用户在调整购物车商品数量的时候出现参数错误问题，ip是.".$_SERVER['REMOTE_ADDR'].", 企图调整的商品id是：".$_POST['product_id']);
		throw new Exception("参数错误！");
	}
	
	if ($quantity > 0) {
		$cart_obj->change_quantity ( $product_id, $quantity, $attr_value );
	}
	
	$presents_ids = array ();
	foreach ( $_SESSION ['cart'] ['products'] as $product ) {
 		if (isset ( $product ['is_present'] ) && $product ['is_present'] == 1) {
			$presents_ids [] = $product ['product_id'];
		}
	}
	
	$result ['data'] ['total_price'] = $_SESSION ['cart'] ['order_total'];
	$result ['data'] ['shipping_fee'] = $_SESSION ['cart'] ['shipping_fee'];
	$result ['data'] ['products_total'] = $_SESSION ['cart'] ['products_total'];
	$result ['data'] ['promotion_fee'] = $_SESSION ['cart'] ['promotion_fee'];
	$result ['data'] ['presents_ids'] = implode ( ',', $presents_ids );
} catch ( Exception $ex ) {
	$result = array (
			'code' => '1',
			'message' => $ex->getMessage () 
	);
}

die ( json_encode ( $result ) );