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
<?php require_once('Connections/localhost.php'); 

// 这里对字段进行验证
$_POST=$_GET;
$validation->set_rules('keywords', '', 'required|max_legnth[36]|min_legnth[1]|alpha_numeric');
if (!$validation->run())
{
header("Location:/index.php");
}

$currentPage = $_SERVER["PHP_SELF"];
$colname_products = "-1";
if (isset($_GET['keywords'])) {
  $colname_products = (get_magic_quotes_gpc()) ? $_GET['keywords'] : addslashes($_GET['keywords']);
}
$order_by=_get_order_by();
$maxRows_products = 20;
$pageNum_products = 0;
if (isset($_GET['pageNum_products'])) {
  $pageNum_products = $_GET['pageNum_products'];
}
$startRow_products = $pageNum_products * $maxRows_products;


$query_products = "SELECT * FROM product WHERE name like '%".$colname_products."%' and is_delete=0 $order_by";
$query_limit_products = sprintf("%s LIMIT %d, %d", $query_products, $startRow_products, $maxRows_products);
$products = mysqli_query($localhost,$query_limit_products);
if(!$products){$logger->fatal("数据库操作失败:".$query_limit_products);}
//$row_products = mysqli_fetch_assoc($products);

if (isset($_GET['totalRows_products'])) {
  $totalRows_products = $_GET['totalRows_products'];
} else {
  $all_products = mysqli_query($localhost,$query_products);
  $totalRows_products = mysqli_num_rows($all_products);
}
$totalPages_products = ceil($totalRows_products/$maxRows_products)-1;

$queryString_products = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_products") == false && 
        stristr($param, "totalRows_products") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_products = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_products = sprintf("&totalRows_products=%d%s", $totalRows_products, $queryString_products);
 
include($template_path."search.php");
?>
