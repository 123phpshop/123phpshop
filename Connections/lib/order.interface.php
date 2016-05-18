<?php
/**
 * 订单接口
 *
 * @author thomas@123phpshop.com
 *
 */
interface IOrder {
	
	/**
	 * 向订单中添加商品
	 *
	 * @param unknown $product
	 *        	商品
	 */
	public function add_product($product);
	/**
	 * 删除删除订单中的商品
	 *
	 * @param unknown $product
	 *        	商品
	 */
	public function remove_product($product);
	
	/**
	 * 更新订单中商品的数量
	 *
	 * @param unknown $product
	 *        	商品
	 * @param unknown $quantity
	 *        	数量
	 */
	public function update_product_quantity($product, $quantity);
}
?>
