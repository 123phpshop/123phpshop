<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_order = "-1";
if (isset($_GET['id'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE id = %s", $colname_order);
$order = mysql_query($query_order, $localhost) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

$colname_order_items = "-1";
if (isset($_GET['id'])) {
  $colname_order_items = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_order_items = sprintf("SELECT * FROM order_item WHERE order_id = '%s'", $colname_order_items);
$order_items = mysql_query($query_order_items, $localhost) or die(mysql_error());
$row_order_items = mysql_fetch_assoc($order_items);
$totalRows_order_items = mysql_num_rows($order_items);

$doc_url="order.html#update_product";
$support_email_question="更新订单";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">更新订单中的商品信息</p>
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
    <?php } while ($row_order_items = mysql_fetch_assoc($order_items)); ?>
</table>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($order);

mysql_free_result($order_items);
?>
