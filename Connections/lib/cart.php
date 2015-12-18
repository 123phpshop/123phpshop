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
?>
<?php

class Cart {
	/**
	 * 构造函数
	 */
	public function __construct() {
		// 这里检查session是否开启，如果没有开启，那么开启
		if (false == $this->_is_cart_initialized ()) {
			$this->_init_cart ();
		}
	}
	
	/**
	 * 将产品添加到购物车
	 *
	 * @param unknown_type $product        	
	 */
	public function add($product) {
		
		// 获取默认收货地址
		
		// 如果用户已经登录的话，那么获取用户的默认收货地址
		
		// 如果用户没有登录的话，那么获取系统的默认收货区域
		
		// 检查商品是否可以配送到这个区域，如果不能配送到这个区域，那么返回false
		
		// 如果session中的产品的数量为0的话，那么直接将产品添加到购物车中的产品列表中即可
		$_is_product_exits_in_cart = $this->_is_product_exits_in_cart ( $product );
		
		if (! $_is_product_exits_in_cart) {
			
			// 如果不为0 的话，那么需要检查购物车中是否有这个产品，如果有的话，那么更新这个产品的数量
			$this->_do_add_product ( $product );
		} else {
			// 如果没有这个产品的话，那么将这个产品更新到session中的产品中
			$this->_update_product_quantity ( $product );
		}
		
		// 更新产品总价
		$this->_update_products_total ();
		
		// 更新运费
		$this->_update_shipping_fee ();
		
		// 更新订单总价
		$this->_update_order_total ();
		
		// 更新购物车中的促销信息
		$this->_update_promotion_info ();
	}
	
	/**
	 * 更新购物车中的促销信息
	 */
	private function _update_promotion_info($order = array()) {
		
		// 获取订单的费用
		if ($order == array ()) {
			$order = $_SESSION ['cart'];
		}
		
		$products = $order ['products']; // 获取订单的
		$products_fee = $order ['products_total']; // 获取所有的产品配用
		$shipping_fee = $order ['shipping_fee']; // 获取运费费用
		                                         
		// 获取订单的促销信息
		$promotion_fee_obj = $this->_123phpshop_get_promotion_fee ( $products_fee, $order ); // 获取促销费用数据
		
		$promotion_fee = $promotion_fee_obj ['fee']; // 获取促销的费用
		$promotion_presents = $promotion_fee_obj ['presents']; // 获取促销的赠品
		                                                       // 如果订单之中没有享受过的id的话，有可能是添加的第一个上牌你
		if ($order ['promotion_id'] == null) {
			$promotion_id_array = $promotion_fee_obj ['promotion_ids'];
		} else {
			$original_promotion_ids = explode ( ",", $order ['promotion_id'] );
			$promotion_id_array = array_merge ( $original_promotion_ids, $promotion_fee_obj ['promotion_ids'] );
			$promotion_id_array = array_unique ( $promotion_id_array );
		}
		
		// 检查购物车中促销里面是否已经存在，
		$promotion_fee_promotion_ids = implode ( ",", $promotion_id_array );
		
		// 获取订单可以享受到的促销总费用
		$promotion_fee = floatval ( $order ['promotion_fee'] ) + floatval ( $promotion_fee );
		$order_total = $this->_123phpshop_get_order_total ( $products_fee, $shipping_fee, $promotion_fee ); // 获取订单的总费用
		                                                                                                    
		// 更新订单总额
		$this->_do_update_order_fee ( $shipping_fee, $products_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids ); // 更新db中的数据
		                                                                                                                          
		// 将赠品添加到购物车中
		$this->_123phpshop_add_order_presents ( $order, $promotion_presents );
	}
	
	/**
	 * 清除订单中的赠品
	 *
	 * @param array $order        	
	 */
	private function _123phpshop_clear_order_presents($order = array()) {
		if ($order == array ()) {
			$this->_123phpshop_clear_cart_presents ();
			return;
		}
	}
	
	/**
	 * 清除购物车中的赠品
	 */
	private function _123phpshop_clear_cart_presents() {
		$result = array ();
		// 如果购物车中没有商品的话，那么直接返回
		if (count ( $_SESSION ['cart'] ['products'] ) == 0) {
			return true;
		}
		
		// 如果有商品的话，那么循环这些商品，然后将不是赠品的商品调出来
		foreach ( $_SESSION ['cart'] ['products'] as $product ) {
			if ($product ['is_present'] == "0" || $product ['is_present'] == 0) {
				$result [] = $product;
			}
		}
		
		// 如果购物车里面全都是赠品的话，那么直接返回
		if (count ( $result ) == 0) {
			$_SESSION ['cart'] ['products'] = array ();
			return;
		}
		
		// 如果里面有商品的话，那么直接将session
		$_SESSION ['cart'] ['products'] = $result;
	}
	
	/**
	 * 将促销生成的赠品添加到订单中
	 *
	 * @param unknown $order        	
	 * @param unknown $promotion_presents        	
	 */
	private function _123phpshop_add_order_presents($order, $promotion_presents) {
		// 如果赠品的数量为0的话，那么直接退出
		if (count ( $promotion_presents ) == 0) {
			return;
		}
		
		// 如果》0的话，那么循环这些赠品，
		foreach ( $promotion_presents as $product_id ) {
			$product = $this->_get_product_from_db_by_id ( $product_id );
			$product ['product_price'] = $product ['price'];
			$product ['should_pay_price'] = $product ['price'];
			$product ['product_id'] = $product ['id'];
			$product ['is_present'] = 1;
			$product ['quantity'] = 1;
			$product ['product_image'] = $this->_get_product_image_by_id ( $product ['id'] );
			// 这里需要获取这个商品的图片
			$_SESSION ['cart'] ['products'] [] = $product;
		}
	}
	private function _get_product_image_by_id($product_id) {
		$result = "";
		global $db_conn;
		global $db_database_localhost;
		mysql_select_db ( $db_database_localhost );
		$query_get_product_image = "SELECT * FROM product_images WHERE product_id = product_id and is_delete=0 limit 1";
		$get_product_image = mysql_query ( $query_get_product_image, $db_conn ) or die ( mysql_error () );
		$row_get_product_image = mysql_fetch_assoc ( $get_product_image );
		$totalRows_get_product_image = mysql_num_rows ( $get_product_image );
		if ($totalRows_get_product_image > 0) {
			return $row_get_product_image ['image_files'];
		}
		return $result;
	}
	
	/**
	 * 获取订单的总金额
	 *
	 * @param unknown $product_fee        	
	 * @param unknown $shipping_fee        	
	 * @param unknown $promotion_fee        	
	 * @return number
	 */
	function _123phpshop_get_order_total($product_fee, $shipping_fee, $promotion_fee) {
		return ( float ) $product_fee + ( float ) $shipping_fee - ( float ) $promotion_fee;
	}
	
	/**
	 * 更新订单费用的SESSION字段
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
	private function _do_update_order_fee($shipping_fee, $product_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids) {
		$_SESSION ['cart'] ['order_total'] = $order_total; // 更新购物车中的订单总额
		$_SESSION ['cart'] ['products_total'] = $product_fee; // 更新购物车中的产品费用
		$_SESSION ['cart'] ['shipping_fee'] = $shipping_fee; // 更新购物车中的运费
		$_SESSION ['cart'] ['promotion_fee'] = $promotion_fee; // 更新购物车中的运费
		$_SESSION ['cart'] ['promotion_id'] = $promotion_fee_promotion_ids; // 更新购物车中的促销计划的ids
	}
	
	/**
	 * 获取促销的促销金额，折扣赠品
	 *
	 * @param unknown $promotion_plan        	
	 * @param unknown $fee        	
	 */
	private function _get_promotion_fee_presents($promotion_plan, $fee) {
		// 检查促销的类型
		$result = array ();
		$result ['fee'] = 0.00;
		$result ['presents'] = array ();
		switch ($promotion_plan ['promotion_type']) {
			case "1" : // 满增
				$result ['presents'] = explode ( ",", $promotion_plan ['present_products'] );
				return $result;
				break;
			
			case "2" : // 满减
				$result ['fee'] = $promotion_plan ['promotion_type_val'];
				return $result;
				break;
			
			case "3" : // 满折
				$result ['fee'] = ( float ) $fee - (( float ) $fee * ( float ) $promotion_plan ['promotion_type_val'] / 100);
				return $result;
				break;
			
			default :
				return $result;
		}
	}
	
	/**
	 * 获取所有的促销计划
	 *
	 * @param unknown $order        	
	 * @return unknown[]
	 */
	private function _123phpshop_get_promotion_fee($product_fee, $order) {
		
		// 初始化结果参数
		$results = array ();
		$results ['fee'] = 0.00;
		$results ['presents'] = array ();
		$results ['promotion_ids'] = array ();
		
		// 从数据库中获取所有当前可用的促销计划
		global $db_conn;
		global $db_database_localhost;
		mysql_select_db ( $db_database_localhost );
		$sql = "SELECT * FROM promotion WHERE is_delete = 0 and start_date<=" . date ( 'Ymd' ) . " and end_date>=" . date ( 'Ymd' );
		$promotions = mysql_query ( $sql, $db_conn );
		if (mysql_num_rows ( $promotions ) == 0) {
			return $results;
		}
		
		// 循环这些促销
		while ( $promotion_plan = mysql_fetch_assoc ( $promotions ) ) {
			// 这里需要检查用户是否已经享受到了这个促销,如果已经享受了的话，那么就不用在进行了
			$promotion_ids_array = explode ( ",", $order ['promotion_id'] );
			
			// 如果用户已经享受了这个促销，而且这个不是满折内的话
			if ($promotion_plan ['promotion_type']!="3" && in_array ( $promotion_plan ['id'], $promotion_ids_array )) {
				continue;
			}
			
			
			// 检查促销的使用范围，如果是全场的话，那么直接添加
			if ($promotion_plan ['promotion_limit'] == "1" && ( float ) $product_fee > ( float ) $promotion_plan ['amount_lower_limit']) {
				
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $product_fee );
				
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
				$catalog_product_fee = $this->_123phpshop_get_catalog_product_fee ( $order, $promotion_plan );
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
				$brand_product_fee = $this->_123phpshop_get_brand_product_fee ( $order, $promotion_plan );
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
				
				$product_product_fee = $this->_123phpshop_get_product_product_fee ( $order, $promotion_plan );
				// 如果指定商品的总额《参与促销活动的最低的金额的话
				if (( float ) $product_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					continue;
				}
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = _get_promotion_fee_presents ( $promotion_plan, $product_product_fee );
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
	 * 获取该订单中属于促销所属分类的所有产品的总额
	 *
	 * @param unknown $order        	
	 * @param unknown $promotions        	
	 * @return number
	 */
	private function _123phpshop_get_catalog_product_fee($order, $promotions) {
		$result = 0.00;
		
		// 获取参与促销的分类数组
		$cata_id_array = explode ( ",", $promotions ['promotion_limit_value'] );
		if (count ( $cata_id_array ) == 0) {
			return $result;
		}
		
		// 循环每个商品
		foreach ( $order ['products'] as $product ) {
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
	private function _123phpshop_get_brand_product_fee($order, $promotions) {
		$result = 0.00;
		
		// 获取品牌的数组
		$brand_id_array = explode ( ",", $promotions ['promotion_limit_value'] );
		
		if (count ( $brand_id_array ) == 0) {
			return $result;
		}
		
		foreach ( $order ['products'] as $product ) {
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
	private function _123phpshop_get_product_product_fee($order, $promotions) {
		$result = 0.00;
		$product_id_array = explode ( ",", $promotions ['promotion_limit_value'] );
		if (count ( $product_id_array ) == 0) {
			return $result;
		}
		
		foreach ( $order ['products'] as $product ) {
			if (in_array ( $product ['product_id'], $product_id_array )) {
				$result += ( float ) $product ['should_pay_price'] * ( int ) $product ['quantity'];
			}
		}
		return $result;
	}
	
	/**
	 * 获取符合要求的促销计划
	 */
	private function _get_promotion_plan() {
		$results = array ();
		
		// 获取所有当前可用的促销计划
		// 这里还是需要获取是否有优惠价格
		global $db_conn;
				global $db_database_localhost;
 		mysql_select_db ( $db_database_localhost );
		
		$sql = "SELECT * FROM promotion WHERE is_delete = 0 and start_date<=" . date ( 'Ymd' ) . " and end_date>=" . date ( 'Ymd' );
		$promotions = mysql_query ( $sql, $db_conn );
		$promotion_plans = mysql_fetch_assoc ( $promotions );
		if (mysql_num_rows ( $product ) == 0) {
			return $results;
		}
		
		// 循环这些计划，检查当前的促销是否适合当前的购物车
		foreach ( $promotion_plans as $promotion_plan ) {
			// 如果当前购物车中的商品总额满足享受促销的最低金额的话
			if ($_SESSION ['cart'] ['products_total'] > $promotion_plan ['amount_lower_limit']) {
				$results [] = $promotion_plan;
			}
		}
		
		return $results;
	}
	
	/**
	 * 更新促销信息的数据
	 *
	 * @param unknown $promotion_plan        	
	 */
	private function _update_promotion_presents($promotion_plan) {
		
		// 获取产品id的数组
		$products_id_array = explode ( ",", $promotion_plan ['present_products'] );
		if (count ( products_id_array ) == 0) {
			return;
		}
		
		// 循环这些数组
		foreach ( $products_id_array as $products_id ) {
			// 从数据中获取这些赠品的信息
			$product = $this->_get_product_from_db_by_id ( $products_id );
			if ($product != null) {
				$_SESSION ['cart'] ['promotion_presents'] [] = $product;
			}
		}
	}
	
	// 更新购物车中的促销费用
	private function _update_promotion_fee($promotion_plan) {
		
		// 如果是满减的话，那么直接+即可
		if ($promotion_plan ['promotion_type'] == 2) {
			$_SESSION ['cart'] ['promotion_fee'] += $promotion_plan ['promotion_type_val'];
		}
		
		// 如果是满折扣的话，那么需要计算产品的金额，然后计算折扣的金额
		if ($promotion_plan ['promotion_type'] == 3) {
			$_SESSION ['cart'] ['promotion_fee'] += $_SESSION ['cart'] ['products_total'] * $promotion_plan ['promotion_type_val'] / 100;
		}
	}
	
	/**
	 * 减少购物车中某产品的数量
	 *
	 * @param unknown_type $product_id        	
	 * @param unknown_type $quantity        	
	 */
	public function decrease_quantity($product_id, $quantity) {
		// 检查产品是否存在，如果不存在，那么告知重新刷新页面
		$product = $this->_get_product_by_id ( $product_id );
		if (! $product) {
			throw new Exception ( "产品不存在，请刷新页面后重试" );
		}
		// 如果存在，那么检查产品的数量是否为1，如果为1的话，那么告知不能减低
		if (( int ) $product ['quantity'] == 1) {
			throw new Exception ( "请至少保留一件此商品，如果需要删除此件商品的话，请点击删除链接" );
		}
		// 如果都ok的话，那么这个产品的数量-1，然后返回更新后的数据
		if (! $this->_do_decrease_quantity ( $product_id, $quantity )) {
			throw new Exception ( "系统错误，请稍后重试" );
		}
		// 更新产品总价
		$this->_update_products_total ();
		
		// 更新运费
		$this->_update_shipping_fee ();
		
		// 更新订单总价
		$this->_update_order_total ();
		
		$this->_update_promotion_info_for_remove();
		// 更新促销信息
		
		return true;
	}
	
	/**
	 * 更新费用
	 *
	 * @return boolean
	 */
	public function update_fee() {
		// 更新产品总价
		$this->_update_products_total ();
		
		// 更新运费
		$this->_update_shipping_fee ();
		
 
		// 更新订单总价
		$this->_update_order_total ();
		
		return true;
	}
	
	/**
	 * 增加购物车中某产品的数量
	 *
	 * @param unknown_type $product_id        	
	 * @param unknown_type $quantity        	
	 */
	public function change_quantity($product_id, $quantity, $attr_value) {
		
		// 检查产品是否存在，如果不存在，那么告知重新刷新页面
		$product = $this->_get_product_by_id_attr_value ( $product_id, $attr_value );
		if (! $product) {
			throw new Exception ( "产品不存在，请刷新页面后重试" );
		}
		
		// 如果都ok的话，那么这个产品的数量+1，然后返回更新后的数据
		if (! $this->_do_change_quantity ( $product_id, $quantity, $attr_value )) {
			throw new Exception ( "系统错误，请稍后重试" );
		}
		
		// 更新产品总价
		$this->_update_products_total ();
		
		// 更新运费
		$this->_update_shipping_fee ();
		
		// 更新订单总价
		$this->_update_order_total ();
		
		return true;
	}
	
	/**
	 *
	 * @param unknown $product_id        	
	 * @param unknown $quantity        	
	 * @param unknown $attr_value        	
	 * @return boolean
	 */
	private function _do_change_quantity($product_id, $quantity, $attr_value) {
		
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $_SESSION ['cart'] ['products'] ) || empty ( $_SESSION ['cart'] ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		for($i = 0; $i < count ( $_SESSION ['cart'] ['products'] ); $i ++) {
			if (( int ) $_SESSION ['cart'] ['products'] [$i] ['product_id'] == ( int ) $product_id && $_SESSION ['cart'] ['products'] [$i] ['attr_value'] == $attr_value) {
				$_SESSION ['cart'] ['products'] [$i] ['quantity'] = $quantity;
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 返回购物车是否已经初始化
	 */
	private function _is_cart_initialized() {
		if (array_key_exists ( "products", $_SESSION ['cart'] ) && array_key_exists ( "products_total", $_SESSION ['cart'] ) && array_key_exists ( "shipping_fee", $_SESSION ['cart'] ) && array_key_exists ( "order_total", $_SESSION ['cart'] )) {
			return true;
		}
		return false;
	}
	
	/**
	 * 检查产品是否在购物车里面
	 *
	 * @param unknown_type $product        	
	 */
	private function _is_product_exits_in_cart($product) {
		
		// isset($product['Submit'])?unset($product['Submit']):'';
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $_SESSION ['cart'] ['products'] ) || empty ( $_SESSION ['cart'] ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $_SESSION ['cart'] ['products'] as $item ) {
			
			if (! isset ( $item ['product_id'] ) || ! isset ( $product ['product_id'] )) {
				continue;
			}
			
			if (( int ) $item ['product_id'] == ( int ) $product ['product_id'] && $item ['attr_value'] == $product ['attr_value']) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 检查产品是否在购物车里面
	 *
	 * @param unknown_type $product        	
	 */
	private function _is_product_exits_in_cart_by_id($product_id) {
		
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $_SESSION ['cart'] ['products'] ) || empty ( $_SESSION ['cart'] ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $_SESSION ['cart'] ['products'] as $item ) {
			if ($item ['product_id'] == $product_id) {
				return true;
			}
		}
		return false;
	}
	private function _is_product_exits_in_cart_by_id_attr_value($product_id, $attr_value) {
		
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $_SESSION ['cart'] ['products'] ) || empty ( $_SESSION ['cart'] ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $_SESSION ['cart'] ['products'] as $item ) {
			if ($item ['product_id'] == $product_id && $item ['attr_value'] == $attr_value) {
				return true;
			}
		}
		return false;
	}
	private function _get_product_by_id($product_id) {
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $_SESSION ['cart'] ['products'] ) || empty ( $_SESSION ['cart'] ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $_SESSION ['cart'] ['products'] as $item ) {
			if ($item ['product_id'] == $product_id) {
				return $item;
			}
		}
		return false;
	}
	function _get_product_by_id_attr_value($product_id, $attr_value) {
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $_SESSION ['cart'] ['products'] ) || empty ( $_SESSION ['cart'] ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $_SESSION ['cart'] ['products'] as $item ) {
			if ($item ['product_id'] == $product_id && $item ['attr_value'] == $attr_value) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 正式添加
	 * Enter description here .
	 */
	function _do_add_product($product) {
		
		// 这里需要根据product的id获取相应的产品的价格
		$product_obj = $this->_get_product_from_db_by_id ( $product ['product_id'] );
		$product ['is_shipping_free'] = $product_obj ['is_shipping_free'];
		$product ['is_promotion'] = $product_obj ['is_promotion'];
		$product ['promotion_start'] = $product_obj ['promotion_start'];
		$product ['promotion_end'] = $product_obj ['promotion_end'];
		$product ['promotion_price'] = $product_obj ['promotion_price'];
		$product ['product_price'] = $product_obj ['price'];
		$product ['should_pay_price'] = $product_obj ['price'];
		$product ['cata_path'] = $product_obj ['cata_path'];
		$product ['brand_id'] = $product_obj ['brand_id'];
		
		// 这里需要检查产品是否是优惠产品，如果是优惠产品的话，那么检查产品是否还在优惠期之内，如果在优惠期之内，按么这里的价格就应该是优惠价格
		if ($product_obj ['is_promotion'] == 1 && (date ( 'Y-m-d' ) >= $product_obj ['promotion_start']) && (date ( 'Y-m-d' ) <= $product_obj ['promotion_end'])) {
			$product ['product_price'] = $product_obj ['promotion_price'];
		}
		
		// 将产品信息添加到产品列表中
		$_SESSION ['cart'] ['products'] [] = $product;
	}
	
	// 从数据库里面获取产品的价格
	function _get_product_from_db_by_id($product_id) {
		
		// 这里还是需要获取是否有优惠价格
		global $db_conn;
		global $db_database_localhost;
		mysql_select_db ( $db_database_localhost );
		$query_product = "SELECT id,name,price,brand_id,cata_path,is_shipping_free,is_promotion,promotion_price,promotion_start,promotion_end FROM product WHERE id = " . $product_id;
		$product = mysql_query ( $query_product, $db_conn ) or die ( mysql_error ());
		$row_product = mysql_fetch_assoc ( $product );
		// $totalRows_product = mysql_num_rows($product);
		return $row_product;
	}
	
	/**
	 * 更新购物车里面这个产品的数量
	 *
	 * @param unknown_type $product        	
	 */
	function _update_product_quantity($product) {
		
		// 检查session中是否有产品，如果没有的话，那么直接返回false
		if (! isset ( $_SESSION ['cart'] ['products'] ) || count ( $_SESSION ['cart'] ['products'] ) == 0) {
			return false;
		}
		
		// 如果session中有产品，那么循环这些产品
		for($i = 0; $i < count ( $_SESSION ['cart'] ['products'] ); $i ++) {
			
			// 如果这个产品没有产品id的属性
			if (! isset ( $_SESSION ['cart'] ['products'] [$i] ['product_id'] )) {
				continue;
			}
			
			// 如果这个产品的id和循环中的产品的id相同
			if ($_SESSION ['cart'] ['products'] [$i] ['product_id'] == $product ['product_id'] && $_SESSION ['cart'] ['products'] [$i] ['attr_value'] == $product ['attr_value']) {
				$_SESSION ['cart'] ['products'] [$i] ['quantity'] = ( int ) $_SESSION ['cart'] ['products'] [$i] ['quantity'] + ( int ) $product ['quantity'];
				return true;
			}
		}
		
		// 如果找不到这个产品的话，那么直接返回false
		return false;
	}
	
	/**
	 * 删除这个产品
	 *
	 * @param unknown_type $product        	
	 */
	public function remove($product_id, $attr_value) {
		
		// 检查这个产品是否在cart中，如果不在的话，那么直接返回true
		if (! $this->_is_product_exits_in_cart_by_id_attr_value ( $product_id, $attr_value )) {
			return true;
		}
		
		// 如果在的话，那么将这个产品从购物车中移除
		$this->_do_remove_from_cart ( $product_id, $attr_value );
		
		// 更新总价
		$this->_update_products_total ();
		
		$this->_update_promotion_info_for_remove ();
		return true;
	}
	private function _update_fee_promotion($order = array()) {
		
		// 为了以后的扩展这里进行了初始化工作
		if ($order == array ()) {
			$order = $_SESSION;
		}
		
		// 更新产品总价
		$this->_update_products_total ( $order );
		
		// 更新运费
		$this->_update_shipping_fee ( $order );
		
		// 更新订单总价
		$this->_update_order_total ( $order );
		
		$this->_update_promotion_info_for_remove ( $order );
	}
	
	/**
	 * 更新购物车中的商品总价
	 * @return number
	 */
	function _update_promotion_info_for_remove($order = array()) {
		
		// 获取订单的费用
		if ($order == array ()) {
			$order = $_SESSION ['cart'];
		}
		
		$products = $order ['products']; // 获取订单的
		$products_fee = $order ['products_total']; // 获取所有的产品配用
		$shipping_fee = $order ['shipping_fee']; // 获取运费费用
		                                         
		// 这里需要将所有已经享受的促销删除
		$order ['promotion_id'] = "";
		
		// 获取订单的促销信息
		$promotion_fee_obj = $this->_123phpshop_get_promotion_fee ( $products_fee, $order ); // 获取促销费用数据
		$promotion_fee = $promotion_fee_obj ['fee']; // 获取促销的费用
		$promotion_presents = $promotion_fee_obj ['presents']; // 获取促销的赠品
		$promotion_fee_promotion_ids = $promotion_fee_obj ['promotion_ids'];
		
		// 获取订单可以享受到的促销总费用
		$order_total = $this->_123phpshop_get_order_total ( $products_fee, $shipping_fee, $promotion_fee ); // 获取订单的总费用
		                                                                                                    
		// 更新订单总额
		$this->_do_update_order_fee ( $shipping_fee, $products_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids ); // 更新db中的数据
		                                                                                                                          
		// 清除购物车中的赠品
		$this->_123phpshop_clear_order_presents ();
		
		// 将赠品添加到购物车中
		$this->_123phpshop_add_order_presents ( $order, $promotion_presents );
	}
	
	/**
	 * 从购物车中删除某个产品。
	 *
	 * @param unknown_type $product_id        	
	 */
	private function _do_remove_from_cart($product_id, $attr_value) {
		// 循环购物车中的所有产品，然后检查他们的产品id，如果当前的产品id和我们所需要的产品id是一致的话删除。
		for($i = 0; $i < count ( $_SESSION ['cart'] ['products'] ); $i ++) {
			if (( int ) $_SESSION ['cart'] ['products'] [$i] ['product_id'] == ( int ) $product_id && $_SESSION ['cart'] ['products'] [$i] ['attr_value'] == $attr_value) {
				unset ( $_SESSION ['cart'] ['products'] [$i] );
				break;
			}
		}
		sort ( $_SESSION ['cart'] ['products'] );
		return true;
	}
	
	/**
	 * 获取购物车数据
	 * Enter description here .
	 */
	public function get() {
		if (! isset ( $_SESSION ['cart'] )) {
			$this->_init_cart ();
		}
		return $_SESSION ['cart'];
	}
	
	/**
	 * 更新购物车里面的产品
	 * Enter description here .
	 */
	public function update() {
	}
	
	/**
	 * 清除购物车中的所有产品
	 * Enter description here .
	 */
	public function clear() {
		$this->_init_cart ();
	}
	
	/**
	 * 初始化购物车
	 */
	function _init_cart() {
		// 检查session是否开启，如果没有开启的话，那么开启session；
		if (! isset ( $_SESSION )) {
			session_start ();
		}
		$_SESSION ['cart'] = array (); // 初始化购物车
		$_SESSION ['cart'] ['promotion_id'] = array (); // 初始化可以享受的促销类型
		$_SESSION ['cart'] ['promotion_fee'] = 0.00; // 初始化可以享受的促销类型
		$_SESSION ['cart'] ['products'] = array (); // 初始化购物车中的商品
		$_SESSION ['cart'] ['products_total'] = 0.00; // 初始化购物车中的商品总额
		$_SESSION ['cart'] ['shipping_fee'] = 0.00; // 初始化购物车运费费用
		$_SESSION ['cart'] ['order_total'] = 0.00; // 初始化购物车订单总额
	}
	
	/**
	 * 更新购物车中的商品总价
	 *
	 * @return number
	 */
	function _update_products_total() {
		$product_total = 0.00;
		if (count ( $_SESSION ['cart'] ['products'] ) == 0) {
			return $product_total;
		}
		
		// 对订单中的每个产品的总价进行累加
		foreach ( $_SESSION ['cart'] ['products'] as $product ) {
			
			// 如果商品是赠品的话,那么直接跳过
			if (isset ( $product ['is_present'] ) && $product ['is_present'] == 1) {
				continue;
			}
			
			// 如果商品的价格没有设置的话
			if (! isset ( $product ['product_price'] )) {
				continue;
			}
			
			$price = $product ['product_price'];
			// 这里需要检查是否在促销区间之内，如果在促销期间之内那么商品的价格将会是促销价格
			if ($product ['is_promotion'] == "1" && (date ( 'Y-m-d' ) >= $product ['promotion_start']) && (date ( 'Y-m-d' ) <= $product ['promotion_end'])) {
				$price = $product ['promotion_price'];
			}
			// 商品的总价=商品价格×商品的数量
			$product_total += floatval ( $product ['product_price'] ) * $product ['quantity'];
		}
		
		// 更新商品的总价格
		$_SESSION ['cart'] ['products_total'] = $product_total;
	}
	
	/**
	 * 更新购物车中的运费
	 */
	function _update_shipping_fee() {
		require_once ($_SERVER ['DOCUMENT_ROOT'] . "/Connections/lib/order.php");
		$shipping_fee = get_shipping_fee ();
		$_SESSION ['cart'] ['shipping_fee'] = $shipping_fee ['shipping_fee'];
		$_SESSION ['cart'] ['shipping_method_id'] = $shipping_fee ['shipping_fee_plan'];
		return true;
	}
	
	// 更新运费
	function update_shipping_fee() {
		require_once ($_SERVER ['DOCUMENT_ROOT'] . "/Connections/lib/order.php");
		$shipping_fee = get_shipping_fee ();
		$_SESSION ['cart'] ['shipping_fee'] = $shipping_fee ['shipping_fee'];
		$_SESSION ['cart'] ['shipping_method_id'] = $shipping_fee ['shipping_fee_plan'];
		return true;
	}
	
	// 更新订单总价
	function _update_order_total() {
		return $_SESSION ['cart'] ['order_total'] = floatval ( $_SESSION ['cart'] ['shipping_fee'] ) + floatval ( $_SESSION ['cart'] ['products_total'] )- floatval ( $_SESSION ['cart'] ['promotion_fee'] );
	}
}