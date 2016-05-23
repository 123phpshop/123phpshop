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

require_once 'icart.php';
/**
 * 这个是购物车的抽象类，这个类的使用场景有：
 * 1.管理员后台添加订单
 * 2.注册用户前台添加订单功能-web
 * 3.未注册用户前台添加订单功能-web
 * 4.手机端用户或前台添加订单功能
 * 5.微信端用户前台添加订单功能
 *
 * @author thomas@123phpshop.com
 *        
 */
abstract class Cart implements ICart {
	protected $user_id;
	protected $token;
	protected $conn;
	protected $logger;
	
	/**
	 * 构造函数
	 */
	public function __construct($user_id, $token = null) {
		global $glogger; //
		$this->user_id = $user_id; // 操作用户的id
		$this->token = $token; // 手机端用户需要提交的token
		$this->cart = array (); // 初始化购物车数组
		$this->logger = $glogger; // 初始化日志
		
		if (false == $this->_is_cart_initialized ()) { // 这里检查购物测是否已经初始化，如果还没有初始化，那么初始化购物车
			$this->_init_cart (); // 初始化购物车
			$this->_load_cart_data (); // 加载购物车数据
		}
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see ICart::add_product()
	 */
	public function add_product($product) {
		$this->_add_product_to_this_cart ( $product ); // 将当前的商品添加到this->cart
		
		$this->_save_this_cart (); // 保存this->cart
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see ICart::remove_product()
	 */
	public function remove_product($product_id, $attr_value) {
		$this->_remove_product_from_this_cart ( $product_id, $attr_value ); // 将当前商品从this->cart中删除
		$this->_save_this_cart (); // 保存this->cart
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see ICart::get()
	 */
	public function get() {
		return $this->cart;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see ICart::clean_products()
	 */
	public function clean_products() {
		$this->_init_cart ();
		$this->_save_this_cart ();
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see ICart::get_products()
	 */
	public function get_products() {
		$this->logger->debug ( __METHOD__ . "获取购物车中所有商品开始" );
		$results = array ();
		if (count ( $this->cart ['products'] ) == 0) {
			return $results;
		}
		return $this->cart ['products'];
		$this->logger->debug ( __METHOD__ . "获取购物车中所有商品结束" );
	}
	
	/**
	 * 增加购物车中某产品的数量
	 *
	 * @param unknown_type $product_id        	
	 * @param unknown_type $quantity        	
	 */
	public function change_produt_quantity($product_id, $quantity, $attr_value) {
		$this->logger->debug ( __METHOD__ . "修改购物车中商品的数量开始" );
		
		$this->_change_product_quantity_for_this_cart ();
		
		$this->_save_this_cart (); // 保存this->cart
		
		$this->logger->debug ( __METHOD__ . "修改购物车中商品的数量结束" );
	}
	
	/**
	 * 从购物车模型中删除这个商品
	 *
	 * @param unknown $product_id        	
	 * @param unknown $attr_value        	
	 */
	private function _remove_product_from_this_cart($product_id, $attr_value) {
		// 检查这个产品是否在cart中，如果不在的话，那么直接返回true
		if (! $this->_is_product_exits_in_cart_by_id_attr_value ( $product_id, $attr_value )) {
			return true;
		}
		$this->_do_remove_from_cart ( $product_id, $attr_value ); // 如果在的话，那么将这个产品从购物车中移除
		
		$this->_recalculate_promotion_and_fee (); // 重新计算订单的费用和促销
	}
	
	/**
	 *
	 * @param unknown $product        	
	 */
	private function _add_product_to_this_cart($product) {
		$_is_product_exits_in_cart = $this->_is_product_exits_in_cart ( $product ); // 检查购物车中的是不是有这个商品
		if (! $_is_product_exits_in_cart) {
			$this->_do_add_cart_product ( $product ); // 如果购物车中没有该商品的话，那么直接进行添加
		} else {
			$this->_update_cart_product_quantity ( $product ); // 如果已经有了这个产品的话，那么需要增加这个商品的数量
		}
		$this->_recalculate_promotion_and_fee (); // 重新计算订单的费用和促销
	}
	
	/**
	 * 将this-》cart中的数据保存进入session或是数据库
	 */
	private function _save_this_cart() {
		
		// 删除数据库里面该用户该订单的所有信息
		$this->_clean_order_from_db ();
		
		// 将this->cart保存进入数据库里面
		$this->_do_save_this_cart ();
	}
	abstract function _clean_order_from_db(); // 删除数据库里面该用户该订单的所有信息
	abstract function _do_save_this_cart(); // 将this->cart保存进入数据库里面
	private function _recalculate_promotion_and_fee() {
		$this->_update_cart_products_total (); // 更新商品总价
		$this->_update_cart_promotion (); // 更新购物车中的促销信息
		$this->_update_cart_shipping_fee (); // 如果有赠品变动的话，那么需要更新运费
		$this->_update_cart_order_total (); // 更新购物车订单的总价格
	}
	
	/**
	 * 更新购物车模型中的某个商品的数量
	 *
	 * @param unknown $product_id        	
	 * @param unknown $quantity        	
	 * @param unknown $attr_value        	
	 * @throws Exception
	 */
	private function _change_product_quantity_for_this_cart($product_id, $quantity, $attr_value) {
		$this->logger->debug ( __METHOD__ . ' 更新购物车模型中的某个商品的数量开始' );
		
		// 检查产品是否存在，如果不存在，那么告知重新刷新页面
		$product = $this->_get_cart_product_by_id_attr_value ( $product_id, $attr_value );
		
		// $glogger->debug ( "修改购物车中商品的数量：获取商品" );
		
		if (! $product) {
			$this->logger->fatal ( __METHOD__ . "修改购物车中商品的数量：获取不到商品：" );
			throw new Exception ( "商品不存在，请刷新页面后重试" );
		}
		
		// 如果都ok的话，那么这个产品的数量+1，然后返回更新后的数据
		if (! $this->_do_change_cart_quantity ( $product_id, $quantity, $attr_value )) {
			$this->logger->fatal ( __METHOD__ . "修改购物车中商品的数量：_do_change_quantity操作失败" );
			throw new Exception ( "系统错误，请稍后重试" );
		}
		
		$this->_recalculate_promotion_and_fee();
		
		$this->logger->debug ( __METHOD__ . ' 更新购物车模型中的某个商品的数量结束' );
	}
	
	
	abstract protected function _load_cart_data(); // 加载订单数据
	
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
			
			// 检查用户已经享受了这个促销
			$_promotion_in_order = in_array ( $promotion_plan ['id'], $promotion_ids_array );
			$this->logger->debug ( "检查用户是否已经享受了这个促销：" );
			
			// 如果用户没有享受过这个促销，而且具有享受这个促销的条件，那么直接添加
			if ($promotion_plan ['promotion_limit'] == "1") {
				
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $product_fee );
				
				// 如果是全场的话，商品的总费用没有达到享受促销所需要的费用，但是订单有这个促销的话，那么将这个促销删除
				if (( float ) $product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) {
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					continue;
				}
				
				// 如果用户已经享受过这个促销的话
				if ($_promotion_in_order) {
					$this->logger->debug ( "用户是否已经享受了这个促销：跳过。。。" );
					continue;
				}
				
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
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $catalog_product_fee );
				
				// 如果指定分类的商品的总额《参与促销活动的最低的金额的话，那么说明用户无权享受这个促销，那么需要退出
				if (( float ) $catalog_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) { // 如果用户已经享受过这个促销的话，那么需要收回这个促销
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					// 进行下一个
					continue;
				}
				
				// 如果用户已经享受过这个促销的话，那么直接循环下一个
				if ($_promotion_in_order) {
					continue;
				}
				
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
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $brand_product_fee );
				// 如果这个品牌的商品的总额《参与促销活动的最低的金额的话
				if (( float ) $brand_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) {
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					continue;
				}
				
				// 如果用户已经享受过这个促销的话，那么直接循环下一个
				if ($_promotion_in_order) {
					continue;
				}
				
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
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $product_product_fee );
				// 如果指定商品的总额《参与促销活动的最低的金额的话
				if (( float ) $product_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) {
						// 那么将这个促销从订单中删除
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					continue;
				}
				
				// 如果用户已经享受过这个促销的话，那么直接循环下一个
				if ($_promotion_in_order) {
					continue;
				}
				
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
	 * 删除促销信息
	 *
	 * @param unknown $promotion        	
	 * @param unknown $promotion_fee_presents        	
	 * @param array $order        	
	 */
	private function _unset_promotion($promotion, $promotion_fee_presents, $order = array()) {
		$this->logger->debug ( "删除促销开始" );
		if ($order == array ()) {
			$order = $this->cart;
		}
		
		// 从订单中删除这个促销的id
		$promotion_ids = $this->_123phpshop_remove_promotion_id_from_order ( $order, $promotion ['id'] );
		$this->logger->debug ( "删除促销：当前订单的促销id：" . $promotion_ids );
		
		// 重新更新订单的id
		$this->_123phpshop_update_order_promotion_id ( $order, $promotion_ids );
		
		// 更新订单中的赠品或金额
		switch ($promotion ['promotion_type']) {
			case "1" : // 如果是满增的话
				$presents_ids = $this->_remove_presents_id_from_order ( $order, $promotion_fee_presents ['presents'] );
				break;
			case "2" : // 如果是满减的话
				$promotion_fee = floatval ( $order ['promotion_fee'] ) - floatval ( $promotion_fee_presents ['fee'] );
				$this->_123phpshop_update_order_promotion_fee ( $promotion_fee, $order );
				break;
		}
		
		$this->_123phpshop_update_order_total ();
	}
	
	/**
	 * 从订单中删除订单的促销id
	 *
	 * @param unknown $order        	
	 * @param unknown $promotion_id        	
	 */
	private function _123phpshop_remove_promotion_id_from_order($order, $promotion_id) {
		$result = array ();
		
		foreach ( explode ( ",", $order ['promotion_id'] ) as $promotion_id_item ) {
			if (( int ) $promotion_id_item != ( int ) $promotion_id) {
				$result [] = $promotion_id_item;
			}
		}
		
		if (count ( $result ) == 0) {
			$result = '';
		}
		$result = implode ( ",", $result );
		return $result;
	}
	
	/**
	 * 更新订单的促销id
	 *
	 * @param unknown $order        	
	 * @param unknown $promotion_ids        	
	 */
	private function _123phpshop_update_order_promotion_id($order, $promotion_ids) {
		$this->cart ['promotion_id'] = $promotion_ids;
	}
	
	/**
	 * 从订单中删除这些赠品
	 *
	 * @param unknown $presents_ids        	
	 * @param array $order        	
	 */
	private function _remove_presents_id_from_order($presents_ids, $order = array()) {
		$result = array ();
		foreach ( $this->cart ['products'] as $promotion_id_item ) {
			if ($promotion_id_item ['is_present'] == "0" && 　 ( ( int ) $promotion_id_item != ( int ) $presents_ids )) {
				$result [] = $promotion_id_item;
			}
		}
		$this->cart ['products'] = $result;
	}
	/**
	 * 更新订单的促销费用
	 *
	 * @param unknown $promotion_fee        	
	 * @param array $order        	
	 */
	private function _123phpshop_update_order_promotion_fee($promotion_fee, $order = array()) {
		$this->cart ['promotion_fee'] = floatval ( $promotion_fee );
	}
	
	/**
	 * 更新订单的总额
	 *
	 * @param unknown $order        	
	 */
	private function _123phpshop_update_order_total($order) {
		if ($order == array ()) {
			$shipping_fee = $this->cart ['shipping_fee'];
			$products_fee = $this->cart ['products_fee'];
			$promotion_fee = $this->cart ['promotion_fee'];
			$order_total = floatval ( $products_fee ) + floatval ( $shipping_fee ) - floatval ( $promotion_fee );
			$this->cart ['order_total'] = $order_total;
			return;
		}
		$shipping_fee = $order ['shipping_fee'];
		$products_fee = $order ['products_fee'];
		$promotion_fee = $order ['promotion_fee'];
		$order_total = floatval ( $products_fee ) + floatval ( $shipping_fee ) - floatval ( $promotion_fee );
		return $order ['order_total'] = $order_total;
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
			if ($this->cart ['products_total'] > $promotion_plan ['amount_lower_limit']) {
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
				$this->cart ['promotion_presents'] [] = $product;
			}
		}
	}
	
	// 更新购物车中的促销费用
	private function _update_promotion_fee($promotion_plan) {
		
		// 如果是满减的话，那么直接+即可
		if ($promotion_plan ['promotion_type'] == 2) {
			$this->cart ['promotion_fee'] += $promotion_plan ['promotion_type_val'];
		}
		
		// 如果是满折扣的话，那么需要计算产品的金额，然后计算折扣的金额
		if ($promotion_plan ['promotion_type'] == 3) {
			$this->cart ['promotion_fee'] += $this->cart ['products_total'] * $promotion_plan ['promotion_type_val'] / 100;
		}
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
		if (! isset ( $this->cart ['products'] ) || empty ( $this->cart ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		for($i = 0; $i < count ( $this->cart ['products'] ); $i ++) {
			if (( int ) $this->cart ['products'] [$i] ['product_id'] == ( int ) $product_id && $this->cart ['products'] [$i] ['attr_value'] == $attr_value) {
				$this->cart ['products'] [$i] ['quantity'] = $quantity;
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 返回购物车是否已经初始化
	 */
	private function _is_cart_initialized() {
		if (array_key_exists ( "products", $this->cart ) && array_key_exists ( "products_total", $this->cart ) && array_key_exists ( "shipping_fee", $this->cart ) && array_key_exists ( "order_total", $this->cart )) {
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
		if (! isset ( $this->cart ['products'] ) || empty ( $this->cart ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $this->cart ['products'] as $item ) {
			
			// 如果商品的id不存在的话，那么直接跳出
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
		if (! isset ( $this->cart ['products'] ) || empty ( $this->cart ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $this->cart ['products'] as $item ) {
			if ($item ['product_id'] == $product_id) {
				return true;
			}
		}
		return false;
	}
	private function _is_product_exits_in_cart_by_id_attr_value($product_id, $attr_value) {
		
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $this->cart ['products'] ) || empty ( $this->cart ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $this->cart ['products'] as $item ) {
			if ($item ['product_id'] == $product_id && $item ['attr_value'] == $attr_value) {
				return true;
			}
		}
		return false;
	}
	private function _get_product_by_id($product_id) {
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $this->cart ['products'] ) || empty ( $this->cart ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $this->cart ['products'] as $item ) {
			if ($item ['product_id'] == $product_id) {
				return $item;
			}
		}
		return false;
	}
	/**
	 *
	 * @param unknown $product_id        	
	 * @param unknown $attr_value        	
	 */
	private function _get_product_by_id_attr_value($product_id, $attr_value) {
		// 如果没有设置过产品的session信息，或者是设置过产品的session信息但是里面没有产品的话，那么直接返回false
		if (! isset ( $this->cart ['products'] ) || empty ( $this->cart ['products'] )) {
			return false;
		}
		
		// 循环里面的每一个产品
		foreach ( $this->cart ['products'] as $item ) {
			if ($item ['product_id'] == $product_id && $item ['attr_value'] == $attr_value) {
				return $item;
			}
		}
		return false;
	}
	
	/**
	 * 将商品添加到购物车中
	 * Enter description here .
	 */
	private function _do_add_cart_product($product) {
		
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
		$this->cart ['products'] [] = $product;
	}
	
	// 从数据库里面获取产品的价格
	protected function _get_product_from_db_by_id($product_id) {
		
		// 这里还是需要获取是否有优惠价格
		global $db_conn;
		global $db_database_localhost;
		mysql_select_db ( $db_database_localhost );
		$query_product = "SELECT id,name,price,brand_id,cata_path,is_shipping_free,is_promotion,promotion_price,promotion_start,promotion_end FROM product WHERE id = " . $product_id;
		$product = mysql_query ( $query_product, $db_conn ) or die ( mysql_error () );
		$row_product = mysql_fetch_assoc ( $product );
		// $totalRows_product = mysql_num_rows($product);
		return $row_product;
	}
	
	/**
	 * 更新购物车里面这个产品的数量
	 *
	 * @param unknown_type $product        	
	 */
	protected function _update_cart_product_quantity($product) {
		
		// 检查session中是否有产品，如果没有的话，那么直接返回false
		if (! isset ( $this->cart ['products'] ) || count ( $this->cart ['products'] ) == 0) {
			return false;
		}
		
		// 如果session中有产品，那么循环这些产品
		for($i = 0; $i < count ( $this->cart ['products'] ); $i ++) {
			
			// 如果这个产品没有产品id的属性
			if (! isset ( $this->cart ['products'] [$i] ['product_id'] )) {
				continue;
			}
			
			// 如果这个产品的id和循环中的产品的id相同
			if ($this->cart ['products'] [$i] ['product_id'] == $product ['product_id'] && $this->cart ['products'] [$i] ['attr_value'] == $product ['attr_value']) {
				$this->cart ['products'] [$i] ['quantity'] = ( int ) $this->cart ['products'] [$i] ['quantity'] + ( int ) $product ['quantity'];
				return true;
			}
		}
		
		// 如果找不到这个产品的话，那么直接返回false
		return false;
	}
	
	/**
	 * 更新促销费用
	 *
	 * @param array $order        	
	 */
	protected function _update_fee_promotion($order = array()) {
		
		// 为了以后的扩展这里进行了初始化工作
		if ($order == array ()) {
			$order = $_SESSION;
		}
		
		// 更新产品总价
		$this->_update_products_total ( $order );
		
		// 更新促销信息
		$this->_update_promotion ( $order );
	}
	
	/**
	 * 更新购物车中的商品总价
	 *
	 * @return number
	 */
	protected function _update_cart_promotion($order = array()) {
		
		// 获取订单的费用
		if ($order == array ()) {
			$order = $this->cart;
		}
		
		$products = $order ['products']; // 获取订单的
		$products_fee = $order ['products_total']; // 获取所有的产品配用
		                                           
		// 如果购物车中没有商品的话，那么直接初始化即可
		if (count ( $products ) == 0) {
			$this->_init_cart ();
			return;
		}
		
		// 获取订单的促销信息
		$promotion_fee_obj = $this->_123phpshop_get_promotion_fee ( $products_fee, $order ); // 获取促销费用数据
		$promotion_fee = $promotion_fee_obj ['fee']; // 获取促销的费用
		$promotion_presents = $promotion_fee_obj ['presents']; // 获取促销的赠品
		$promotion_fee_promotion_ids = $promotion_fee_obj ['promotion_ids'];
		
		// 将赠品添加到购物车中
		$this->_123phpshop_add_order_presents ( $promotion_presents );
		
		// 因为有赠品添加进入，所以这里需要更新运费
		// $this->_update_shipping_fee ();
		// $shipping_fee = $this->cart ['shipping_fee']; // 获取运费费用
		
		// 获取订单总费用
		// $order_total = $this->_123phpshop_get_order_total ( $products_fee, $shipping_fee, $promotion_fee ); // 获取订单的总费用
		
		// 更新订单总额
		// $this->_do_update_order_fee ( $shipping_fee, $products_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids ); // 更新db中的数据
	}
	
	/**
	 * 清除订单中的促销信息
	 *
	 * @param array $order        	
	 */
	protected function _clear_promotion($order = array()) {
		
		// 如果订单的为空的话，那么直接操作session
		if ($order == array ()) {
			$this->cart ['promotion_id'] = array (); // 清除促销金额
			$this->cart ['order_total'] = floatval ( $this->cart ['order_total'] ) + floatval ( $this->cart ['promotion_fee'] ); // 清楚
			$this->cart ['promotion_fee'] = 0.00; // 清楚
			$this->_123phpshop_clear_cart_presents ();
		}
		
		return $order;
	}
	
	/**
	 * 从购物车模型中删除某个产品。
	 *
	 * @param unknown_type $product_id        	
	 */
	private function _do_remove_from_cart($product_id, $attr_value) {
		// 循环购物车中的所有产品，然后检查他们的产品id，如果当前的产品id和我们所需要的产品id是一致的话删除。
		for($i = 0; $i < count ( $this->cart ['products'] ); $i ++) {
			if (( int ) $this->cart ['products'] [$i] ['product_id'] == ( int ) $product_id && $this->cart ['products'] [$i] ['attr_value'] == $attr_value) {
				unset ( $this->cart ['products'] [$i] );
				break;
			}
		}
		sort ( $this->cart ['products'] );
		return true;
	}
	
	/**
	 * 更新购物车模型中的商品总价
	 *
	 * @return number
	 */
	protected function _update_cart_products_total() {
		$this->logger->debug ( __CLASS__ . " 开始更新购物车中的商品总价" );
		
		$product_total = 0.00;
		if (count ( $this->cart ['products'] ) == 0) {
			return $product_total;
		}
		
		// 对订单中的每个产品的总价进行累加
		foreach ( $this->cart ['products'] as $product ) {
			
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
		$this->cart ['products_total'] = $product_total;
	}
	
	// 更新运费
	private function _update_cart_shipping_fee() {
		require_once ($_SERVER ['DOCUMENT_ROOT'] . "/Connections/lib/order.php");
		$shipping_fee = get_shipping_fee ();
		// $this->logger->debug ( "更新运费shipping_fee:" . $shipping_fee ['shipping_fee'] );
		// $this->logger->debug ( "更新运费shipping_fee_plan:" . $shipping_fee ['shipping_fee_plan'] );
		$this->cart ['shipping_fee'] = $shipping_fee ['shipping_fee'];
		$this->cart ['shipping_method_id'] = $shipping_fee ['shipping_fee_plan'];
		return true;
	}
	
	// 更新订单总价
	private function _update_cart_order_total() {
		$this->cart ['order_total'] = floatval ( $this->cart ['shipping_fee'] ) + floatval ( $this->cart ['products_total'] ) - floatval ( $this->cart ['promotion_fee'] );
		$this->logger->debug ( "更新订单总价:" . $this->cart ['order_total'] );
		return $this->cart ['order_total'];
	}
	
	/**
	 * 清除购物车中的赠品
	 */
	protected function _123phpshop_clear_cart_presents() {
		$result = array ();
		// 如果购物车中没有商品的话，那么直接返回
		if (count ( $this->cart ['products'] ) == 0) {
			return true;
		}
		
		// 如果有商品的话，那么循环这些商品，然后将不是赠品的商品调出来
		foreach ( $this->cart ['products'] as $product ) {
			if (! isset ( $product ['is_present'] ) || $product ['is_present'] == "0" || $product ['is_present'] == 0) {
				$result [] = $product;
			}
		}
		
		// 如果购物车里面全都是赠品的话，那么直接返回
		if (count ( $result ) == 0) {
			$this->cart ['products'] = array ();
			return;
		}
		
		// 如果里面有商品的话，那么直接将session
		$this->cart ['products'] = $result;
	}
	
	/**
	 * 将促销生成的赠品添加到订单中
	 *
	 * @param unknown $order        	
	 * @param unknown $promotion_presents        	
	 */
	protected function _123phpshop_add_order_presents($promotion_presents) {
		// 如果赠品的数量为0的话，那么直接退出
		if (count ( $promotion_presents ) == 0) {
			return;
		}
		
		// 如果》0的话，那么循环这些赠品，
		foreach ( $promotion_presents as $product_id ) {
			$product = $this->_get_product_from_db_by_id ( $product_id );
			$product ['product_price'] = 0.00;
			$product ['should_pay_price'] = 0.00;
			$product ['product_id'] = $product ['id'];
			$product ['is_present'] = 1;
			$product ['quantity'] = 1;
			$product ['product_image'] = $this->_get_product_image_by_id ( $product ['id'] );
			$this->cart ['products'] [] = $product;
		}
	}
	
	/**
	 * 通过商品的id获取这个商品的图片
	 *
	 * @param unknown $product_id        	
	 */
	protected function _get_product_image_by_id($product_id) {
		$result = "";
		global $db_conn;
		global $db_database_localhost;
		mysql_select_db ( $db_database_localhost );
		$query_get_product_image = "SELECT * FROM product_images WHERE product_id = $product_id and is_delete=0 limit 1";
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
	protected function _123phpshop_get_order_total($product_fee, $shipping_fee, $promotion_fee) {
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
	protected function _do_update_order_fee($shipping_fee, $product_fee, $promotion_fee, $order_total, $promotion_fee_promotion_ids) {
		$this->cart ['cart'] ['order_total'] = $order_total; // 更新购物车中的订单总额
		$this->cart ['cart'] ['products_total'] = $product_fee; // 更新购物车中的产品费用
		$this->cart ['cart'] ['shipping_fee'] = $shipping_fee; // 更新购物车中的运费
		$this->cart ['cart'] ['promotion_fee'] = $promotion_fee; // 更新购物车中的运费
		$this->cart ['promotion_id'] = $promotion_fee_promotion_ids; // 更新购物车中的促销计划的ids
	}
	
	/**
	 * 初始化购物车
	 */
	protected function _init_cart() {
		// $this->logger->debug ( "数据库购物车初始化" );
		
		// 检查session是否开启，如果没有开启的话，那么开启session；
		$this->cart = array (); // 初始化购物车
		$this->cart ['order_id'] = 0;
		$this->cart ['promotion_id'] = array (); // 初始化可以享受的促销类型
		$this->cart ['promotion_fee'] = 0.00; // 初始化可以享受的促销类型
		$this->cart ['products'] = array (); // 初始化购物车中的商品
		$this->cart ['products_total'] = 0.00; // 初始化购物车中的商品总额
		$this->cart ['shipping_fee'] = 0.00; // 初始化购物车运费费用
		$this->cart ['order_total'] = 0.00; // 初始化购物车订单总额
	}
}