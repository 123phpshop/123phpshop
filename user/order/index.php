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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
$maxRows_orders = 10;
$pageNum_orders = 0;
if (isset($_GET['pageNum_orders'])) {
  $pageNum_orders = $_GET['pageNum_orders'];
}
$startRow_orders = $pageNum_orders * $maxRows_orders;

$colname_orders = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_orders = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$where=_get_order_where($_GET);

$query_orders = "SELECT * from orders $where order by id desc";
$query_limit_orders = sprintf("%s LIMIT %d, %d ", $query_orders, $startRow_orders, $maxRows_orders);
$orders = mysql_query($query_limit_orders, $localhost) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);

if (isset($_GET['totalRows_orders'])) {
  $totalRows_orders = $_GET['totalRows_orders'];
} else {
  $all_orders = mysql_query($query_orders);
  $totalRows_orders = mysql_num_rows($all_orders);
}
$totalPages_orders = ceil($totalRows_orders/$maxRows_orders)-1;


$queryString_order = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_orders") == false && 
        stristr($param, "totalRows_order") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_order = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_order = sprintf("&totalRows_order=%d%s", $totalRows_orders, $queryString_order);

?>

<?php 
function _get_order_where($get){
	
 	$where_string=' where is_delete=0 and user_id='.$_SESSION['user_id'];
	
	if(isset($get['sn']) && trim($get['sn'])!=''){
 		$where_string.=" and sn='".$get['sn']."'";
	}
	
	if(isset($get['order_status']) && trim($get['order_status'])!=''){
 		$where_string.=" and ";
		$where_string.=" order_status='".$get['order_status']."'";
	}
	
	if( isset($get['delivery_from']) && trim($get['delivery_from'])!='' && isset($get['delivery_end']) && trim($get['delivery_end'])!=''){
 		$where_string.=" and ";
		$where_string.=" delivery_at between '".$get['delivery_from']. "  00:00:00' and '" .$get['delivery_end'] ."  23:59:59'";
	}
	
	if(isset($get['pay_from']) && trim($get['pay_from'])!='' && isset($get['pay_end']) && trim($get['pay_end'])!=''){
 		$where_string.=" and ";
		$where_string.=" pay_at between '".$get['pay_from']. "  00:00:00' and '" .$get['pay_end']."  23:59:59'" ;
	
	}
	
	if(isset($get['create_from']) && trim($get['create_from'])!='' && isset($get['create_from']) && trim($get['create_end'])!=''){
		
 		$where_string.=" and ";
		$where_string.=" create_time between '".$get['create_from']. "  00:00:00' and '" .$get['create_end']." 23:59:59'" ;
	}

	return $where_string;
}


?>