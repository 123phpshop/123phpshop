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
 ?><?php require_once('Connections/localhost.php'); ?>
<?php

// 这里对字段进行验证
$_POST=$_GET;
$validation->set_rules('id', '', 'required|is_natural_no_zero');
if (!$validation->run())
{
	$MM_redirectLoginFailed = "/index.php";
	header("Location: ". $MM_redirectLoginFailed );return;
}

$colname_news_catalog = "-1";
if (isset($_GET['id'])) {
  $colname_news_catalog = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_news_catalog = sprintf("SELECT * FROM news_catalog WHERE is_delete='0' and id = %s", $colname_news_catalog);
$news_catalog = mysql_query($query_news_catalog, $localhost) ;
if(!$news_catalog){$logger->fatal("数据库操作失败:".$query_news_catalog);}
$row_news_catalog = mysql_fetch_assoc($news_catalog);
$totalRows_news_catalog = mysql_num_rows($news_catalog);

$maxRows_news = 50;
$pageNum_news = 0;
if (isset($_GET['pageNum_news'])) {
  $pageNum_news = $_GET['pageNum_news'];
}
$startRow_news = $pageNum_news * $maxRows_news;

$colname_news = "-1";
if (isset($_GET['id'])) {
  $colname_news = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_news = sprintf("SELECT * FROM news WHERE is_delete='0'  and catalog_id = %s", $colname_news);
$query_limit_news = sprintf("%s LIMIT %d, %d", $query_news, $startRow_news, $maxRows_news);
$news = mysql_query($query_limit_news, $localhost) ;
if(!$news){$logger->fatal("数据库操作失败:".$query_limit_news);}
$row_news = mysql_fetch_assoc($news);
$totalRows_row_news = mysql_num_rows($news);

if (isset($_GET['totalRows_news'])) {
  $totalRows_news = $_GET['totalRows_news'];
} else {
  $all_news = mysql_query($query_news);
  $totalRows_news = mysql_num_rows($all_news);
}
$totalPages_news = ceil($totalRows_news/$maxRows_news)-1;

mysql_select_db($database_localhost, $localhost);
$query_news_catalogs = "SELECT * FROM news_catalog where is_delete='0'";
$news_catalogs = mysql_query($query_news_catalogs, $localhost) ;
if(!$news_catalogs){$logger->fatal("数据库操作失败:".$query_news_catalogs);}
$row_news_catalogs = mysql_fetch_assoc($news_catalogs);
$totalRows_news_catalogs = mysql_num_rows($news_catalogs);
include($template_path."news_list.php");
?>