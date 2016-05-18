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

require_once 'order.interface.php';
/**
 * 订单操作的抽象类
 *
 * @author thomas
 *        
 */
abstract class Order implements IOrder {
	protected $order;
	public function __construct($order) {
		$this->order = $order;
	}
	public function add_product($prodcut) {
		global $glogger;
		
		// @toto 获取默认收货地址
		
		// @toto 如果用户已经登录的话，那么获取用户的默认收货地址
		
		// @toto 如果用户没有登录的话，那么获取系统的默认收货区域
		
		// @toto 检查商品是否可以配送到这个区域，如果不能配送到这个区域，那么返回false
		
		// 如果session中的产品的数量为0的话，那么直接将产品添加到购物车中的产品列表中即可
		$_is_product_exits_in_cart = $this->_is_product_exits_in_cart ( $product );
		
		// $glogger->debug ( "添加商品到购物车：检查商品是否存在：" . $_is_product_exits_in_cart );
		
		// 如果购物车中没有该商品的话，那么直接进行添加
		if (! $_is_product_exits_in_cart) {
			// $glogger->debug ( "添加商品到购物车：购物车中没有此商品，直接添加商品。" );
			
			// 如果不为0 的话，那么需要检查购物车中是否有这个产品，如果有的话，那么更新这个产品的数量
			$this->_do_add_product ( $product );
		} else {
			// 如果已经有了这个产品的话，那么需要增加这个商品的数量
			// $glogger->debug ( "添加商品到购物车：购物车中有此商品，更新商品数量。" );
			$this->_update_product_quantity ( $product );
		}
		
		// 更新产品总价
		$this->_update_products_total ();
		
		$this->_clear_promotion (); // 清除购物车中的所有促销信息
		                            
		// 更新订单总价
		                            // $glogger->debug ( "添加商品到购物车：开始更新订单总价" );
		                            // $this->_update_order_total ();
		
		$glogger->debug ( "添加商品到购物车：开始更新购物车中的促销信息" );
		$this->_update_promotion_info (); // 更新购物车中的促销信息
	}
	public function update_product_quantity($prodcut, $quantity) {
	}
	
	/**
	 * 从订单中删除商品
	 *
	 * {@inheritDoc}
	 *
	 * @see IOrder::remove_product()
	 */
	public function remove_product($product) {
		// 初始化参数
		$product_id = $product ['product_id'];
		$attr_value = $product ['attr_value'];
		
		// 检查这个产品是否在cart中，如果不在的话，那么直接返回true
		if (! $this->_is_product_exits_in_order ( $product_id, $attr_value )) {
			return true;
		}
		
		// 如果在的话，那么将这个产品从购物车中移除
		$this->_remove_from_order ( $product_id, $attr_value );
		
		// 更新商品的总额
		$this->_update_product_total ();
		
		$this->_update_shipping_fee ();
		// 更新促销费用
		$this->_update_promotion_info_for_remove ();
	}
	
	/**
	 * 更新购物车中的运费
	 */
	function _update_shipping_fee() {
		require_once ($_SERVER ['DOCUMENT_ROOT'] . "/Connections/lib/order.php");
		$shipping_fee = get_shipping_fee ( $this->order );
		$this->order ['shipping_fee'] = $shipping_fee ['shipping_fee'];
		$this->order ['shipping_method_id'] = $shipping_fee ['shipping_fee_plan'];
		return true;
	}
	
	/**
	 * 更新购物车中的商品总价
	 *
	 * @return number
	 */
	protected function _update_promotion_info_for_remove() {
		// 初始化数据
		$products = $this->order ['products']; // 获取订单的
		$this->order ['promotion_id'] = "";
		
		// 获取促销信息
		if (count ( $products ) == 0) {
			$promotion_fee = 0.00;
			$promotion_fee_promotion_ids = "";
			$shipping_fee = 0.00;
			$products_fee = 0.00;
			$order_total = 0.00;
			$shipping_method_id = 0;
		} else {
			$promotion_fee_obj = $this->_123phpshop_get_promotion_fee ( $this->order ['products_total'], $this->order ); // 获取促销费用数据
			$products_fee = $this->order ['products_total']; // 获取所有的商品费用
			$promotion_fee = $promotion_fee_obj ['fee']; // 获取促销的费用
			$promotion_presents = $promotion_fee_obj ['presents']; // 获取促销的赠品
			$promotion_fee_promotion_ids = implode ( ",", $promotion_fee_obj ['promotion_ids'] );
			// 清除购物车中的赠品
			$this->_123phpshop_clear_order_presents ();
			
			// 将赠品添加到购物车中
			$this->_123phpshop_add_order_presents ( $promotion_presents );
			
			// 获取运费信息
			$shipping_fee_array = get_shipping_fee ( $this->order );
			$shipping_fee = $shipping_fee_array ['shipping_fee'];
			$shipping_method_id = $shipping_fee_array ['shipping_fee_plan'];
			
			$order_total = $this->_123phpshop_get_order_total ( $products_fee, $shipping_fee, $promotion_fee ); // 获取订单的总费用
		}
		// 更新订单总额
		$this->_123phpshop_do_update_order_fee ( $shipping_fee, $products_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids, $shipping_method_id ); // 更新db中的数据
	}
	
	/**
	 * 获取促销的金额，计划和赠品
	 *
	 * @param unknown $products_fee        	
	 * @param unknown $order        	
	 * @return number[]|number[]|unknown
	 */
	function _123phpshop_get_promotion_fee($products_fee, $order) {
		// 初始化结果参数
		$results = array ();
		$results ['fee'] = 0.00;
		$results ['presents'] = array ();
		$results ['promotion_ids'] = array ();
		
		// 获取所有当前可用的促销计划
		global $db_conn;
		$sql = "SELECT * FROM promotion WHERE is_delete = 0 and start_date<=" . date ( 'Ymd' ) . " and end_date>=" . date ( 'Ymd' );
		$promotions = mysql_query ( $sql, $db_conn );
		if (mysql_num_rows ( $promotions ) == 0) {
			return $results;
		}
		
		// 循环这些促销
		while ( $promotion_plan = mysql_fetch_assoc ( $promotions ) ) {
			
			// 这里需要检查用户是否已经享受到了这个促销,如果已经享受了的话，那么就不用在进行了
			$promotion_ids_array = explode ( ",", $order ['promotion_id'] );
			if (in_array ( $promotion_plan ['id'], $promotion_ids_array )) {
				continue;
			}
			
			// 检查促销的使用范围，如果是全场的话，那么直接添加
			if ($promotion_plan ['promotion_limit'] == "1" && ( float ) $products_fee > ( float ) $promotion_plan ['amount_lower_limit']) {
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $products_fee );
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
			
			// 如果是分类的话
			if ($promotion_plan ['promotion_limit'] == "2") {
				
				// 检查获取这张订单中所有参与分类的商品的总金额
				$catalog_product_fee = $this->_123phpshop_get_catalog_product_fee ( $promotion_plan );
				// 如果指定分类的商品的总额《参与促销活动的最低的金额的话
				if (( float ) $catalog_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					continue;
				}
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $catalog_product_fee );
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
			
			// 如果是品牌的话
			if ($promotion_plan ['promotion_limit'] == "3") {
				
				// 获取订单中
				$brand_product_fee = $this->_123phpshop_get_brand_product_fee ( $promotion_plan );
				// 如果这个品牌的商品的总额《参与促销活动的最低的金额的话
				if (( float ) $brand_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					continue;
				}
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $brand_product_fee );
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
			
			// 如果是指定产品的话
			if ($promotion_plan ['promotion_limit'] == "4") {
				
				$product_product_fee = $this->_123phpshop_get_product_product_fee ( $promotion_plan );
				// 如果指定商品的总额《参与促销活动的最低的金额的话
				if (( float ) $product_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					continue;
				}
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $product_product_fee );
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
		}
		return $results;
	}
	
	/**
	 * 获取促销的促销金额，折扣赠品
	 *
	 * @param unknown $promotion_plan        	
	 * @param unknown $fee        	
	 */
	function _get_promotion_fee_presents($promotion_plan, $fee) {
		// 检查促销的类型
		$result = array ();
		$result ['fee'] = 0.00;
		$result ['presents'] = array ();
		switch ($promotion_plan ['promotion_type']) {
			case "1" : // 满增
			           // 这里需要检查是否这个订单已经有了这个赠品了，赠品只能另一次哦
				$result ['presents'] = explode ( ",", $promotion_plan ['present_products'] );
				return $result;
				break;
			
			case "2" : // 满减
			           // 这里需要检查这个订单是否已经享受了满减了
				$result ['fee'] = $promotion_plan ['promotion_type_val'];
				return $result;
				break;
			
			case "3" : // 满折
			           // 这里需要检查这个订单是否已经享受了满折
				$result ['fee'] = ( float ) $fee - (( float ) $fee * ( float ) $promotion_plan ['promotion_type_val'] / 100);
				return $result;
				break;
			
			default :
				return $result;
		}
	}
	
	/**
	 * 获取该订单中属于促销所属分类的所有产品的总额
	 *
	 * @param unknown $order        	
	 * @param unknown $promotions        	
	 * @return number
	 */
	function _123phpshop_get_catalog_product_fee($promotions) {
		$result = 0.00;
		
		// 获取参与促销的分类数组
		$cata_id_array = explode ( ",", $promotions ['promotion_limit_value'] );
		if (count ( $cata_id_array ) == 0) {
			return $result;
		}
		
		// 循环每个商品
		foreach ( $this->order ['products'] as $product ) {
			// 循环每个参与其中的分类， 如果当前的商品的分类属于参与这个促销的品牌的话，
			foreach ( $cata_id_array as $catalog_id ) {
				// 如果参与其中的分类的id
				if (strpos ( $product ['cata_path'], "|" . $catalog_id . "|" ) >= 0) {
					$result += ( float ) $product ['should_pay_price'] * ( int ) $product ['quantity'];
					break;
				}
			}
		}
		return $result;
	}
	
	/**
	 * 获取该订单中属于促销所属品牌的所有产品的总额
	 *
	 * @param unknown $order        	
	 * @param unknown $promotions        	
	 * @return number
	 */
	function _123phpshop_get_brand_product_fee($promotions) {
		$result = 0.00;
		
		// 获取品牌的数组
		$brand_id_array = explode ( ",", $promotions ['promotion_limit_value'] );
		
		if (count ( $brand_id_array ) == 0) {
			return $result;
		}
		
		foreach ( $this->order ['products'] as $product ) {
			// 如果当前的商品的品牌属于参与这个促销的品牌的话，
			if (in_array ( $product ['brand_id'], $brand_id_array )) {
				$result += ( float ) $product ['should_pay_price'] * ( int ) $product ['quantity'];
			}
		}
		return $result;
	}
	
	/**
	 * 获取该订单中属于促销所属产品的所有的总额
	 *
	 * @param unknown $order        	
	 * @param unknown $promotions        	
	 * @return number
	 */
	function _123phpshop_get_product_product_fee($promotions) {
		$result = 0.00;
		
		$product_id_array = explode ( ",", $promotions ['promotion_limit_value'] );
		
		if (count ( $product_id_array ) == 0) {
			return $result;
		}
		
		foreach ( $this->order ['products'] as $product ) {
			if (in_array ( $product ['product_id'], $product_id_array )) {
				$result += ( float ) $product ['should_pay_price'] * ( int ) $product ['quantity'];
			}
		}
		return $result;
	}
	abstract function _123phpshop_do_update_order_fee($shipping_fee, $products_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids, $shipping_fee_plan);
	abstract function _123phpshop_add_order_presents($promotion_presents);
	final function _123phpshop_get_order_total($products_fee, $shipping_fee, $promotion_fee) {
		return ( float ) $products_fee + ( float ) $shipping_fee - ( float ) $promotion_fee;
	}
	
	// 从数据库里面获取产品的价格
	final function _get_product_from_db_by_id($product_id) {
		
		// 这里还是需要获取是否有优惠价格
		global $db_conn;
		global $db_database_localhost;
		mysql_select_db ( $db_database_localhost );
		$query_product = "SELECT id,name,price,brand_id,cata_path,is_shipping_free,is_promotion,promotion_price,promotion_start,promotion_end FROM product WHERE id = " . $product_id;
		$product = mysql_query ( $query_product, $db_conn ) or die ( mysql_error () . "_get_product_from_db_by_id" );
		$row_product = mysql_fetch_assoc ( $product );
		
		return $row_product;
	}
	
	/**
	 * 检查商品是否在订单中存在
	 *
	 * @param unknown $product_id        	
	 * @param unknown $attr_value        	
	 */
	final function _is_product_exits_in_order($product_id, $attr_value) {
		
		// 检查数据结构是否合理
		if (! isset ( $this->order ['products'] ) || empty ( $this->order ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $this->order ['products'] as $item ) {
			
			if (! isset ( $item ['product_id'] )) {
				continue;
			}
			
			if (( int ) $item ['product_id'] == ( int ) $product_id && $item ['attr_value'] == $attr_value) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 从订单中删除这个商品
	 *
	 * @param unknown $product_id        	
	 * @param unknown $attr_value        	
	 */
	final function _remove_from_order($product_id, $attr_value) {
		
		// 循环购物车中的所有产品，然后检查他们的产品id，如果当前的产品id和我们所需要的产品id是一致的话删除。
		for($i = 0; $i < count ( $this->order ['products'] ); $i ++) {
			if (( int ) $this->order ['products'] [$i] ['product_id'] == ( int ) $product_id && $this->order ['products'] [$i] ['attr_value'] == $attr_value) {
				unset ( $this->order ['products'] [$i] );
				break;
			}
		}
		sort ( $this->order ['products'] );
		$this->_do_remove_from_order ( $product_id, $attr_value );
		return true;
	}
	abstract function _do_remove_from_order($product_id, $attr_value);
	
	/**
	 * 更新订单中商品的总价
	 */
	private function _update_product_total() {
		$products_total = 0.00;
		if (count ( $this->order ['products'] ) == 0) {
			return $products_total;
		}
		
		// 对订单中的每个产品的总价进行累加
		foreach ( $this->order ['products'] as $product ) {
			
			// 如果商品是赠品的话,那么直接跳过
			if (isset ( $product ['is_present'] ) && $product ['is_present'] == 1) {
				continue;
			}
			
			// 如果商品的价格没有设置的话,那么直接跳过
			if (! isset ( $product ['product_price'] ) && ! isset ( $product ['should_pay_price'] )) {
				continue;
			}
			
			// 默认情况下商品的价格就是它的本身
			
			if (! key_exists ( "product_price", $product )) {
				$price = $product ['should_pay_price'];
			} else {
				$price = $product ['product_price'];
			}
			
			// 这里需要检查是否在优惠日之内，如果在促销期间之内那么商品的价格将会是促销价格
			if (isset ( $product ['is_promotion'] ) && $product ['is_promotion'] == "1" && (date ( 'Y-m-d' ) >= $product ['promotion_start']) && (date ( 'Y-m-d' ) <= $product ['promotion_end'])) {
				$price = $product ['promotion_price'];
			}
			
			// 最后计算商品的总价=商品价格×商品的数量
			$products_total += floatval ( $price ) * $product ['quantity'];
		}
		
		// 更新商品的总价格
		$this->order ['products_total'] = $products_total;
	}
	abstract function _do_update_shipping_fee($fee);
	abstract function _do_update_order_total($fee);
	abstract function _do_update_promotion($promotion);
	abstract function _get_update_shipping_fee();
	abstract function _get_update_product_total();
	abstract function _get_update_order_total();
	abstract function _get_update_promotion();
}
class AdminOrder extends Order {
	final function _do_remove_from_order($product_id, $attr_value) {
		// 数据库里面更新记录
		global $db_conn;
		$sql = sprintf ( "update order_item set is_delete=1 where product_id=%s and attr_value='%s' and order_id='%s'", $product_id, $attr_value, $this->order ['id'] );
		if ($attr_value == '') {
			$sql = sprintf ( "update order_item set is_delete=1 where product_id=%s and attr_value is null and order_id='%s'", $product_id, $this->order ['id'] );
		}
		if (! mysql_query ( $sql, $db_conn )) {
			throw new Exception ( "系统错误，请联系123phpshop.com" . mysql_error () );
		}
	}
	final function _123phpshop_clear_order_presents() {
		
		// 清除模型层中的赠品
		$result = array ();
		// 如果购物车中没有商品的话，那么直接返回
		if (count ( $this->order ['products'] ) == 0) {
			return true;
		}
		
		// 如果有商品的话，那么循环这些商品，然后将不是赠品的商品调出来
		foreach ( $this->order ['products'] as $product ) {
			if ($product ['is_present'] == "0" || $product ['is_present'] == 0) {
				$result [] = $product;
			}
		}
		
		// 如果购物车里面全都是赠品的话，那么直接返回
		if (count ( $result ) == 0) {
			$this->order ['products'] = array ();
		} else {
			$this->order ['products'] = $result;
		}
		
		// 清楚数据库里面的赠品
		global $db_conn;
		$sql = "update order_item set is_delete=1 where is_present=1 and order_id=" . $this->order ['id'];
		if (! mysql_query ( $sql, $db_conn )) {
			throw new Exception ( "系统错误，请联系哦123phpshop.com" . mysql_error () );
		}
	}
	
	/**
	 * 更新订单费用的数据库字段
	 *
	 * @param unknown $product_fee        	
	 * @param unknown $shipping_fee        	
	 * @param unknown $promotion_fee        	
	 * @param unknown $order_total        	
	 * @param unknown $shipping_fee_plan        	
	 * @param unknown $promotion_fee_promotion_ids
	 *        	订单中包含的促销活动
	 * @throws Exeption
	 */
	final function _123phpshop_do_update_order_fee($shipping_fee, $products_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids, $shipping_fee_plan) {
		global $db_conn;
		/*
		 * $sql = sprintf ( "update orders set products_total=%s,shipping_fee=%s,promotion_fee=%s,should_paid=%s,shipping_method=%s where id=%s", $products_fee, $shipping_fee, $promotion_fee, $order_total, $shipping_fee_plan, $this->order ['id'] );
		 * if ($promotion_fee_promotion_ids != '') {
		 */
		$sql = sprintf ( "update orders set products_total=%s,shipping_fee=%s,promotion_fee=%s,should_paid=%s,shipping_method=%s,promotion_id='%s' where id=%s", $products_fee, $shipping_fee, $promotion_fee, $order_total, $shipping_fee_plan, $promotion_fee_promotion_ids, $this->order ['id'] );
		// }
		
		$result = mysql_query ( $sql, $db_conn );
		if (! $result) {
			throw new Exception ( "订单费用更新错误，请重试,欢迎联系123phpshop.com寻求解决方案" . mysql_error () );
		}
	}
	
	/**
	 * 数据库操作
	 *
	 * {@inheritDoc}
	 *
	 * @see Order::_123phpshop_add_order_presents()
	 */
	final function _123phpshop_add_order_presents($promotion_presents) {
		// 如果赠品的数量为0的话，那么直接退出
		if (count ( $promotion_presents ) == 0) {
			return;
		}
		
		// 如果》0的话，那么循环这些赠品，
		foreach ( $promotion_presents as $product_id ) {
			
			$product = $this->_get_product_from_db_by_id ( $product_id );
			$product ['is_present'] = 1;
			$product ['quantity'] = 1;
			$product ['should_pay_price'] = 0.00;
			$product ['product_price'] = 0.00;
			$this->order ['products'] [] = $product;
			
			global $db_conn;
			$insertSQL = sprintf ( "INSERT INTO order_item (should_pay_price,order_id,product_id, quantity, attr_value, is_present) VALUES (%s,%s, %s,%s, '%s', %s)", 0.00, ( int ) $this->order ['id'], ( int ) $product_id, 1, "", 1 );
			$new_order_item_query = mysql_query ( $insertSQL, $db_conn );
			if (! $new_order_item_query) {
				throw new Exception ( "系统错误，请联系123phpshop.com寻求解决方案" . mysql_error () );
			}
		}
	}
	final function _do_update_shipping_fee($fee) {
	}
	final function _do_update_order_total($fee) {
	}
	final function _do_update_promotion($promotion) {
	}
	final function _get_update_shipping_fee() {
	}
	final function _get_update_product_total() {
	}
	final function _get_update_order_total() {
	}
	final function _get_update_promotion() {
	}
}