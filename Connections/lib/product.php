<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
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
<?php

// 返回用户是否已经评论过这个商品
function user_could_comment($user_id, $product_id) {
	
	// 检查用户购买过这个商品的数目，如果这个数目是0的话，那么直接返回true
	global $db_conn;
	global $glogger;
	$query_order = "SELECT orders.id, orders.user_id,order_item.order_id,order_item.product_id  FROM orders LEFT JOIN order_item ON order_item.order_id=orders.id WHERE orders.`user_id`=$user_id AND   order_item.product_id=$product_id";
	$order = mysqli_query($db_conn ) or die ( mysqli_error ($localhost),$query_order);
	if (! $order) {
		$glogger->fatal ( "获取用户购买过这个商品的数目失败:" . $query_order );
	}
	
	$totalRows_order = mysqli_num_rows ( $order );
	// $glogger->debug ( "用户购买过这个商品的数目:" . $totalRows_order );
	if ($totalRows_order == 0) {
		return false;
	}
	
	// 检查用户评论过这个商品的数目，如果这个数目是0的话，那么直接返回false
	$product_comment_num_sql = "select count(*) as comment_num from product_comment where product_id=$product_id and user_id=$user_id ";
	$product_comment_num_query = mysqli_query($db_conn ) or die ( mysqli_error ($localhost),$product_comment_num_sql);
	$product_comment_num = mysqli_fetch_assoc ( $product_comment_num_query );
	// $glogger->debug ( "用户评论过的商品的数目:" . $product_comment_num ['comment_num'] );
	if (( int ) $product_comment_num ['comment_num'] == 0) {
		return true;
	}
	
	// 检查2个数目，如果购买的数目>评论的数目的话，那么可以直接返回tru不能评论了
	return $totalRows_order > ( int ) $product_comment_num ['comment_num'];
}
/**
 * 添加浏览历史
 * 
 * @param unknown $product_id        	
 */
function add_view_history($product_id) {
	_add_session_view_history ( $product_id );
 	_add_db_view_history ( $product_id );
}

/**
 * 向SESSION中添加浏览历史
 * 
 * @param unknown $product_id        	
 */
function _add_session_view_history($product_id) {
	
	// 检查浏览记录是否设置了，如果设置过了的话，那么将其设置为空
	if (isset ( $_SESSION ['view_history'] ) || ! is_array ( $_SESSION ['view_history'] )) {
		$_SESSION ['view_history'] = array ();
	}
	
	$create_time = date ( 'Y-m-d H:i:s' );
	// $item['user_id']=$create_time;
	$item ['product_id'] = $product_id;
	$item ['creat_time'] = $create_time;
	$_SESSION ['view_history'] [] = $item;
}

/**
 * 添加浏览历史的数据库操作
 * 
 * @param unknown $product_id        	
 */
function _add_db_view_history($product_id) {
	
	// 检查里面是否已经存在了这个产品，如果有的话，那么删除这个产品，然后
	if (isset ( $_SESSION ['user_id'] )) {
		global $db_conn;
		global $glogger;
		global $db_database_localhost;
		
 		$colname_get_last_view_product = "-1";
		if (isset($_SESSION['user_id'])) {
		  $colname_get_last_view_product = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
		}
		mysql_select_db($db_database_localhost, $db_conn);
		$query_get_last_view_product = sprintf("SELECT * FROM user_view_history WHERE user_id = %s and product_id= %s ORDER BY id DESC limit 1", $colname_get_last_view_product,$product_id);
		$get_last_view_product = mysqli_query($db_conn,$query_get_last_view_product);
		if(!$get_last_view_product){
				$glogger->fatal("获取用户最后一条查看历史数据库操作失败:".$query_get_last_view_product);
				return;
		}
		
		$row_get_last_view_product = mysqli_fetch_assoc($get_last_view_product);
		$totalRows_get_last_view_product = mysqli_num_rows($get_last_view_product);
		if($totalRows_get_last_view_product==0){
			// 如果最后一条不是这个用户的数据的话,那么直接插入
			$sql = sprintf ( "insert into user_view_history (user_id,product_id) values('%s','%s')", $_SESSION ['user_id'], $product_id );
 		}else{
			// 如果最后一条是这个用户的数据的话，那么只更新浏览历史的时间
			$sql = sprintf ( "update  user_view_history set create_time='%s'  where id=%s", date("Y-m-d H:i:s"),$row_get_last_view_product['id'] );
  }
 		$query=mysqli_query($db_conn,$sql);
		if(!$query){
				$glogger->fatal("添加/更新用户查看历史时数据库操作失败".$sql);
		}
   	}
}

/**
 * 检查是否在运送范围之内
 * *
 */
function could_devliver($areas) {
	global $db_conn;
	if (! is_array ( $areas )) {
		return false;
	}
	$query_area = "SELECT * from shipping_method_area where is_delete=0";
	$area = mysqli_query($db_conn ) or die ( mysqli_error ($localhost),$query_area);
	while ( $order_area = mysqli_fetch_assoc ( $area ) ) {
		// 如果是全国范围的话，
		if (trim ( $order_area ['area'] ) == "*_*_*;") {
			return true;
		}
		
		foreach ( $areas as $area_item ) {
			if (strpos ( $order_area ['area'], $area_item ) > - 1) {
				return true;
			}
		}
	}
	return false;
}
/**
 * 检查商品是否属于特价商品
 * 
 * @param unknown $product        	
 */
function phpshop123_is_special_price($product) {
	$curr_date = date ( "Y-m-d" );
	
	// 检查产品是否在优惠期之内，如果在优惠期之内，那么产品的价格就是优惠价格
	if ($product ['is_promotion'] == 1 && $curr_date >= $product ['promotion_start'] && $curr_date <= $product ['promotion_end']) {
		return true;
 }
 	return false;
}