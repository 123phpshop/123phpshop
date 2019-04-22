<?php
include_once "cart.php";
/**
 * 数据库购物车类
 *
 * @author thomas
 *        
 */
class DBCart extends Cart {
	protected $cart_table_name;
	protected $cart_items_table_name;
	final function _update_order_info() {
		$sql = "update ";
		return mysqli_query($this->conn,$sql);
	}
	final function _clear_order_products() {
		$sql = "delete from $cart_table_name where order_id=";
		return mysqli_query($this->conn,$sql);
	}
	final function _add_order_products() {
		foreach ( $this->cart ['products'] as $product ) {
			$sql = "delete from $cart_table_name where order_id=";
			$op = mysqli_query($this->conn,$sql);
			if (! $op) {
				throw new Exception ( "系统错误，请稍后再试！" );
			}
		}
	}
	final function _load_cart_data() {
		// 检查token是否存在，如果不存在，那么直接退出
		if (empty ( $this->token )) {
			return false;
		}
		
		// 这里需要根据用户提交的token，检查数据库里面是否有购物车商品信息，如果有的话，那么直接对购物车数据进行初始化
		$this->_load_cart_order ();
		$this->_load_cart_order_items ();
		
		// 如果存在，那么获取订单信息，然后更新到购物车中
		
		// 检查订单的商品表中是否有商品，如果有的话，那么将商品信息更新到购物车中
	}
	
	/**
	 * 加载购物车本表里面的数据
	 *
	 * @throws Exception
	 */
	private function _load_cart_order() {
		$sql = "select * from " . $this->cart_table_name . " where token='" . $this->token . "'";
		$query = mysqli_query ($localhost,$sql );
		if (! $query) {
			throw new Exception ( "系统错误，请稍后重试！" );
		}
		
		// 如果找不到的话，那么返回false;
		if (mysqli_num_rows ( $query ) == 0) {
			return;
		}
		
		$result = mysqli_fetch_assoc ( $query );
		$this->cart ['order_id'] = $result ['id'];
		$this->cart ['promotion_id'] = explode ( ",", $result ['promotion_id'] ); // 初始化可以享受的促销类型
		$this->cart ['promotion_fee'] = $result ['promotion_fee']; // 初始化可以享受的促销类型
		$this->cart ['products_total'] = $result ['products_total']; // 初始化购物车中的商品总额
		$this->cart ['shipping_fee'] = $result ['shipping_fee']; // 初始化购物车运费费用
		$this->cart ['order_total'] = $result ['should_paid']; // 初始化购物车订单总额
		return true;
	}
	
	/**
	 * 加载购物车的商品数据
	 *
	 * @param unknown $order        	
	 */
	private function _load_cart_order_items() {
		
		// 检查订单的id是否为0，如果为0的话，那么说明没有找打购物车订单的本表,那么直接退出不进行任何操作
		if ($this->cart ['order_id'] == 0) {
			return;
		}
		
		// 如果可以找到本表信息，那么继续寻找这个购物车订单中的商品信息
		$sql = "select * from " . $this->cart_items_table_name . " where order_id='" . $this->token . "'";
		$query = mysqli_query ($localhost,$sql );
		if (! $query) {
			throw new Exception ( "系统错误，请稍后重试！" );
		}
		
		// 如果找不到的话，那么返回false;
		if (mysqli_num_rows ( $query ) == 0) {
			return;
		}
		
		// 循环这些商品，然后将其添加到购物车商品列表中
		while ( $result = mysqli_fetch_assoc ( $query ) ) {
		}
	}
}
class UserDBCart extends DBCart {
	protected $cart_table_name = "cart_orders";
	protected $cart_items_table_name = "cart_order_item";
}
class AdminDBCart extends DBCart {
	protected $cart_table_name = "orders";
	protected $cart_items_table_name = "order_item";
}