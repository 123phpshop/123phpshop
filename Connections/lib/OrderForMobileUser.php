<?php
/**
 * 手机用户的订单处理类
 * @author thomas
 *
 */
class OrderForMobileUser extends CartManger {
	public function __construct($token) {
		parent::__construct ( ORDER_TYPE_MOBILE_USER, $token );
	}
	/**
	 * 加载购物车数据
	 */
	final function _load_cart_data() {
		$this->_load_cart_data_by_field('token');
	}
}