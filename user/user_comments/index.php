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

$maxRows_comments = 50;
$pageNum_comments = 0;
if (isset($_GET['pageNum_comments'])) {
  $pageNum_comments = $_GET['pageNum_comments'];
}
$startRow_comments = $pageNum_comments * $maxRows_comments;

$colname_comments = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_comments = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$where_string=_get_comment_where_query_string();
$query_comments = "SELECT * FROM product_comment WHERE user_id = $colname_comments  $where_string and  is_delete=0 ORDER BY id DESC";
$query_limit_comments = sprintf("%s LIMIT %d, %d", $query_comments, $startRow_comments, $maxRows_comments);
$comments = mysql_query($query_limit_comments, $localhost) or die(mysql_error());
$row_comments = mysql_fetch_assoc($comments);

if (isset($_GET['totalRows_comments'])) {
  $totalRows_comments = $_GET['totalRows_comments'];
} else {
  $all_comments = mysql_query($query_comments);
  $totalRows_comments = mysql_num_rows($all_comments);
}
$totalPages_comments = ceil($totalRows_comments/$maxRows_comments)-1;

$queryString_comments = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_comments") == false && 
        stristr($param, "totalRows_comments") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_comments = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_comments = sprintf("&totalRows_comments=%d%s", $totalRows_comments, $queryString_comments);

function _get_comment_where_query_string(){
	
	$result="";
	
	if(isset($_GET['message']) && trim($_GET['message'])!=''){
		$result.=" and message like '%".$_GET['message']."%'";
	}
	
 	return $result;
	
}
?>