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
interface IOrder {
	public function add_product($product);
	public function remove_product($product);
	public function update_product_quantity($product, $quantity);
}
abstract class Order implements IOrder {
	public function add_product($prodcut) {
		$this->_update_fee_promotion();
	}
	public function remove_product($prodcut) {
		
		$this->_update_fee_promotion();
	}
	public function update_product_quantity($prodcut, $quantity) {
		
		$this->_update_fee_promotion();
	}
	
	private function _update_fee_promotion(){
		$this->_update_order_total();
		$this->_update_product_total();
		$this->_update_order_total();
		$this->_update_promotion();
	}
	
	function _update_order_total(){
			
	}
	
	function _update_product_total(){
		
	}
	
	function _update_order_total(){
		
	}
	
	function _update_promotion(){
		
	}
	
	abstract function _do_update_shipping_fee($fee);
	abstract function _do_update_product_total($fee);
	abstract function _do_update_order_total($fee);
	abstract function _do_update_promotion($promotion);
	
	abstract function _get_update_shipping_fee();
	abstract function _get_update_product_total();
	abstract function _get_update_order_total();
	abstract function _get_update_promotion();
}
class CartOrder extends Order{
	
}
class AdminOrder extends Order{
}