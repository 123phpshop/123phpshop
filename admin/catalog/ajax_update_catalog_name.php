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
$result="true";
$colname_catalog = "-1";
if (isset($_POST['name'])) {
  $colname_catalog = (get_magic_quotes_gpc()) ? $_POST['name'] : addslashes($_POST['name']);
}

$colname_id = "-1";
if (isset($_POST['id'])) {
  $colname_id = (get_magic_quotes_gpc()) ? $_POST['id'] : addslashes($_POST['id']);
}

mysql_select_db($database_localhost, $localhost);
$query_catalog = sprintf("SELECT * FROM `catalog` WHERE name = '%s' and id!='%s'", $colname_catalog, $colname_id);
$catalog = mysql_query($query_catalog, $localhost) ;
if(!$catalog){$logger->fatal("数据库操作失败:".$query_catalog);}
$row_catalog = mysql_fetch_assoc($catalog);
$totalRows_catalog = mysql_num_rows($catalog);
if($totalRows_catalog>0){
	$result="false";
}
?>
<?php
mysql_free_result($catalog);
die($result);
?>