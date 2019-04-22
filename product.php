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
require_once ('Connections/localhost.php');
?>
<?php require_once('Connections/lib/product.php'); ?>
<?php

// 这里对字段进行验证
$_POST=$_REQUEST;
$validation->set_rules('id', '', 'required|is_natural_no_zero');
if (!$validation->run())
{
	$MM_redirectLoginFailed = "/index.php";
	header("Location: ". $MM_redirectLoginFailed );return;
}
$user_favorited=false;
$is_in_promotion = false;
$colname_product = "-1";
if (isset ( $_GET ['id'] )) {
	$colname_product = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
}

// 根据id获取商品信息

$query_product = sprintf ( "SELECT product.*,brands.name as brand_name FROM product left join brands on product.brand_id=brands.id WHERE product.id = %s and product.is_delete=0 and product.is_on_sheft=1", $colname_product );
$product = mysqli_query($localhost,$query_product);
if(!$product){$logger->fatal("数据库操作失败:".$query_product);}


$row_product = mysqli_fetch_assoc ( $product );
$totalRows_product = mysqli_num_rows ( $product );
// 如果找不到这个商品的话,那么跳转到主页
if ($totalRows_product == 0) {
	$remove_succeed_url = "/";
	header ( "Location: " . $remove_succeed_url );
}

// 获取商品的图品
$colname_product_images = "-1";
if (isset ( $_GET ['id'] )) {
	$colname_product_images = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
}

$query_product_images = sprintf ( "SELECT * FROM product_images WHERE product_id = %s  and is_delete=0", $colname_product_images );
$product_images = mysqli_query ($localhost,$query_product_images ) ;
if(!$product_images){$logger->fatal("数据库操作失败:".$query_product_images);}

$row_product_images = mysqli_fetch_assoc ( $product_images );
$totalRows_product_images = mysqli_num_rows ( $product_images );

// 获取商品的小图
$colname_product_image_small = "-1";
if (isset ( $_GET ['id'] )) {
	$colname_product_image_small = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
}

$query_product_image_small = sprintf ( "SELECT * FROM product_images WHERE product_id = %s", $colname_product_image_small );
$product_image_small = mysqli_query($localhost,$query_product_image_small);
if(!$product_image_small){$logger->fatal("数据库操作失败:".$query_product_image_small);}


$row_product_image_small = mysqli_fetch_assoc ( $product_image_small );
$totalRows_product_image_small = mysqli_num_rows ( $product_image_small );
$could_deliver = false;
$areas = get_deliver_areas ();
$colname_consignee = "-1";
if (isset ( $_SESSION ['user_id'] )) {
	$colname_consignee = (get_magic_quotes_gpc ()) ? $_SESSION ['user_id'] : addslashes ( $_SESSION ['user_id'] );
}

$query_consignee = sprintf ( "SELECT * FROM user_consignee WHERE user_id = %s and is_delete=0 and is_default=1", $colname_consignee );
$consignee = mysqli_query($localhost,$query_consignee);
if(!$consignee){$logger->fatal("数据库操作失败:".$query_consignee);}

$row_consignee = mysqli_fetch_assoc ( $consignee );
$totalRows_consignee = mysqli_num_rows ( $consignee );
$areas = array ();
// 如果有收货人记录，但是session中么有设置的话
if ($totalRows_consignee > 0 && ! isset ( $_SESSION ['user'] ['province'] ) && ! isset ( $_SESSION ['user'] ['city'] ) && ! isset ( $_SESSION ['user'] ['district'] )) {
	
	$areas [] = $row_consignee ['province'] . "_*_*";
	$areas [] = $row_consignee ['province'] . "_" . $row_consignee ['city'] . "_*";
	$areas [] = $row_consignee ['province'] . "_" . $row_consignee ['city'] . "_" . $row_consignee ['district'];
} else {
	
	// 设置默认的收货人数据
	$_SESSION ['user'] = array ();
	$global_default_province = "上海";
	$global_default_city = "上海";
	$global_default_district = "黄浦区";
	$areas [] = $global_default_province . "_*_*";
	$areas [] = $global_default_province . "_" . $global_default_city . "_*";
	$areas [] = $global_default_province . "_" . $global_default_city . "_" . $global_default_district;
}

// 检查是否可以发货
$could_deliver = could_devliver ( $areas );

// 获取分类信息

$query_product_catalog = "SELECT id,name FROM `catalog` WHERE id = " . $row_product ['catalog_id'];
$product_catalog = mysqli_query($localhost,$query_product_catalog);
if(!$product_catalog){$logger->fatal("数据库操作失败:".$query_product_catalog);}
$row_product_catalog = mysqli_fetch_assoc ( $product_catalog );
$totalRows_product_catalog = mysqli_num_rows ( $product_catalog );

// 检查用户是否已经收藏
$colname_user_favorite = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_user_favorite = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}


$query_user_favorite = sprintf("SELECT * FROM user_favorite WHERE user_id = %s and product_id=%s and is_delete=0", $colname_user_favorite,$colname_product);
$user_favorite = mysqli_query($localhost,$query_user_favorite);
if(!$user_favorite){$logger->fatal("数据库操作失败:".$query_user_favorite);}
$row_user_favorite = mysqli_fetch_assoc($user_favorite);
$totalRows_user_favorite = mysqli_num_rows($user_favorite);
if($totalRows_user_favorite>0){
	$user_favorited=true;	
}
include($template_path."product.php");
?>