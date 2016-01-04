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

require_once ($_SERVER ['DOCUMENT_ROOT'] . '/Connections/localhost.php');
require_once ($_SERVER ['DOCUMENT_ROOT'] . '/Connections/lib/email.php');
?>
<?php

$cart_obj = new Cart ();

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	$validation->set_rules ( 'quantity', '', 'required|is_natural_no_zero' );
	$validation->set_rules ( 'product_id', '', 'required|is_natural_no_zero' );
	$validation->set_rules ( 'attr_value', '', 'max_length[20]|min_length[2]|alpha_numeric' );
	$validation->set_rules ( 'product_name', '', 'required|max_length[255]|min_length[2]' );
	$validation->set_rules ( 'product_image', '', 'alpha_dash' );
	$validation->set_rules ( 'ad_text', '', 'max_length[0]|min_length[32]' );
	if (! $validation->run ()) {
 		$MM_redirectLoginFailed = "/index.php";
		header ( "Location: " . $MM_redirectLoginFailed );
		return;
	}
	
	// $logger->debug("购物车添加商品");
	$cart_obj->add ( $_POST );
}
$cart = $cart_obj->get ();
$cart_products = $cart ['products'];
include ($template_path . "cart.php");

?>