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
$colname_order = "-1";
if (isset($_GET['sn'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['sn'] : addslashes($_GET['sn']);
}
mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT orders.*,express_company.name as express_company_name  FROM orders left join express_company on orders.express_company_id=express_company.id WHERE orders.sn = '%s' and orders.is_delete=0 and orders.user_id='".$_SESSION['user_id']."'", $colname_order);
$order = mysql_query($query_order, $localhost) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

if($totalRows_order==0){
   $insertGoTo = "index.php";
   header(sprintf("Location: %s", $insertGoTo));
}
  

mysql_select_db($database_localhost, $localhost);
$query_order_items = "SELECT * FROM order_item WHERE order_id = ".$row_order['id'];
$order_items = mysql_query($query_order_items, $localhost) or die(mysql_error());
$row_order_items = mysql_fetch_assoc($order_items);
$totalRows_order_items = mysql_num_rows($order_items);


mysql_select_db($database_localhost, $localhost);
$query_log_DetailRS1 = "SELECT * FROM `order_log`  WHERE order_id = ".$row_order['id'];
$log_DetailRS1 = mysql_query($query_log_DetailRS1, $localhost);


?>