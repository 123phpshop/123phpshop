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
global $www_root;
global $db_hostname_localhost;
global $db_username_localhost;
global $db_database_localhost;
global $db_password_localhost;
global $db_conn;
$www_root=$_SERVER['DOCUMENT_ROOT'];
$db_hostname_localhost=$hostname_localhost;
$db_username_localhost=$username_localhost;
$db_database_localhost=$database_localhost;
$db_password_localhost=$password_localhost;
$db_conn=$localhost;
define("ORDER_STATUS_REFUND",-300); 
define("ORDER_STATUS_REFUND_APPLIED",-250); 
define("ORDER_STATUS_RETURNED",-200); 
define("ORDER_STATUS_RETURNED_APPLIED",-150); 
define("ORDER_STATUS_WITHDRAWED",-100);
define("ORDER_STATUS_UNPAID",0);
define("ORDER_STATUS_PAID",100);  
define("ORDER_STATUS_DELIVERED",200);
define("ORDER_STATUS_RECIEVED",300);

$order_status[-300]='已退款';
$order_status[-250]='申请退款';
$order_status[-200]='已退货';
$order_status[-150]='申请退货';
$order_status[-100]='已撤销';
$order_status[0]='未付款';
$order_status[100]='已付款';
$order_status[200]='已发货';
$order_status[300]='已收货';

$const_promotion_types[1]="赠";
$const_promotion_types[2]="减";
//$const_promotion_types[3]="折";
$const_promotion_types[4]="免运费";

$const_promotion_limit[1]="全场";
$const_promotion_limit[2]="选定分类";
$const_promotion_limit[3]="选定品牌";
$const_promotion_limit[4]="选定商品";

$pay_way['在线支付']=100;
$pay_methomd[100]="支付宝";
$shipping_method[100]="免运费";
$please_deliver_at[1]="每天都可以";
$please_deliver_at[2]="工作日可以";
$please_deliver_at[3]="周六周日可以";

$product_type_selectable[1]="只是显示";
$product_type_selectable[2]="可单选";

$product_type_input_method[1]="手动录入";
$product_type_input_method[2]="列表选择";

$global_default_province="上海";
$global_default_city="上海";
$global_default_district="黄浦区";

$global_phpshop123_email_send_time[100]="订单支付完成";
$global_phpshop123_email_send_time[200]="订单创建";
$global_phpshop123_email_send_time[300]="订单撤销";
$global_phpshop123_email_send_time_array=array(100,200,300);
?>