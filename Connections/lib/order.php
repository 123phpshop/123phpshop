<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php

/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 * 作者: 123PHPSHOP团队
 * 手机: 13391334121
 * 邮箱: service@123phpshop.com
 */
?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php

/**
 * 生成订单序列号
 */
function gen_order_sn() {
	$sn = date ( "YmdHis" ) . rand ( 10000000, 99999999 );
	return $sn;
}

/**
 * 在订单中添加产品
 *
 * @param unknown $order        	
 * @param unknown $product        	
 */
function phpshop123_order_add_product($order, $product) {
	
	// 检查订单是否已经设置了收货人信息，如果没有的话，那么告知无法添加
	if (! isset ( $order ['consignee_province'] ) || ! isset ( $order ['consignee_city'] ) || ! isset ( $order ['consignee_district'] )) {
		throw new Exception ( "收货人没有设置，请首先设置收货人信息" );
	}
	
	// 首先检查商品是否在配送范围之内，如果没有在配送范围之内的话，那么告知无法配送
	if (! _123phpshop_product_could_ship ( $order, $product )) {
		throw new Exception ( "不在配送范围之内，无法添加" );
	}
	
	// 如果在配送范围之内的话，那么检查订单中是否已经有了这个商品,
	$product_in_order = _123phpshop_product_is_in_order ( $order, $product );
	
 	// 1. 如果订单中没有这个商品，并且商品是赠品的话
	if (! $product_in_order && _123phpshop_order_product_is_present ( $product )) {
  		die("1");
		_123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录
		return true;
	}
	
	// 2. 如果订单中没有这个商品,而且当前商品不属于赠品的话
	if (! $product_in_order && !_123phpshop_order_product_is_present ( $product )) {
  		die("2");
		_123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录
		_123phpshop_order_update_fee_promotion ( $order ); // 更新订单的费用和促销信息
		return;
	}
	
	// 3. 如果订单中有这个商品，且当前的商品和之前的商品都是赠品的话，那么直接将之前商品的数量+1即可
	if ($product_in_order && _123phpshop_order_product_is_present ( $product ) && _123phpshop_order_product_is_present ( $product_in_order )) {
		// 那么直接将商品的数量+1即可
  		die("3");
		_123phpshop_order_update_product_quantity ( $order, $product_in_order, 1 ); // 将订单中之前的商品数量+1
		return;
	}
	
	// 4. 如果订单中有这个商品，当前的产品和之前的商品都不是赠品的话，
	if ($product_in_order && ! _123phpshop_order_product_is_present ( $product ) && ! _123phpshop_order_product_is_present ( $product_in_order )) {
  		die("4");
		_123phpshop_order_update_product_quantity ( $order, $product_in_order, 1 ); // 将之前的产品的数量+1
		_123phpshop_order_update_fee_promotion ( $order ); // 更新订单的费用和促销信息
		return;
	}
	
	// 5. 如果订单中有这个商品，且当前的商品为赠品，但是之前的产品不是赠品的话，那么直接插入记录，不需要更新费用
	if ($product_in_order && _123phpshop_order_product_is_present ( $product ) && 　！_123phpshop_order_product_is_present ( $product_in_order )) {
  		die("5");
		_123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录即可
		return;
	}
	
	// 6. 如果订单中有这个商品，当前的产品不是赠品，但是之前的商品属于赠品的话
	if ($product_in_order && ! _123phpshop_order_product_is_present ( $product ) && _123phpshop_order_product_is_present ( $product_in_order )) {
		_123phpshop_order_do_add_product ( $order, $product ); // 直接插入当前产品记录即可
		_123phpshop_order_update_fee_promotion ( $order ); // 更新订单的费用和促销信息
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
		throw new Exception ( "订单不存在！" );
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
	$query_order = sprintf ( "SELECT * FROM orders WHERE id = %s and is_delete=0 ", $id );
	$order = mysql_query ( $query_order, $db_conn ) or die ( mysql_error () );
	$totalRows_order = mysql_num_rows ( $order );
	if ($totalRows_order == 0) {
		return false;
	}
	
	return mysql_fetch_assoc ( $order );
}

/**
 * 根据id获取订单下面的产品
 * 
 * @param unknown $id        	
 */
function _123phpshop_get_order_items_by_id($id) {
	global $db_conn;
	$result = array ();
	$query_order = sprintf ( "SELECT * FROM order_item WHERE order_id = %s and is_delete=0 ", $id );
	$order = mysql_query ( $query_order, $db_conn ) or die ( mysql_error () );
	$totalRows_order = mysql_num_rows ( $order );
	while ( $row_order = mysql_fetch_assoc ( $order ) ) {
		$result [] = $row_order;
	}
	
	return $result;
}

function _123phpshop_order_product_is_present($product){
	if(!isset($product['is_present'])){
		return false;
	}
	return  ($product['is_present']=='1' || $product['is_present']==1);
}

/**
 * 检查产品是否可以被配送
 *
 * @param unknown $product        	
 * @return boolean
 */
function _123phpshop_product_could_ship($order, $product) {
	$result = true;
	return $result;
}

/**
 * 检查订单中是否已经存在存在这个商品了
 *
 * @param unknown $order        	
 * @param unknown $product        	
 */
function _123phpshop_product_is_in_order($order, $product) {
	foreach ( $order ['products'] as $product_item ) {
		if ($product_item ['product_id'] == $product ['product_id']) {
			return $product_item;
		}
	}
	return false;
}

function _123phpshop_order_update_product_quantity($order, $product,$quantity){
	global $db_conn;
	$sql="update order_item set quantity=quantity+".$quantity." where order_id=".$order['id']." and product_id=".$product['product_id'];
	return mysql_query($sql);
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
	$should_pay_price=_123phpshop_get_price_by_product_id($product['product_id']);
	$is_present=0;
	
 	//  如果是赠品的话
   	if(isset($product['is_present']) || ($product['is_present']=='1' || $product['is_present']==1)){
		$should_pay_price=0.00;
		$is_present=1;
	}
	
 	$insertSQL = sprintf ( "INSERT INTO order_item (should_pay_price,order_id,product_id, quantity, attr_value, is_present) VALUES (%s,%s, %s,%s, %s, %s)", GetSQLValueString ( $should_pay_price, "double" ), GetSQLValueString ( $order['id'], "int" ), GetSQLValueString ( $product ['product_id'], "int" ), GetSQLValueString ( $product ['quantity'], "int" ), GetSQLValueString ( $product ['attr_value'], "text" ), GetSQLValueString ( $is_present,"int") );
 	return $order = mysql_query ( $insertSQL, $db_conn );
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

// 更新产品总价
function _123phpshop_update_products_total($order) {
	$result=0.00;
	// 循环订单中的所有商品
	foreach($order['products'] as $product){
		
		// 如果商品是赠品的话，那么不计入总计
		
		// 如果商品处于优惠期之内的话，那么按照优惠价格进行计算
	}
	return $result=0.00;
}

function _123phpshop_get_product_by_id($id){
	global $db_conn;
 	$query_sql = sprintf ( "SELECT * FROM product WHERE id = %s", $id );
	$query = mysql_query ( $query_sql, $db_conn ) or die ( mysql_error () );
	$result=mysql_fetch_assoc($query);
	$totalRows_order = mysql_num_rows ( $query );
	if($totalRows_order==0){
		return false;
	}
	return $result;
}

function _123phpshop_get_price_by_product_id($id){
	$result=false;
 	$product=_123phpshop_get_product_by_id($id);
  	if(!$product){
 		return $result;
 	}
 	$curr_date=date("Y-m-d");
	//	检查产品是否在优惠期之内，如果在优惠期之内，那么产品的价格就是优惠价格
	if($product['is_promotion']==1 && $curr_date>=$product['promotion_start'] && $curr_date<=$product['promotion_end']){
		$result=$product['promotion_price'];
	}else{
		$result=$product['price'];
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
	$result=0.00;
	foreach($order['products'] as $item){
		if($item){
			// 首先检查是否是赠品，如果是赠品的话，那么不计入总的产品价格
			
			// 如果不是赠品，那么检查是否可以使用优惠价格，如果可以应用优惠价格的话，那么使用优惠价格
			
  		}
	}
}

// 更新运费总价格
function _123phpshop_get_shipping_fee($order) {
	
}

// 更新订单总价
function _123phpshop_get_order_total($order) {
}
// 更新促销总价
function _123phpshop_get_promotion($order) {
	
}

/**
 * 删除订单中的某项产品
 *
 * @param unknown $order        	
 * @param unknown $product        	
 */
function phpshop123_order_remove_product($order, $product) {
	_do_order_remove_product($order, $product);
	_123phpshop_order_update_fee_promotion ( $order ); // 更新订单的费用和促销信息
}

function phpshop123_order_update_fee_promotion ( $order_id ){
	$order=_123phpshop_get_order_by_id($id);
	$products=_123phpshop_get_order_items_by_id($id);
	$order['products']=$products;
	_123phpshop_update_products_total ( $order ); // 更新产品总价
	_123phpshop_update_shipping_fee ( $order ); // 更新运费总价格
	_123phpshop_update_order_total ( $order ); // 更新订单总价
	_123phpshop_update_promotion ( $order ); // 更新促销总价
}

function _do_order_remove_product($order, $product){
	$sql="";
	
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
 * *
 */
function get_shipping_fee() {
	
	// 获取这个订单的所有商品的总重量和总数量
	$weight = 0.00;
	$quantity = 1;
	$shipping_fee = 0.00;
	$shipping_fee_plan = 1;
	$weight = _get_order_weight ();
	$is_first_shipping_fee = true;
	$quantity = _get_order_quantity ();
	$shipping_methods = _get_shipping_methods ();
	
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
 * *
 */
function _get_order_weight() {
	$result = 0;
	foreach ( $_SESSION ['cart'] ['products'] as $product ) {
		if ($product ['is_shipping_free'] == '0') { // 如果不是免运费的话
			$product_obj = _get_product_by_id ( $product ['product_id'] );
			if (false == $product_obj) {
				throw new Excaption ( "产品不存在或者已经删除" );
			}
			$result += $product_obj ['weight'] * $product ['quantity']; // 商品总重=商品数量×商品重量
		}
	}
	return $result;
}

/**
 * 获取购物车中产品的数量
 * *
 */
function _get_order_quantity() {
	$result = 0;
	foreach ( $_SESSION ['cart'] ['products'] as $product ) {
		if ($product ['is_shipping_free'] == '0') { // 如果不是免运费的话
			$result += $product ['quantity'];
		}
	}
	return $result;
}

/**
 * 获取可以用的配送方式
 * *
 */
function _get_shipping_methods() {
	$result = array ();
	if ($_SESSION ['cart'] ['products'] [0] ['province'] != '' && ! isset ( $_SESSION ['user'] ['province'] ) && ! isset ( $_SESSION ['user'] ['city'] ) && ! isset ( $_SESSION ['user'] ['district'] )) {
		$province = $_SESSION ['cart'] ['products'] [0] ['province'];
		$city = $_SESSION ['cart'] ['products'] [0] ['city'];
		$district = $_SESSION ['cart'] ['products'] [0] ['district'];
	} else {
		$province = $_SESSION ['user'] ['province'];
		$city = $_SESSION ['user'] ['city'];
		$district = $_SESSION ['user'] ['district'];
	}
	$location [] = $province . "_*_*";
	$location [] = $province . "_" . $city . "_*";
	$location [] = $province . "_" . $city . "_" . $district;
	
	// 这里需要根据用户的收货地址来选择可以供货的配送方式
	return _could_devliver_shipping_methods ( $location );
}
/**
 * 通过数量来计算运费
 * *
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
function _get_product_by_id($product_id) {
	$query_express_company = "SELECT * FROM product WHERE id = '" . $product_id . "'";
	global $db_conn;
	$express_company = mysql_query ( $query_express_company, $db_conn ) or die ( mysql_error () );
	$row_express_company = mysql_fetch_assoc ( $express_company );
	$totalRows_express_company = mysql_num_rows ( $express_company );
	if ($totalRows_express_company == 0) {
		return false;
	}
	return $row_express_company;
}

/**
 * 检查是否在运送范围之内
 * *
 */
function _could_devliver_shipping_methods($areas) {
	if (! is_array ( $areas )) {
		return false;
	}
	$result = array ();
	
	// 获取所有已经激活的且没有被删除的配送方式的未删除配送区域
	$query_area = "SELECT shipping_method_area.*,shipping_method.is_free,shipping_method.is_delete as shipping_method_is_delete,shipping_method.is_activated as shipping_method_is_activated,shipping_method.is_cod,shipping_method.name as shipping_method_name from shipping_method_area left join shipping_method on shipping_method_area.shipping_method_id=shipping_method.id where shipping_method_area.is_delete=0";
	global $db_conn;
	$area = mysql_query ( $query_area, $db_conn ) or die ( mysql_error () );
	
	while ( $order_area = mysql_fetch_assoc ( $area ) ) {
		foreach ( $areas as $area_item ) {
			if ($order_area ['shipping_method_is_delete'] == '0' && $order_area ['shipping_method_is_activated'] == '1' && strpos ( $order_area ['area'], $area_item ) > - 1) {
				$result [] = $order_area;
			}
		}
	}
	return $result;
}

// 订单合并
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
		throw new Exception ( "订单号为：$from_order_sn的订单已被删除,不能合并" );
	}
	
	if ($to_order_obj ['is_delete'] == 1) {
		throw new Exception ( "订单号为：$to_order_sn的订单已被删除,不能合并" );
	}
	
	// 检查2张订单是否已经合并,如果已经合并过了的话，那么直接返回true
	if ($from_order_obj ['merge_to'] == $to_order_obj ['id']) {
		return true;
	}
	
	// 检查订单是否已经被合并到其他订单，如果已经合并过了，那么告知
	if ($from_order_obj ['merge_to'] != "0") {
		throw new Exception ( "订单号为：" . $from_order_sn . "的订单订单已经被合并，不能再次进行操作" );
	}
	
	// 检查主订单是否已经被合并到其他订单，如果已经合并过了，那么告知
	if ($to_order_obj ['merge_to'] != "0") {
		throw new Exception ( "订单号为：" . $to_order_sn . "的订单订单已经被合并，不能再次行操作" );
	}
	
	// 检查订单状态是否相同。是否在退货和发货之间，如果已经发货，那么告知
	if ($from_order_obj ['order_status'] != $to_order_obj ['order_status']) {
		throw new Exception ( "只有状态相同的订单才可以合并哦" );
	}
	
	// 检查主订单的状态
	if ($to_order_obj ['order_status'] != ORDER_STATUS_UNPAID && $to_order_obj ['order_status'] != ORDER_STATUS_PAID) {
		throw new Exception ( "订单号为：" . $to_order_sn . "的订单的状态既不是创建也不是已经付款，所以不能进行合并" );
	}
	
	// 检查从订单的状态
	if ($from_order_obj ['order_status'] != ORDER_STATUS_UNPAID && $from_order_obj ['order_status'] != ORDER_STATUS_PAID) {
		throw new Exception ( "订单号为：" . $from_order_obj . "的订单的状态既不是创建也不是已经付款，所以不能进行合并" );
	}
	
	// 将从订单的订单sn修改为订单的订单sn
	if (! _update_merge_to ( $from_order_obj ['id'], $to_order_obj ['id'] )) {
		throw new Exception ( "更新从订单是否为合并订单的数据库操作失败" );
	}
	
	// 将从订单的产品所属的订单id修改为主订单的id
	if (! _update_child_order_product_order_id ( $from_order_obj ['id'], $to_order_obj ['id'] )) {
		throw new Exception ( "更新从订单产品的数据库操作失败" );
	}
	
	// 更新主订单的价格参数
	if (! _update_to_order_price_para ( $from_order_obj, $to_order_obj )) {
		throw new Exception ( "更新主订单价格的数据库操作失败" );
	}
	
	if (! _log_order_merge ( $from_order_obj ['id'], $to_order_obj ['id'] )) {
		throw new Exception ( "更新主订单价格的数据库操作失败" );
	}
}

// 更新订单的费用，这里面有个问题，现在的合并用户是不能享受优惠的
function phpshop123_update_order_fee($order_id) {
	
	// 初始化这个订单的费用
	$products = array ();
	$product_fee = 0.00;
	$shipping_fee = 0.00;
	$promotion_fee = 0.00;
	$order_total = 0.00;
	
	// 获取订单的费用
	$products = _get_products_by_order_id ( $order_id );
	$product_fee = _get_product_fee ( $products ); // 获取所有的产品配用
	$shipping_fee = _get_shipping_fee ( $products ); // 获取运费费用
	$promotion_fee = _get_promotion_fee ( $products ); // 获取促销费用
	$order_total = _get_order_total ( $products ); // 获取订单的总费用
	_do_update_order_fee ( $product_fee, $shipping_fee, $promotion_fee, $order_total ); // 更新db中的数据
}
function _get_products_by_order_id($order_id) {
	$result = array ();
	mysql_select_db ( $database_localhost, $localhost );
	$query_order_items = "SELECT * FROM order_item WHERE order_id = '" . $order_id . "'";
	$order_items = mysql_query ( $query_order_items, $localhost ) or die ( mysql_error () );
	$totalRows_order_items = mysql_num_rows ( $order_items );
	if ($totalRows_order_items == 0) {
		return $result;
	}
	while ( $item = $row_order_items = mysql_fetch_assoc ( $order_items ) ) {
		$result [] = $item;
	}
	return $result;
}
function _get_product_fee($products) {
	$result = 0.00;
	
	return $result;
}
function _get_shipping_fee($products) {
	$result = 0.00;
	foreach ( $products as $product ) {
	}
	return $result;
}
function _get_promotion_fee($products) {
	$result = 0.00;
	foreach ( $products as $product ) {
	}
	return $result;
}
function _get_order_total($products) {
	$result = 0.00;
	foreach ( $products as $product ) {
	}
	return $result;
}
function _do_update_order_fee($product_fee, $shipping_fee, $promotion_fee, $order_total) {
	$sql = "";
	$result = mysql_query ( $sql );
	if (! $result) {
		throw new Exeption ( "订单费用更新错误，请重试！" );
	}
}

// 通过订单序列号来获取订单记录
function _get_order_by_sn($from_order_sn) {
	$query_form_order = sprintf ( "SELECT * FROM orders WHERE sn = '%s'", $from_order_sn );
	global $db_conn;
	$form_order = mysql_query ( $query_form_order, $db_conn ) or die ( mysql_error () );
	$row_form_order = mysql_fetch_assoc ( $form_order );
	$totalRows_form_order = mysql_num_rows ( $form_order );
	if ($totalRows_form_order > 0) {
		return $row_form_order;
	}
	return false;
}

// 更新主订单的费用
function _update_to_order_price_para($from_order_obj, $to_order_obj) {
	$shipping_fee = $from_order_obj ['shipping_fee'] + $to_order_obj ['shipping_fee'];
	$products_total = $from_order_obj ['products_total'] + $to_order_obj ['products_total'];
	$should_paid = $from_order_obj ['should_paid'] + $to_order_obj ['should_paid'];
	$actual_paid = $from_order_obj ['actual_paid'] + $to_order_obj ['actual_paid'];
	
	// 更新主订单的运费
	$query_form_order = sprintf ( "update orders set shipping_fee='%s',products_total='%s',should_paid='%s',actual_paid='%s' WHERE id = '%s'", $shipping_fee, $products_total, $should_paid, $actual_paid, $to_order_obj ['id'] );
	global $db_conn;
	return mysql_query ( $query_form_order, $db_conn ) or die ( mysql_error () );
}

// 更新从订单产品的订单id
function _update_child_order_product_order_id($from_order_id, $to_order_id) {
	$query_form_order = sprintf ( "update order_item set order_id='%s' WHERE order_id = '%s'", $to_order_id, $from_order_id );
	global $db_conn;
	return mysql_query ( $query_form_order, $db_conn );
}

// 更新merge_to字段
function _update_merge_to($from_order_id, $to_order_id) {
	$query_form_order = sprintf ( "update orders set merge_to='%s' WHERE id = '%s'", $to_order_id, $from_order_id );
	global $db_conn;
	return mysql_query ( $query_form_order, $db_conn ) or die ( mysql_error () );
}

// 通过id获取订单记录
function _get_order_by_id($order_id) {
	$query_form_order = sprintf ( "SELECT * FROM orders WHERE id = '%s'", $order_id );
	global $db_conn;
	$form_order = mysql_query ( $query_form_order, $db_conn ) or die ( mysql_error () );
	$row_form_order = mysql_fetch_assoc ( $form_order );
	$totalRows_form_order = mysql_num_rows ( $form_order );
	if ($totalRows_form_order > 0) {
		return $row_form_order;
	}
	return false;
}

// 将订单合并信息添加到订单日志之中
function _log_order_merge($from_order_obj, $to_order_obj) {
	global $db_conn;
	$order_log_sql = "insert into order_log(order_id,message)values('" . $to_order_obj ['id'] . "','成功将订单号为：'" . $from_order_obj ['sn'] . "'的订单合并到:" . $to_order_obj ['sn'] . ")";
	return mysql_query ( $order_log_sql, $db_conn );
}
?>