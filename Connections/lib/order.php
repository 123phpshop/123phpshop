<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
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
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php


/**
 * 生成订单序列号
 *
 * @return string
 */
function gen_order_sn() {
	$sn = date ( "YmdHis" ) . rand ( 10000000, 99999999 );
	return $sn;
}

/**
 * 在订单中添加产品
 *
 * @param
 *        	$order
 * @param
 *        	$product
 */
function phpshop123_order_add_product($order, $product) {
	
	// 检查订单是否已经设置了收货人信息，如果没有的话，那么告知无法添加
	if (! isset ( $order ['consignee_province'] ) || ! isset ( $order ['consignee_city'] ) || ! isset ( $order ['consignee_district'] )) {
		throw new Exception ( "收货人没有设置，请首先设置收货人信息,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 首先检查商品是否在配送范围之内，如果没有在配送范围之内的话，那么告知无法配送
	if (! _123phpshop_product_could_ship ( $order )) {
		throw new Exception ( "不在配送范围之内，请重新选择或添加订单用户的收货地址,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 如果在配送范围之内的话，那么检查订单中是否已经有了这个商品,
	$product_is_present = isset ( $product ['is_present'] ) ? true : false; // 判断产品是否是赠品
	$product_in_order = _123phpshop_product_is_in_order ( $order, $product ); // 检查订单中是否有这个产品
	$order_product_is_present = $product_in_order ['is_present'] == "1" ? true : false; // 订单中已有的产品是否是赠品
	                                                                                    
	// 1. 如果订单中没有这个商品，并且商品是赠品的话,那么直接添加商品
	if (! $product_in_order && $product_is_present) {
		$iorder = _123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录
		phpshop123_update_order_fee ( $iorder ); // 直接插入当前产品记录
		return true;
	}
	
	// 2. 如果订单中没有这个商品,而且当前商品不属于赠品的话，那么添加商品然后更新订单的费用和促销信息
	if (! $product_in_order && ! $product_is_present) {
		$iorder = _123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录
		phpshop123_update_order_fee ( $iorder ); // 更新订单的费用和促销信息
		return;
	}
	
	// 3. 如果订单中有这个商品，且当前的商品和之前的商品都是赠品的话，那么直接将之前商品的数量+1即可
	if ($product_in_order && $product_is_present && $order_product_is_present) {
		// 那么直接将商品的数量+1即可
		_123phpshop_order_update_product_quantity ( $order, $product_in_order, ( int ) $product ['quantity'] ); // 将订单中之前的商品数量+1
		return;
	}
	
	// 4. 如果订单中有这个商品，当前的产品和之前的商品都不是赠品的话，
	if ($product_in_order && ! $product_is_present && ! $order_product_is_present) {
		_123phpshop_order_update_product_quantity ( $order, $product_in_order, ( int ) $product ['quantity'] ); // 将之前的产品的数量+1
		$iorder = get_order_full_by_id ( $order ['id'] );
		phpshop123_update_order_fee ( $iorder ); // 更新订单的费用和促销信息
		return;
	}
	
	// 5. 如果订单中有这个商品，且当前的商品为赠品，但是之前的产品不是赠品的话，那么直接插入记录，不需要更新费用
	if ($product_in_order && $product_is_present && ! $order_product_is_present) {
		_123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录即可
		return;
	}
	
	// 6. 如果订单中有这个商品，当前的产品不是赠品，但是之前的商品属于赠品的话
	if ($product_in_order && ! $product_is_present && $order_product_is_present) {
		$iorder = _123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录即可
		phpshop123_update_order_fee ( $iorder ); // 更新订单的费用和促销信息
		return;
	}
}
/**
 * 获取格式化的订单数组
 *
 * @param unknown $id        	
 */
function get_order_full_by_id($id) {
	$order = _123phpshop_get_order_by_id ( $id );
	if (! $order) {
		throw new Exception ( "订单不存在！欢迎联系123phpshop.com寻求解决方案" );
	}
	
	$order_items = _123phpshop_get_order_items_by_id ( $id );
	$order ['products'] = $order_items;
	return $order;
}

/**
 * 根据id获取订单
 *
 * @param unknown $id        	
 */
function _123phpshop_get_order_by_id($id) {
	global $db_conn;
	global $db_database_localhost;
	
	$query_order = sprintf ( "SELECT * FROM orders WHERE id = %s and is_delete=0 ", $id );
	$order = mysqli_query($db_conn,$query_order ) or die ( mysqli_error ($localhost).$query_order);
	$totalRows_order = mysqli_num_rows ( $order );
	if ($totalRows_order == 0) {
		return false;
	}
	
	return mysqli_fetch_assoc ( $order );
}

/**
 * 根据id获取订单下面的产品
 *
 * @param unknown $id        	
 */
function _123phpshop_get_order_items_by_id($id) {
	global $db_conn;
	global $db_database_localhost;
	
	$query_order = sprintf ( "SELECT order_item.*,product.brand_id,product.is_shipping_free,product.cata_path FROM order_item inner join product on product.id=order_item.product_id WHERE order_item.order_id = %s and order_item.is_delete=0 ", $id );
	$order = mysqli_query($db_conn ,$query_order) or die ( mysqli_error ($localhost).$query_order);
	$totalRows_order = mysqli_num_rows ( $order );
	while ( $row_order = mysqli_fetch_assoc ( $order ) ) {
		$result [] = $row_order;
	}
	
	return $result;
}
function _123phpshop_order_product_is_present($product) {
	if (! isset ( $product ['is_present'] )) {
		return false;
	}
	return ($product ['is_present'] == '1' || $product ['is_present'] == 1);
}

/**
 * 检查产品是否可以被配送
 *
 * @param unknown $product        	
 * @return boolean
 */
function _123phpshop_product_could_ship($order) {
	$area = array ();
	$area [] = $order ['consignee_province'] . "_*_*";
	$area [] = $order ['consignee_province'] . "_" . $order ['consignee_city'] . "_*";
	$area [] = $order ['consignee_province'] . "_" . $order ['consignee_city'] . "_" . $order ['consignee_district'];
	require_once ($_SERVER ['DOCUMENT_ROOT'] . '/Connections/lib/product.php');
	return could_devliver ( $area );
}

/**
 * 检查订单中是否已经存在存在这个商品了
 *
 * @param
 *        	　 $order
 * @param
 *        	　 $product
 */
function _123phpshop_product_is_in_order($order, $product) {
	foreach ( $order ['products'] as $product_item ) {
		if ($product_item ['product_id'] == $product ['product_id'] && $product_item ['attr_value'] == $product ['attr_value']) {
			return $product_item;
		}
	}
	return false;
}

/**
 * 更新产品数量
 * 
 * @param unknown $order        	
 * @param unknown $product        	
 * @param unknown $quantity        	
 * @return mixed
 */
function _123phpshop_order_update_product_quantity($order, $product, $quantity) {
	global $db_conn;
	global $db_database_localhost;
	
	$sql = "update order_item set quantity=quantity+" . $quantity . " where order_id=" . $order ['id'] . " and product_id=" . $product ['product_id'];
	return mysqli_query ($localhost,$sql );
}

/**
 * 向订单中添加商品
 *
 * @param unknown $order        	
 * @param unknown $product        	
 * @return boolean
 */
function _123phpshop_order_do_add_product($order, $product) {
	global $db_conn;
	$should_pay_price = _123phpshop_get_price_by_product_id ( $product ['product_id'] );
	
	$is_present = 0;
	
	// 如果是赠品的话
	if (isset ( $product ['is_present'] )) {
		$should_pay_price = 0.00;
		$is_present = 1;
	}
	
	$insertSQL = sprintf ( "INSERT INTO order_item (should_pay_price,order_id,product_id, quantity, attr_value, is_present) VALUES (%s,%s, %s,%s, %s, %s)", GetSQLValueString ( $should_pay_price, "double" ), GetSQLValueString ( $order ['id'], "int" ), GetSQLValueString ( $product ['product_id'], "int" ), GetSQLValueString ( $product ['quantity'], "int" ), GetSQLValueString ( $product ['attr_value'], "text" ), GetSQLValueString ( $is_present, "int" ) );
	$new_order_item_query = mysqli_query($db_conn,$insertSQL);
	if (! $new_order_item_query) {
		throw new Exception ( "系统错误，请联系123phpshop.com寻求解决方案" );
	}
	
	return get_order_full_by_id ( $order ['id'] );
}

/**
 * 更新订单中的费用和促销信息
 *
 * @param unknown $order        	
 * @return boolean
 */
function _123phpshop_order_update_fee_promotion($order) {
	$result = true;
	_123phpshop_update_products_total ( $order ); // 更新产品总价
	_123phpshop_update_shipping_fee ( $order ); // 更新运费总价格
	_123phpshop_update_order_total ( $order ); // 更新订单总价
	_123phpshop_update_promotion ( $order ); // 更新促销总价
	return $result;
}

/**
 * 更新订单中产品的总价
 *
 * @param unknown $order        	
 * @return number
 */
function _123phpshop_update_products_total($order) {
	$result = 0.00;
	// 循环订单中的所有商品
	foreach ( $order ['products'] as $product ) {
		
		// 如果商品是赠品的话，那么不计入总计
		
		// 如果商品处于优惠期之内的话，那么按照优惠价格进行计算
	}
	return $result = 0.00;
}

/**
 * 通过产品的id获取这个产品的记录
 *
 * @param unknown $id        	
 */
function _123phpshop_get_product_by_id($id) {
	global $db_conn;
	global $db_database_localhost;
	
	$query_sql = sprintf ( "SELECT * FROM product WHERE id = %s", $id );
	$query = mysqli_query($db_conn,$query_sql ) or die ( mysqli_error ($localhost).$query_sql);
	$result = mysqli_fetch_assoc ( $query );
	$totalRows_order = mysqli_num_rows ( $query );
	if ($totalRows_order == 0) {
		return false;
	}
	return $result;
}

/**
 * 通过这个产品的id获取这个产品的哦价格
 *
 * @param unknown $id        	
 */
function _123phpshop_get_price_by_product_id($id) {
	$result = false;
	$product = _123phpshop_get_product_by_id ( $id );
	if (! $product) {
		return $result;
	}
	
	$curr_date = date ( "Y-m-d" );
	// 检查产品是否在优惠期之内，如果在优惠期之内，那么产品的价格就是优惠价格
	if ($product ['is_promotion'] == 1 && $curr_date >= $product ['promotion_start'] && $curr_date <= $product ['promotion_end']) {
		$result = $product ['promotion_price'];
	} else {
		$result = $product ['price'];
	}
	
	return $result;
}

// 更新运费总价格
function _123phpshop_update_shipping_fee($order) {
}

// 更新订单总价
function _123phpshop_update_order_total($order) {
}
// 更新促销总价
function _123phpshop_update_promotion($order) {
}

// 更新产品总价
function _123phpshop_get_products_total($order) {
	$result = 0.00;
	foreach ( $order ['products'] as $item ) {
		if ($item) {
			// 首先检查是否是赠品，如果是赠品的话，那么不计入总的产品价格
			// 如果不是赠品，那么检查是否可以使用优惠价格，如果可以应用优惠价格的话，那么使用优惠价格
		}
	}
}

/**
 * 删除订单中的某项产品
 *
 * @param unknown $order        	
 * @param unknown $product        	
 */
function phpshop123_order_remove_product($order, $product) {
	_do_order_remove_product ( $order, $product );
	_123phpshop_order_update_fee_promotion ( $order ); // 更新订单的费用和促销信息
}

/**
 * 更新订单的费用和促销信息
 *
 * @param unknown $order_id        	
 */
function phpshop123_order_update_fee_promotion($order_id) {
	$order = _123phpshop_get_order_by_id ( $order_id );
	$products = _123phpshop_get_order_items_by_id ( $order_id );
	$order ['products'] = $products;
	_123phpshop_update_products_total ( $order ); // 更新产品总价
	_123phpshop_update_shipping_fee ( $order ); // 更新运费总价格
	_123phpshop_update_order_total ( $order ); // 更新订单总价
	_123phpshop_update_promotion ( $order ); // 更新促销总价
}

/**
 * 从数据库中删除这个商品
 *
 * @param unknown $order        	
 * @param unknown $product        	
 */
function _do_order_remove_product($order, $product) {
	$sql = "";
}

/**
 * 更新订单中的产品数量
 *
 * @param unknown $order        	
 * @param unknown $product        	
 * @param unknown $quantity        	
 */
function phpshop123_order_update_product_quantity($order, $product, $quantity) {
}

/**
 * 初始化一个订单数组
 *
 * @return number[]
 */
function _123phpshop_init_order() {
	$order = array (); // 初始化购物车
	$order ['present_products'] = array (); // 初始化赠送的产品
	$order ['promotions'] = array (); // 初始化可以享受的促销类型
	$order ['promotion_fee'] = 0.00; // 初始化可以享受的促销类型
	$order ['products'] = array (); // 初始化购物车中的商品
	$order ['products_total'] = 0.00; // 初始化购物车中的商品总额
	$order ['shipping_fee'] = 0.00; // 初始化购物车运费费用
	$order ['order_total'] = 0.00; // 初始化购物车订单总额
	$order ['consingee_id'] = 0; // 初始化订单收货人的id
	$order ['consingee_name'] = ''; // 初始化购物车订单总额
	$order ['consingee_province'] = ''; // 初始化购物车订单总额
	$order ['consingee_city'] = ''; // 初始化购物车订单总额
	$order ['consingee_district'] = ''; // 初始化购物车订单总额
	$order ['consingee_address'] = ''; // 初始化购物车订单总额
	return $order;
}
function _phpshop123_init_product() {
}

global $db_conn;

/**
 * 获取购物车中产品的运费
 */
function get_shipping_fee($order = array()) {
	
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
 * 获取购物车中的产品的重量
 */
function _get_order_weight($products = array()) {
	$result = 0;
	if ($products == array ()) {
		foreach ( $_SESSION ['cart'] ['products'] as $product ) {
			if ($product ['is_shipping_free'] == '0' || $product ['is_shipping_free'] == 0) { // 如果不是免运费的话
				$product_obj = _get_product_by_id ( $product ['product_id'] );
				if (false == $product_obj) {
					throw new Excaption ( "产品不存在或者已经删除" );
				}
				$result += $product_obj ['weight'] * $product ['quantity']; // 商品总重=商品数量×商品重量
			}
		}
		return $result;
	}
	
	foreach ( $products as $product ) {
		if ($product ['is_shipping_free'] == '0' || $product ['is_shipping_free'] == 0) { // 如果不是免运费的话
			$product_id = isset ( $product ['product_id'] ) ? $product ['product_id'] : $product ['id'];
			$product_obj = _get_product_by_id ( $product_id );
			if (false == $product_obj) {
				throw new Exception ( "产品不存在或者已经删除" );
			}
			$result += $product_obj ['weight'] * $product ['quantity']; // 商品总重=商品数量×商品重量
		}
	}
	return $result;
}

/**
 * 获取购物车中产品的数量
 */
function _get_order_quantity($products = array()) {
	$result = 0;
	
	foreach ( $products as $product ) {
		if ($product ['is_shipping_free'] == '0' || $product ['is_shipping_free'] == 0) { // 如果不是免运费的话
			$result += $product ['quantity'];
		}
	}
	
	return $result;
}

/**
 * 获取可以用的配送方式
 */
function _get_shipping_methods($order = array()) {
	$result = array ();
	if ($order == array ()) {
		if ($_SESSION ['cart'] ['products'] [0] ['province'] != '' && ! isset ( $_SESSION ['user'] ['province'] ) && ! isset ( $_SESSION ['user'] ['city'] ) && ! isset ( $_SESSION ['user'] ['district'] )) {
			$province = $_SESSION ['cart'] ['products'] [0] ['province'];
			$city = $_SESSION ['cart'] ['products'] [0] ['city'];
			$district = $_SESSION ['cart'] ['products'] [0] ['district'];
		} else {
			$province = $_SESSION ['user'] ['province'];
			$city = $_SESSION ['user'] ['city'];
			$district = $_SESSION ['user'] ['district'];
		}
	} else {
		$province = $order ['consignee_province'];
		$city = $order ['consignee_city'];
		$district = $order ['consignee_district'];
	}
	$location [] = $province . "_*_*";
	$location [] = $province . "_" . $city . "_*";
	$location [] = $province . "_" . $city . "_" . $district;
	
	// 这里需要根据用户的收货地址来选择可以供货的配送方式
	return _could_devliver_shipping_methods ( $location );
}
/**
 * 通过数量来计算运费
 */
function _calc_shipping_fee_by_quantity($quantity, $shipping_methods_item) {
	return $shipping_methods_item ['basic_fee'] + $shipping_methods_item ['cod_fee'] + $shipping_methods_item ['single_product_fee'] * $quantity;
}

/**
 * 通过重量来计算运费
 * *
 */
function _calc_shipping_fee_by_weight($weight, $shipping_methods_item) {
	
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

/**
 * 根据商品的id获取商品的记录
 *
 * @param unknown $product_id        	
 */
function _get_product_by_id($product_id) {
	$query_express_company = "SELECT * FROM product WHERE id = '" . $product_id . "'";
	global $db_conn;
	global $db_database_localhost;
	
	$express_company = mysqli_query($db_conn,$query_express_company ) or die ( mysqli_error ($localhost).$query_express_company);
	$row_express_company = mysqli_fetch_assoc ( $express_company );
	$totalRows_express_company = mysqli_num_rows ( $express_company );
	if ($totalRows_express_company == 0) {
		return false;
	}
	return $row_express_company;
}

/**
 * 检查是否在运送范围之内
 */
function _could_devliver_shipping_methods($areas) {
	if (! is_array ( $areas )) {
		return false;
	}
	$result = array ();
	
	// 获取所有已经激活的且没有被删除的配送方式的未删除配送区域
	$query_area = "SELECT shipping_method_area.*,shipping_method.is_free,shipping_method.is_delete as shipping_method_is_delete,shipping_method.is_activated as shipping_method_is_activated,shipping_method.is_cod,shipping_method.name as shipping_method_name from shipping_method_area left join shipping_method on shipping_method_area.shipping_method_id=shipping_method.id where shipping_method_area.is_delete=0";
	global $db_conn;
	global $db_database_localhost;
	
	$area = mysqli_query($db_conn ,$query_area) or die ( mysqli_error ($localhost).$query_area);
	
	while ( $order_area = mysqli_fetch_assoc ( $area ) ) {
		foreach ( $areas as $area_item ) {
			if ($order_area ['shipping_method_is_delete'] == '0' && $order_area ['shipping_method_is_activated'] == '1' && strpos ( $order_area ['area'], $area_item ) > - 1) {
				$result [] = $order_area;
			}
		}
	}
	return $result;
}

/**
 * 订单合并
 *
 * @param unknown $from_order_sn        	
 * @param unknown $to_order_sn        	
 * @throws Exception
 */
function phpshop123_order_merge($from_order_sn, $to_order_sn) {
	
	// 检查主订单和从订单是否存在，如果不存在，那么抛出错误
	$from_order_obj = _get_order_by_sn ( $from_order_sn );
	$to_order_obj = _get_order_by_sn ( $to_order_sn );
	if (! $from_order_obj) {
		throw new Exception ( "订单号为：" . $from_order_sn . "的订单不存在" );
	}
	
	if (! $to_order_obj) {
		throw new Exception ( "订单号为" . $to_order_sn . "的订单不存在" );
	}
	
	// 如果存在的话，那么检查是否已经被删除，如果有删除的话，那么抛出错误
	if ($from_order_obj ['is_delete'] == 1) {
		throw new Exception ( "订单号为：" . $from_order_sn . "的订单已被删除,不能合并,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	if ($to_order_obj ['is_delete'] == 1) {
		throw new Exception ( "订单号为：$to_order_sn的订单已被删除,不能合并,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 检查2张订单是否已经合并,如果已经合并过了的话，那么直接返回true
	if ($from_order_obj ['merge_to'] == $to_order_obj ['id']) {
		return true;
	}
	
	// 检查订单是否已经被合并到其他订单，如果已经合并过了，那么告知
	if ($from_order_obj ['merge_to'] != "0") {
		throw new Exception ( "订单号为：" . $from_order_sn . "的订单订单已经被合并，不能再次进行操作,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 检查主订单是否已经被合并到其他订单，如果已经合并过了，那么告知
	if ($to_order_obj ['merge_to'] != "0") {
		throw new Exception ( "订单号为：" . $to_order_sn . "的订单订单已经被合并，不能再次行操作,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 检查订单状态是否相同。是否在退货和发货之间，如果已经发货，那么告知
	if ($from_order_obj ['order_status'] != $to_order_obj ['order_status']) {
		throw new Exception ( "只有状态相同的订单才可以合并哦,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 检查主订单的状态
	if ($to_order_obj ['order_status'] != ORDER_STATUS_UNPAID && $to_order_obj ['order_status'] != ORDER_STATUS_PAID) {
		throw new Exception ( "订单号为：" . $to_order_sn . "的订单的状态既不是创建也不是已经付款，所以不能进行合并,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 检查从订单的状态
	if ($from_order_obj ['order_status'] != ORDER_STATUS_UNPAID && $from_order_obj ['order_status'] != ORDER_STATUS_PAID) {
		throw new Exception ( "订单号为：" . $from_order_obj . "的订单的状态既不是创建也不是已经付款，所以不能进行合并,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 将从订单的订单sn修改为订单的订单sn
	if (! _update_merge_to ( $from_order_obj ['id'], $to_order_obj ['id'] )) {
		throw new Exception ( "更新从订单是否为合并订单的数据库操作失败,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 将从订单的产品所属的订单id修改为主订单的id
	if (! _update_child_order_product_order_id ( $from_order_obj ['id'], $to_order_obj ['id'] )) {
		throw new Exception ( "更新从订单产品的数据库操作失败,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	// 更新主订单的价格参数
	if (! _update_to_order_price_para ( $from_order_obj, $to_order_obj )) {
		throw new Exception ( "更新主订单价格的数据库操作失败,欢迎联系123phpshop.com寻求解决方案" );
	}
	
	if (! _log_order_merge ( $from_order_obj ['id'], $to_order_obj ['id'] )) {
		throw new Exception ( "更新主订单价格的数据库操作失败,欢迎联系123phpshop.com寻求解决方案" );
	}
}

// 更新订单的费用，这里面有个问题，现在的合并用户是不能享受优惠的
function phpshop123_update_order_fee($order) {
	
	// 初始化这个订单的费用
	$products = array ();
	$product_fee = 0.00;
	$shipping_fee = 0.00;
	$promotion_fee = 0.00;
	$order_total = 0.00;
	
	// 获取订单的商品
	$products = _get_products_by_order_id ( $order ['id'] );
	$product_fee = _123phpshop_get_product_fee ( $products ); // 获取所有的产品配用
	                                                          
	// 根据订单的商品获取促销信息
	$promotion_fee_obj = _123phpshop_get_promotion_fee ( $product_fee, $order ); // 获取促销费用数据
	
	$promotion_fee = $promotion_fee_obj ['fee']; // 获取促销的费用
	$promotion_presents = $promotion_fee_obj ['presents']; // 获取促销的赠品
	$original_promotion_ids = explode ( ",", $order ['promotion_id'] );
	
	// 如果有赠品的话，那么将赠品添加到订单中，这是个数据库操作
	$order = _123phpshop_add_order_presents ( $order, $promotion_presents );
	
	// 获取订单的运费信息
	$shipping_fee_array = get_shipping_fee ( $order );
	$shipping_fee = $shipping_fee_array ['shipping_fee']; // 获取运费费用
	$shipping_fee_plan = $shipping_fee_array ['shipping_fee_plan']; // 获取快递公司
	                                                                
	// 将之前的促销和本次可以享受的促销进行合并，并去除重复的元素
	$promotion_id_array = array_merge ( $original_promotion_ids, $promotion_fee_obj ['promotion_ids'] );
	$promotion_id_array = array_unique ( $promotion_id_array );
	$promotion_id_array = array_filter ( $promotion_id_array );
	
	// 检查促销计划里面是否已经存在，
	$promotion_fee_promotion_ids = implode ( ",", $promotion_id_array ); //
	                                                                     
	// 获取促销可以减去的金额
	$promotion_fee = floatval ( $order ['promotion_fee'] ) + floatval ( $promotion_fee );
	
	// 获取订单总额
	$order_total = _123phpshop_get_order_total ( $product_fee, $shipping_fee, $promotion_fee ); // 获取订单的总费用
	                                                                                            
	// 更新订单的各项费用，这是个数据库操作
	_do_update_order_fee ( $order ['id'], $product_fee, $shipping_fee, $promotion_fee, $order_total, $shipping_fee_plan, $promotion_fee_promotion_ids ); // 更新db中的数据
}

/**
 * 获取订单下面的所有产品项
 *
 * @param unknown $order_id        	
 */
function _get_products_by_order_id($order_id) {
	$result = array ();
	global $db_conn;
	global $db_database_localhost;
	
	$query_order_items = "SELECT * FROM order_item WHERE is_delete=0 and order_id = '" . $order_id . "'";
	$order_items = mysqli_query($db_conn,$query_order_items ) or die ( mysqli_error ($localhost).$query_order_items);
	$totalRows_order_items = mysqli_num_rows ( $order_items );
	if ($totalRows_order_items == 0) {
		return $result;
	}
	while ( $item = $row_order_items = mysqli_fetch_assoc ( $order_items ) ) {
		$result [] = $item;
	}
	return $result;
}

/**
 * 获取商品的总费用
 *
 * @param unknown $products        	
 * @return number
 */
function _123phpshop_get_product_fee($products) {
	$result = 0.00;
	foreach ( $products as $product ) {
		$result += ( float ) $product ['should_pay_price'] * $product ['quantity'];
	}
	return $result;
}

/**
 * 将促销生成的赠品添加到订单中
 *
 * @param unknown $order        	
 * @param unknown $promotion_presents        	
 */
function _123phpshop_add_order_presents($order, $promotion_presents) {
	// 如果赠品的数量为0的话，那么直接退出
	if (count ( $promotion_presents ) == 0) {
		return $order;
	}
	
	// 如果》0的话，那么循环这些赠品，
	foreach ( $promotion_presents as $product_id ) {
		
		// 获取这个商品.然后添加到模型层里面
		$product = _123phpshop_get_product_by_id ( $product_id );
		$product ['is_present'] = 1;
		$product ['product_price'] = 0.00;
		$product ['should_pay_price'] = 0.00;
		$product ['quantity'] = 1;
		$order ['products'] [] = $product;
		
		// 添加到数据库里面
		global $db_conn;
		global $db_database_localhost;
		
		$insertSQL = sprintf ( "INSERT INTO order_item (should_pay_price,order_id,product_id, quantity, attr_value, is_present) VALUES (%s,%s, %s,%s, %s, %s)", GetSQLValueString ( 0.00, "double" ), GetSQLValueString ( $order ['id'], "int" ), GetSQLValueString ( $product_id, "int" ), GetSQLValueString ( 1, "int" ), GetSQLValueString ( "", "text" ), GetSQLValueString ( 1, "int" ) );
		$new_order_item_query = mysqli_query($db_conn,$insertSQL);
		if (! $new_order_item_query) {
			throw new Exception ( "系统错误，请联系123phpshop.com寻求解决方案" );
		}
	}
	
	return $order;
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
function _do_update_order_fee($order_id, $product_fee, $shipping_fee, $promotion_fee, $order_total, $shipping_fee_plan, $promotion_fee_promotion_ids) {
	global $db_conn;
	global $db_database_localhost;
	
	$sql = sprintf ( "update orders set products_total=%s,shipping_fee=%s,promotion_fee=%s,should_paid=%s,shipping_method=%s where id=%s", $product_fee, $shipping_fee, $promotion_fee, $order_total, $shipping_fee_plan, $order_id );
	if ($promotion_fee_promotion_ids != '') {
		$sql = sprintf ( "update orders set products_total=%s,shipping_fee=%s,promotion_fee=%s,should_paid=%s,shipping_method=%s,promotion_id='%s' where id=%s", $product_fee, $shipping_fee, $promotion_fee, $order_total, $shipping_fee_plan, $promotion_fee_promotion_ids, $order_id );
	}
	
	$result = mysqli_query($db_conn,$sql);
	if (! $result) {
		throw new Exception ( "订单费用更新错误，请重试,欢迎联系123phpshop.com寻求解决方案" . mysqli_error ($localhost) );
	}
}

// 通过订单序列号来获取订单记录
function _get_order_by_sn($from_order_sn) {
	$query_form_order = sprintf ( "SELECT * FROM orders WHERE sn = '%s'", $from_order_sn );
	global $db_conn;
	global $db_database_localhost;
	
	$form_order = mysqli_query($db_conn,$query_form_order ) or die ( mysqli_error ($localhost).$query_form_order);
	$row_form_order = mysqli_fetch_assoc ( $form_order );
	$totalRows_form_order = mysqli_num_rows ( $form_order );
	if ($totalRows_form_order > 0) {
		return $row_form_order;
	}
	return false;
}

/**
 * 更新主订单的费用
 *
 * @param unknown $from_order_obj        	
 * @param unknown $to_order_obj        	
 * @return boolean
 */
function _update_to_order_price_para($from_order_obj, $to_order_obj) {
	$shipping_fee = $from_order_obj ['shipping_fee'] + $to_order_obj ['shipping_fee'];
	$products_total = $from_order_obj ['products_total'] + $to_order_obj ['products_total'];
	$should_paid = $from_order_obj ['should_paid'] + $to_order_obj ['should_paid'];
	$actual_paid = $from_order_obj ['actual_paid'] + $to_order_obj ['actual_paid'];
	
	// 更新主订单的运费
	$query_form_order = sprintf ( "update orders set shipping_fee='%s',products_total='%s',should_paid='%s',actual_paid='%s' WHERE id = '%s'", $shipping_fee, $products_total, $should_paid, $actual_paid, $to_order_obj ['id'] );
	global $db_conn;
	global $db_database_localhost;
	
	return mysqli_query($db_conn,$query_form_order ) or die ( mysqli_error ($localhost).$query_form_order);
}

// 更新从订单产品的订单id
function _update_child_order_product_order_id($from_order_id, $to_order_id) {
	$query_form_order = sprintf ( "update order_item set order_id='%s' WHERE order_id = '%s'", $to_order_id, $from_order_id );
	global $db_conn;
	global $db_database_localhost;
	
	return mysqli_query($db_conn,$query_form_order);
}

// 更新merge_to字段
function _update_merge_to($from_order_id, $to_order_id) {
	$query_form_order = sprintf ( "update orders set merge_to='%s' WHERE id = '%s'", $to_order_id, $from_order_id );
	global $db_conn;
	global $db_database_localhost;
	
	return mysqli_query($db_conn,$query_form_order ) or die ( mysqli_error ($localhost).$query_form_order);
}

// 通过id获取订单记录
function _get_order_by_id($order_id) {
	$query_form_order = sprintf ( "SELECT * FROM orders WHERE id = '%s'", $order_id );
	global $db_conn;
	global $db_database_localhost;
	
	$form_order = mysqli_query($db_conn,$query_form_order ) or die ( mysqli_error ($localhost).$query_form_order);
	$row_form_order = mysqli_fetch_assoc ( $form_order );
	$totalRows_form_order = mysqli_num_rows ( $form_order );
	if ($totalRows_form_order > 0) {
		return $row_form_order;
	}
	return false;
}

// 将订单合并信息添加到订单日志之中
function _log_order_merge($from_order_obj, $to_order_obj) {
	global $db_conn;
	global $db_database_localhost;
	
	$order_log_sql = "insert into order_log(order_id,message)values('" . $to_order_obj ['id'] . "','成功将订单号为：'" . $from_order_obj ['sn'] . "'的订单合并到:" . $to_order_obj ['sn'] . ")";
	return mysqli_query($db_conn,$order_log_sql);
}

/**
 * 订单log的数据库记录函数
 *
 * @param unknown $order_id        	
 * @param unknown $message        	
 * @return mixed
 */
function phpshop123_log_order($order_id, $message) {
	global $db_conn;
	global $db_database_localhost;
	
	$order_log_sql = "insert into order_log(order_id,message)values('" . $order_id . "','" . $message . "')";
	return mysqli_query($db_conn,$order_log_sql);
}

/**
 * 增加新订单创建的数据库记录
 *
 * @param unknown $order_id        	
 * @return mixed
 */
function phpshop123_log_order_new($order_id) {
	$message = '创建订单成功！';
	return phpshop123_log_order ( $order_id, $message );
}

/**
 * 获取所有的促销计划
 *
 * @param unknown $order        	
 * @return unknown[]
 */
function _123phpshop_get_promotion_fee($product_fee, $order) {
	
	// 初始化结果参数
	$results = array ();
	$results ['fee'] = 0.00;
	$results ['presents'] = array ();
	$results ['promotion_ids'] = array ();
	
	// 获取所有当前可用的促销计划
	global $db_conn;
	global $db_database_localhost;
	
	$sql = "SELECT * FROM promotion WHERE is_delete = 0 and start_date<=" . date ( 'Ymd' ) . " and end_date>=" . date ( 'Ymd' );
	$promotions = mysqli_query($db_conn,$sql);
	if (mysqli_num_rows ( $promotions ) == 0) {
		return $results;
	}
	
	// 循环这些促销
	while ( $promotion_plan = mysqli_fetch_assoc ( $promotions ) ) {
		
		// 这里需要检查用户是否已经享受到了这个促销,如果已经享受了的话，那么就不用在进行了
		$promotion_ids_array = explode ( ",", $order ['promotion_id'] );
		if (in_array ( $promotion_plan ['id'], $promotion_ids_array )) {
			continue;
		}
		
		// 检查促销的使用范围，如果是全场的话，那么直接添加
		if ($promotion_plan ['promotion_limit'] == "1" && ( float ) $product_fee > ( float ) $promotion_plan ['amount_lower_limit']) {
			
			$promotion_fee_presents = _get_promotion_fee_presents ( $promotion_plan, $product_fee );
			
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
			$catalog_product_fee = _123phpshop_get_catalog_product_fee ( $order, $promotion_plan );
			// 如果指定分类的商品的总额《参与促销活动的最低的金额的话
			if (( float ) $catalog_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
				continue;
			}
			
			// 然后获取这个促销的金额和赠品
			$promotion_fee_presents = _get_promotion_fee_presents ( $promotion_plan, $catalog_product_fee );
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
			$brand_product_fee = _123phpshop_get_brand_product_fee ( $order, $promotion_plan );
			// 如果这个品牌的商品的总额《参与促销活动的最低的金额的话
			if (( float ) $brand_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
				continue;
			}
			
			// 然后获取这个促销的金额和赠品
			$promotion_fee_presents = _get_promotion_fee_presents ( $promotion_plan, $brand_product_fee );
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
			
			$product_product_fee = _123phpshop_get_product_product_fee ( $order, $promotion_plan );
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
function _123phpshop_get_catalog_product_fee($order, $promotions) {
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
function _123phpshop_get_brand_product_fee($order, $promotions) {
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
function _123phpshop_get_product_product_fee($order, $promotions) {
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
 * 更新
 *
 * @param unknown $promotion_plan        	
 */
function _update_promotion_presents($promotion_plan) {
	
	// 获取产品id的数组
	$products_id_array = explode ( ",", $promotion_plan ['present_products'] );
	if (count ( products_id_array ) == 0) {
		return;
	}
	
	// 循环这些数组
	foreach ( $products_id_array as $products_id ) {
		// 从数据中获取这些赠品的信息
		$product = _get_product_from_db_by_id ( $products_id );
		if ($product != null) {
			$order ['promotion_presents'] [] = $product;
		}
	}
}

// 更新购物车中的促销费用
function _update_promotion_fee($promotion_plan) {
	
	// 如果是满减的话，那么直接+即可
	if ($promotion_plan ['promotion_type'] == 2) {
		$promotion_plan ['promotion_fee'] += $promotion_plan ['promotion_type_val'];
	}
	
	// 如果是满折扣的话，那么需要计算产品的金额，然后计算折扣的金额
	if ($promotion_plan ['promotion_type'] == 3) {
		$promotion_plan ['promotion_fee'] += $promotion_plan ['products_total'] * $promotion_plan ['promotion_type_val'] / 100;
	}
}

?>