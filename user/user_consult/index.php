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
$where_query_string=_get_consult_where_query_string();
$maxRows_consult = 50;
$pageNum_consult = 0;
if (isset($_GET['pageNum_consult'])) {
  $pageNum_consult = $_GET['pageNum_consult'];
}
$startRow_consult = $pageNum_consult * $maxRows_consult;

$colname_consult = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_consult = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_consult = sprintf("SELECT * FROM product_consult WHERE user_id = %s and is_delete=0 %s ORDER BY id DESC", $colname_consult,$where_query_string);
$query_limit_consult = sprintf("%s LIMIT %d, %d", $query_consult, $startRow_consult, $maxRows_consult);
$consult = mysql_query($query_limit_consult, $localhost) or die(mysql_error());
$row_consult = mysql_fetch_assoc($consult);

if (isset($_GET['totalRows_consult'])) {
  $totalRows_consult = $_GET['totalRows_consult'];
} else {
  $all_consult = mysql_query($query_consult);
  $totalRows_consult = mysql_num_rows($all_consult);
}
$totalPages_consult = ceil($totalRows_consult/$maxRows_consult)-1;

$queryString_consult = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_consult") == false && 
        stristr($param, "totalRows_consult") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_consult = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_consult = sprintf("&totalRows_consult=%d%s", $totalRows_consult, $queryString_consult);

function _get_consult_where_query_string(){
	
	$result="";
	
	if(isset($_GET['content']) && trim($_GET['content'])!=''){
		$result.=" and content like '%".$_GET['content']."%'";
	}
	
	if(isset($_GET['is_replied']) && trim($_GET['is_replied'])!='' ){
	 $result.=" and is_replied = '".$_GET['is_replied']."'";
	}
	
	if(isset($_GET['create_from']) && trim($_GET['create_from'])!='' && isset($_GET['create_end']) && trim($_GET['create_end'])!=''  ){
		$result.=" and create_time between '".$_GET['create_from']."' and '".$_GET['create_end']."'";
	}
		
	return $result;
	
}
?>