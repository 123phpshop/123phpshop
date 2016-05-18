<?php
/**
 * 购物车的接口
 *
 * @author thomas
 *
 */
interface ICart {
	
	/**
	 * 向购物车里面添加商品
	 *
	 * @param unknown $product        	
	 */
	public function add_product($product);
	/**
	 * 从购物车里面删除这个商品
	 *
	 * @param unknown $product_id        	
	 * @param unknown $attr_value        	
	 */
	public function remove_product($product_id, $attr_value);
	
	/**
	 * 获取购物车对象
	 */
	public function get();
	
	/**
	 * 清空购物车
	 */
	public function clean_products();
	/**
	 * 修改购物车里面商品的数量
	 *
	 * @param unknown $product_id
	 *        	商品的id
	 * @param unknown $quantity
	 *        	需要变更的商品的数量，例如加1就是+1
	 * @param unknown $attr_value
	 *        	需要变更的商品的属性
	 */
	public function change_produt_quantity($product_id, $quantity, $attr_value);
	
	/**
	 * 获取购物车中的商品清单
	 */
	public function get_products();
}