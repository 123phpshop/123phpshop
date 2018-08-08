<?php
class OrderForRegisterUser extends Cart {
	public function __construct($user_id) {
		parent::__construct ( ORDER_TYPE_REGISTERED, $user_id );
	}
	/**
	 * 加载购物车数据
	 */
	final function _load_cart_data() {
		$this->_load_cart_data_by_field('user_id');
	}
}