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

header ( 'Content-type: application/json' );
require_once ('Connections/localhost.php');
?>
<?php
// 初始化结果数据结果
$result = array (
		'code' => '0',
		'message' => 'SUCCEED' 
);
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
	$theValue = (! get_magic_quotes_gpc ()) ? addslashes ( $theValue ) : $theValue;
	
	switch ($theType) {
		case "text" :
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
		case "long" :
		case "int" :
			$theValue = ($theValue != "") ? intval ( $theValue ) : "NULL";
			break;
		case "double" :
			$theValue = ($theValue != "") ? "'" . doubleval ( $theValue ) . "'" : "NULL";
			break;
		case "date" :
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
		case "defined" :
			$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			break;
	}
	return $theValue;
}

try {
	// 检查用户是否登录，如果没有登录的话，那么不能调用
	if (! isset ( $_SESSION ['user_id'] )) {
		throw new Exception ( "请登录后重试！" );
	}
	
	// 这里对字段进行验证
	$validation->set_rules ( 'product_id', '', 'required|is_natural_no_zero' );
	if (! $validation->run ()) {
		$logger->warn ( "用户在取消收藏的时候提交了参数错误，ip是." . $_SERVER ['REMOTE_ADDR'] . ", 企图收藏的商品id是：" . $_POST ['product_id'] );
		throw new Exception ( "参数错误！" );
	}
	
	// 检查商品是否存在，如果不存在，那么告知
	$colname_product = "-1";
	if (isset ( $_POST ['product_id'] )) {
		$colname_product = (get_magic_quotes_gpc ()) ? $_POST ['product_id'] : addslashes ( $_POST ['product_id'] );
	}
	mysql_select_db ( $database_localhost, $localhost );
	$query_product = sprintf ( "SELECT * FROM product WHERE is_delete=0 and id = %s", $colname_product );
	$product = mysql_query ( $query_product, $localhost ) or die ( mysql_error () );
	$row_product = mysql_fetch_assoc ( $product );
	$totalRows_product = mysql_num_rows ( $product );
	if ($totalRows_product == 0) {
		throw new Exception ( "商品不存在，请刷新后重试！" );
	}
	
	// 检查用户是否收藏过这个商品，如果还没有收藏，那么告知完成
	$colname_user_favorite = "-1";
	if (isset ( $_SESSION ['user_id'] )) {
		$colname_user_favorite = (get_magic_quotes_gpc ()) ? $_SESSION ['user_id'] : addslashes ( $_SESSION ['user_id'] );
	}
	mysql_select_db ( $database_localhost, $localhost );
	$query_user_favorite = sprintf ( "SELECT * FROM user_favorite WHERE user_id = %s and product_id=%s", $colname_user_favorite, $colname_product );
	$user_favorite = mysql_query ( $query_user_favorite, $localhost );
	if(!$user_favorite){$logger->fatal("数据库操作失败:".$query_user_favorite);}

	
	$row_user_favorite = mysql_fetch_assoc ( $user_favorite );
	$totalRows_user_favorite = mysql_num_rows ( $user_favorite );
	if ($totalRows_user_favorite == 0) {
		$logger->debug("貌似没有收藏过呀".$query_user_favorite);
		echo json_encode ( $result );return;
	}
	
	// 检查是否已经取消过了，如果已经取消了，那么没有取消的话
	if ($row_user_favorite ['is_delete'] == 0) {
		// 如果用户确实收藏过这个商品，更新记录
		$insertSQL = sprintf ( "update user_favorite set is_delete=1 where user_id='%s' and product_id='%s'", GetSQLValueString ( $colname_user_favorite, "int" ), GetSQLValueString ( $_POST ['product_id'], "int" ) );
		$logger->debug("开始更新".$query_user_favorite);
		mysql_select_db ( $database_localhost, $localhost );
		$Result1 = mysql_query ( $insertSQL, $localhost );
		if (! $Result1) {
			$logger->fatal ( "收藏失败:".$insertSQL);
			throw new Exception ( "系统错误，收藏失败，请稍后重试！" );
		}
	}
	// 如果已经取消了的话
	echo json_encode ( $result );
	return;
} catch ( Exception $ex ) {
	$result ['code'] = 1;
	$result ['message'] = $ex->getMessage ();
	echo json_encode ( $result );
	return;
}
?>