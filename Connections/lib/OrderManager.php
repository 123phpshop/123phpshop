<?php
/**
 * 订单的管理类
 * @author thomas
 *
 */
class OrderManager {
	
	/**
	 * 获取购物车中产品的运费
	 */
	public function get_shipping_fee($order = array()) {
		
		// 获取这个订单的所有商品的总重量和总数量
		if ($order == array ()) {
			$order = $_SESSION ['cart'];
		}
		
		$weight = 0.00;
		$quantity = 1;
		$shipping_fee = 0.00;
		$shipping_fee_plan = 1;
		
		$weight = _get_order_weight ( $order ['products'] );
		$is_first_shipping_fee = true;
		$quantity = _get_order_quantity ( $order ['products'] );
		$shipping_methods = _get_shipping_methods ( $order );
		
		// 如果找不到相应的配送方式的话
		if (count ( $shipping_methods ) == 0) {
			// throw new Exception("不能运送");
		}
		
		// 获取所有激活的没有被删除的快递方式
		foreach ( $shipping_methods as $shipping_methods_item ) {
			
			// 如果产品是货到付款的
			if ($shipping_methods_item ['is_cod'] == 1) {
				$shipping_fee = 0.00;
				$shipping_fee_plan = $shipping_methods_item ['shipping_methods_id'];
				break;
			}
			
			// 如果这个运送方法是通过产品数量进行计算的话，那么计算出运费
			if ($shipping_methods_item ['shipping_by_quantity'] == 1) {
				$shipping_fee_now = _calc_shipping_fee_by_quantity ( $quantity, $shipping_methods_item );
			} else {
				// 如果这个运费送方式是通过产品重量来计算的话，那么计算出运费
				$shipping_fee_now = _calc_shipping_fee_by_weight ( $weight, $shipping_methods_item );
			}
			
			// 如果这里是第一个运费方案的话，那么直接设置为这里的运费
			if ($is_first_shipping_fee == true || $shipping_fee_now < $shipping_fee) {
				$is_first_shipping_fee = false;
				$shipping_fee = $shipping_fee_now;
				$shipping_fee_plan = $shipping_methods_item ['shipping_method_id'];
			}
		}
		
		$result ['shipping_fee'] = $shipping_fee;
		$result ['shipping_fee_plan'] = $shipping_fee_plan;
		return $result;
	}
	
	/**
	 * 通过数量来计算运费
	 */
	private function _calc_shipping_fee_by_quantity($quantity, $shipping_methods_item) {
		return $shipping_methods_item ['basic_fee'] + $shipping_methods_item ['cod_fee'] + $shipping_methods_item ['single_product_fee'] * $quantity;
	}
	
	/**
	 * 通过重量来计算运费
	 * *
	 */
	private function _calc_shipping_fee_by_weight($weight, $shipping_methods_item) {
		
		// 获取购物车中商品的总重量
		$shipping_fee = 0.00;
		
		// 如果配送方式是按照kg来计算的话
		if ($shipping_methods_item ['first_kg_fee'] != null && $shipping_methods_item ['continue_kg_fee'] != null) {
			// 如果重量>=1kg的话
			if ($weight >= 1000) {
				$shipping_fee = ceil ( $weight / 1000 - 1 ) * floatval ( $shipping_methods_item ['continue_kg_fee'] ) + $shipping_methods_item ['first_kg_fee'];
			} else {
				// 如果重量<1kg的话
				$shipping_fee = $shipping_methods_item ['first_kg_fee'];
			}
		}
		
		// 如果配送方式是按照500g来计算的话
		if ($shipping_methods_item ['half_kg_fee'] != null && $shipping_methods_item ['continue_half_kg_fee'] != null) {
			// 如果重量>=1kg的话
			if ($weight >= 500) {
				$shipping_fee = ceil ( $weight / 500 - 1 ) * floatval ( $shipping_methods_item ['continue_half_kg_fee'] ) + $shipping_methods_item ['half_kg_fee'];
			} else {
				// 如果重量<1kg的话
				$shipping_fee = $shipping_methods_item ['half_kg_fee'];
			}
		}
		
		return $shipping_methods_item ['basic_fee'] + $shipping_methods_item ['cod_fee'] + $shipping_fee;
	}
}