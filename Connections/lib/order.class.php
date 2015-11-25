<?php
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