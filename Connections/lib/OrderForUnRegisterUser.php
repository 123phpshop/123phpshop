<?php
class OrderForUnRegisterUser extends Cart {
	public function __construct($session_id) {
		parent::__construct ( ORDER_TYPE_REGISTERED, $session_id );
	}
	/**
	 * 加载购物车数据
	 */
	final function _load_cart_data() {
		$this->_load_cart_data_by_field('session_id');
	}
}
