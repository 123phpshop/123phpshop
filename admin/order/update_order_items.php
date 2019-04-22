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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_order = "-1";
if (isset($_GET['id'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$query_order = sprintf("SELECT * FROM orders WHERE id = %s", $colname_order);
$order = mysqli_query($localhost,$query_order);
if(!$order){$logger->fatal("数据库操作失败:".$query_order);}
$row_order = mysqli_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

$colname_order_items = "-1";
if (isset($_GET['id'])) {
  $colname_order_items = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$query_order_items = sprintf("SELECT * FROM order_item WHERE order_id = '%s'", $colname_order_items);
$order_items = mysqli_query($localhost,$query_order_items);
if(!$order_items){$logger->fatal("数据库操作失败:".$query_order_items);}
$row_order_items = mysqli_fetch_assoc($order_items);
$totalRows_order_items = mysql_num_rows($order_items);

$doc_url="order.html#update_product";
$support_email_question="更新订单";log_admin($support_email_question);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">更新订单中的商品信息<a href="index.php">
  <input style="float:right;" type="submit" name="Submit2" value="订单列表" />
</a></p>
<table width="100%" border="1" class="phpshop123_list_box">
  <tr>
    <th scope="col"> 产品名称</th>
    <th scope="col">属性</th>
    <th scope="col">数量</th>
    <th scope="col">价格</th>
    <th scope="col">操作</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_order_items['product_id']; ?></td>
      <td><?php echo $row_order_items['attr_value']; ?></td>
      <td><?php echo $row_order_items['quantity']; ?></td>
      <td><?php echo $row_order_items['should_pay_price']; ?></td>
      <td><a href="update_order_item.php?id=<?php echo $row_order_items['id']; ?>">更新</a> <a href="remove_order_item.php?id=<?php echo $row_order_items['id']; ?>">删除</a></td>
    </tr>
    <?php } while ($row_order_items = mysqli_fetch_assoc($order_items)); ?>
</table>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#new_consignee_form").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
             },
            mobile: {
                required: true,
                minlength: 11,
				digits:true   
            },
            address: {
                required: true,
                minlength: 3   
            },
 			zip: {
                required: true,
                minlength: 6,
				digits:true
            }
        } 
    });
});</script>
</body>
</html>