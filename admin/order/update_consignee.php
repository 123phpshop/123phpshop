<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_consignees = "-1";
if (isset($_GET['user_id'])) {
  $colname_consignees = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_consignees = sprintf("SELECT * FROM user_consignee WHERE user_id = %s and is_delete=0 ", $colname_consignees);
$consignees = mysql_query($query_consignees, $localhost) or die(mysql_error());
$row_consignees = mysql_fetch_assoc($consignees);
$totalRows_consignees = mysql_num_rows($consignees);

$colname_order_id = "-1";
if (isset($_GET['order_id'])) {
  $colname_order_id = (get_magic_quotes_gpc()) ? $_GET['order_id'] : addslashes($_GET['order_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_order_id = sprintf("SELECT * FROM orders WHERE id = %s and is_delete=0 ", $colname_order_id);
$order_id = mysql_query($query_order_id, $localhost) or die(mysql_error());
$row_order_id = mysql_fetch_assoc($order_id);
$totalRows_order_id = mysql_num_rows($order_id);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <p class="phpshop123_title">更新订单收货人</p>
  <table width="100%" border="0" class="phpshop123_list_box">
    <tr>
      <th width="4%" scope="col">选择</th>
      <th width="24%" scope="col">收货人</th>
      <th width="12%" scope="col">省份</th>
      <th width="12%" scope="col">城市</th>
      <th width="12%" scope="col">区县</th>
      <th width="12%" scope="col">地址</th>
      <th width="12%" scope="col">手机</th>
      <th width="12%" scope="col">邮编</th>
    </tr>
    <?php do { ?>
      <tr>
        <td> 
          <input type="radio" name="radiobutton" value="radiobutton" />
         </td>
        <td><?php echo $row_consignees['name']; ?></td>
        <td><?php echo $row_consignees['province']; ?></td>
        <td><?php echo $row_consignees['city']; ?></td>
        <td><?php echo $row_consignees['district']; ?></td>
        <td><?php echo $row_consignees['address']; ?></td>
        <td><?php echo $row_consignees['mobile']; ?></td>
        <td><?php echo $row_consignees['zip']; ?></td>
      </tr>
      <?php } while ($row_consignees = mysql_fetch_assoc($consignees)); ?>
  </table>
  
</form>
</body>
</html>