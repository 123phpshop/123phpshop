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
 ?>
<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once('../../Connections/lib/product.php'); ?>
<?php
$colname_products = "-1";
$doc_url="order.html#detail";
$support_email_question="查看订单详细";
if (isset($_GET['recordID'])) {
  $colname_products = (get_magic_quotes_gpc()) ? $_GET['recordID'] : addslashes($_GET['recordID']);
}
mysql_select_db($database_localhost, $localhost);
$query_products = sprintf("SELECT order_item.*,product.is_shipping_free,product.is_promotion,product.promotion_price,product.promotion_start,product.promotion_end,product.name as product_name FROM order_item inner join product on product.id=order_item.product_id WHERE order_item.order_id = %s and order_item.is_delete = 0", $colname_products);
$products = mysql_query($query_products, $localhost) or die(mysql_error());
$row_products = mysql_fetch_assoc($products);
$totalRows_products = mysql_num_rows($products);


  
$maxRows_DetailRS1 = 50;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['recordID'];
$query_DetailRS1 = "SELECT orders.*,shipping_method.name as shipping_method_name,user.username FROM `orders` inner join user on user.id=orders.user_id left join shipping_method on orders.shipping_method=shipping_method.id WHERE orders.id = $recordID ";
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;


mysql_select_db($database_localhost, $localhost);
$query_log_DetailRS1 = "SELECT * FROM `order_log`  WHERE order_id = $recordID";
$log_DetailRS1 = mysql_query($query_log_DetailRS1, $localhost);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
<style>
.price_span{
	color:red;
	font-weight:bold;
}
</style>
</head>

<body>
		
<span class="phpshop123_title">订单详细</span>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<table width="100%" border="0" align="center" class="phpshop123_form_box">
  <tr>
    <td>ID</td>
    <td><?php echo $row_DetailRS1['id']; ?> </td>
  </tr>
  <tr>
    <td>序列号</td>
    <td><?php echo $row_DetailRS1['sn']; ?> </td>
  </tr>
  <tr>
    <td>用户</td>
    <td><?php echo $row_DetailRS1['username']; ?>[<a href="update_order_user.php?id=<?php echo $row_DetailRS1['id']; ?>">更改订单用户</a>]<?php 
	$support_email_question="修改订单用户";
	$doc_url="order.html#update_user";
	include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");
?></td>
  </tr>
  <tr>
    <td>应付</td>
    <td><span class="price_span">￥<?php echo $row_DetailRS1['should_paid']; ?></span></td>
  </tr>
  <tr>
    <td>商品总额</td>
    <td><span class="price_span">￥<?php echo $row_DetailRS1['products_total']; ?></span></td>
  </tr>
  <tr>
    <td>运费</td>
    <td><span class="price_span">￥<?php echo $row_DetailRS1['shipping_fee']; ?></span></td>
  </tr>
   <tr>
    <td>促销折减费用</td>
    <td><span class="price_span">￥<?php echo $row_DetailRS1['promotion_fee']; ?></span></td>
  </tr>
   <tr>
     <td>优惠</td>
     <td><?php 
	 
	 if($row_DetailRS1['promotion_id']!=''){
		mysql_select_db($database_localhost, $localhost);
		$query_promotion_names = "SELECT * FROM promotion WHERE id in (".$row_DetailRS1['promotion_id'].")";
		$promotion_names = mysql_query($query_promotion_names, $localhost) or die(mysql_error());
 		$totalRows_promotion_names = mysql_num_rows($promotion_names);	?>	
	 <?php do { ?>
         <a href="../promotion/update.php?id=<?php echo $row_promotion_names['id']; ?>"><?php echo $row_promotion_names['name']; ?></a>
       <?php } while ($row_promotion_names = mysql_fetch_assoc($promotion_names)); ?>
	  <?php }else{
	 	echo "未设置";
	 }
 	 ?>
     </td>
   </tr>
  <tr>
    <td>订单状态</td>
    <td><?php echo $order_status[$row_DetailRS1['order_status']];; ?> </td>
  </tr>
   <tr>
    <td>创建时间</td>
    <td><?php echo $row_DetailRS1['create_time']; ?> </td>
  </tr>
  <tr>
    <td>快递方式</td>
    <td><?php echo $row_DetailRS1['shipping_method_name']; ?> </td>
  </tr>
  <tr>
    <td>支付方式</td>
    <td><?php echo $pay_methomd[$row_DetailRS1['payment_method']]; ?> </td>
  </tr>
  <tr>
    <td>需要发票[<a href="update_order_user.php?id=<?php echo $row_DetailRS1['id']; ?>">更改发票信息</a>]</td>
    <td><?php echo $row_DetailRS1['invoice_is_needed']=='0'?"否":"√"; ?> </td>
  </tr>
  <?php if($row_DetailRS1['invoice_is_needed']=='1'){ ?>
  <tr>
    <td>发票抬头</td>
    <td><?php echo $row_DetailRS1['invoice_title']; ?> </td>
  </tr>
  <tr>
    <td>发票留言</td>
    <td><?php echo $row_DetailRS1['invoice_message']; ?> </td>
  </tr>
  <?php } ?>
  <tr>
    <td>可收货时间</td>
    <td><?php echo $row_DetailRS1['please_delivery_at']==0?"未设置":$please_deliver_at[$row_DetailRS1['please_delivery_at']]; ?> </td>
  </tr>
  <tr>
    <td>备注</td>
    <td><?php echo $row_DetailRS1['memo']==null?"未设置":$row_DetailRS1['memo']; ?> </td>
  </tr>
</table>

<span><span class="phpshop123_title">商品列表</span></span><?php 
$support_email_question="添加订单商品";
$doc_url="order.html#add_product";
include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");
?>[<a href="add_order_item.php?order_id=<?php echo $row_DetailRS1['id']; ?>">添加商品</a>]
<?php if ($totalRows_products > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th scope="col">名称</th>
      <th scope="col">数量</th>
      <th scope="col">赠品</th>
      <th scope="col">免运费</th>
      <th scope="col">优惠</th>
	  <th scope="col">单价</th>
      <th scope="col">应付</th>
      <th scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td scope="col"><a href="../product/update.php?id=<?php echo $row_products['product_id']; ?>"><?php echo $row_products['product_name']; ?></a> <span style="color:#999999"><?php echo str_replace(";","	",$row_products['attr_value']); ?></span></td>
        <td scope="col"><div align="center"><?php echo $row_products['quantity']; ?></div></td>
         <td scope="col"><div align="center"><?php if($row_products['is_present']==1){?><span style="background-color:#FF0000;color:#FFFFFF;padding:0 5px;">赠品</span><?php } ?></div></td>
        <td scope="col"><?php if($row_products['is_shipping_free']==1){ ?><span style="background-color:#FF0000;color:#FFFFFF;padding:0 5px;">免运费</span><?php } ?></td>
        <td scope="col"><div align="center"><?php if(phpshop123_is_special_price($row_products)){?><span style="background-color:#FF0000;color:#FFFFFF;padding:0 5px;">优惠</span><?php } ?></div></td>
		 <td scope="col" style="font-weight:bold;text-align:center;">￥<?php echo $row_products['should_pay_price']; ?></td>
		 
        <td scope="col" style="color:#FF0000;font-weight:bold;text-align:center;">￥<?php echo (float)$row_products['should_pay_price']*$row_products['quantity']; ?></td>
        <td scope="col"><a onclick="return confirm('您确实要删除这个商品吗？')" href="remove_order_item.php?id=<?php echo $row_products['id']; ?>">删除</a> <a href="update_order_item.php?id=<?php echo $row_products['id']; ?>"></a></td>
      </tr>
      <?php } while ($row_products = mysql_fetch_assoc($products)); ?>
  </table>
  <?php } // Show if recordset not empty ?><p class="phpshop123_title">订单处理过程</p>
<table width="100%" border="1"  class="phpshop123_list_box">
 <?php while ($row_log_DetailRS1 = mysql_fetch_assoc($log_DetailRS1)){ ?>
  <tr>
    <td width="10%"><?php echo $row_log_DetailRS1['create_time'];?></td>
    <td width="90%"><?php echo $row_log_DetailRS1['message'];?></td>
  </tr>
  <?php  } ?>
</table>
 <p><span class="phpshop123_title">收货人</span>[<a href="update_order_user.php?id=<?php echo $row_DetailRS1['id']; ?>">修改</a>][<a href="add_consignee.php?order_id=<?php echo $row_DetailRS1['id']; ?>&user_id=<?php echo $row_DetailRS1['user_id']; ?>">添加收货人</a>]</p>
 <table width="100%" border="1" class="phpshop123_list_box">
  <tr>
    <th scope="col">收货人姓名</th>
    <th scope="col">手机</th>
    <th scope="col">省份</th>
    <th scope="col">城市</th>
    <th scope="col">区域</th>
    <th scope="col">地址</th>
    <th scope="col">邮编</th>
  </tr>
  <tr>
    <td scope="col"><?php echo $row_DetailRS1['consignee_name']; ?></td>
    <td scope="col"><?php echo $row_DetailRS1['consignee_mobile']; ?></td>
    <td scope="col"><?php echo $row_DetailRS1['consignee_province']; ?></td>
    <td scope="col"><?php echo $row_DetailRS1['consignee_city']; ?></td>
    <td scope="col"><?php echo $row_DetailRS1['consignee_district']; ?></td>
    <td scope="col"><?php echo $row_DetailRS1['consignee_address']; ?></td>
    <td scope="col"><?php echo $row_DetailRS1['consignee_zip']; ?></td>
  </tr>
</table>
</body>
</html>