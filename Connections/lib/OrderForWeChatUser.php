<?php
class OrderForWeChatUser extends Cart {
	public function __construct($open_id) {
		parent::__construct ( ORDER_TYPE_REGISTERED, $open_id );
	}
	/**
	 * 加载购物车数据
	 */
	final function _load_cart_data() {
		$this->_load_cart_data_by_field('open_id');
	}
}