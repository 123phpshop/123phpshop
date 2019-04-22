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
 ?>
<?php
require_once ('Connections/localhost.php');
$result = array ('code' => '0', 'message' => 'SUCCEED', 'data' => array () );

try{

// 这里对字段进行验证
$validation->set_rules ( 'product_id', '', 'required|is_natural_no_zero' );
if (! $validation->run ()) {
	$logger->warn ( "用户在删除购物车商品的时候参数错误，ip是." . $_SERVER ['REMOTE_ADDR'] . ", 企图删除的商品id是：" . $_POST ['product_id'] );
	throw new Exception ( "参数错误！" );
}
	
	$cart_obj = new Cart ();
	$cart = $cart_obj->remove ( (int)$_POST ['product_id'],$_POST['attr_value'] );
	if (! $cart) {
		throw new Exception ( "删除失败，请稍后再试！" );
	} else {
		$result ['data'] ['total_price'] = $_SESSION ['cart'] ['order_total'];
		$result ['data'] ['cart'] = $_SESSION ['cart'];
	}
}catch(Exception $ex){
	$result ['code']="1";
	$result ['message']=$ex->getMessage();
}
echo json_encode ( $result );return;	