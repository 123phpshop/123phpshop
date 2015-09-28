<?php require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);
$query_shipping_methods = "SELECT * FROM shipping_method";
$shipping_methods = mysql_query($query_shipping_methods, $localhost) or die(mysql_error());
$row_shipping_methods = mysql_fetch_assoc($shipping_methods);
$totalRows_shipping_methods = mysql_num_rows($shipping_methods);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<p>配送方式列表</p>
<table width="100%" border="1" align="center">
  <tr>
    <td>id</td>
    <td>name</td>
    <td>desc</td>
    <td>is_activated</td>
    <td>is_cod</td>
    <td>操作</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_shipping_methods['id']; ?>&nbsp; </td>
      <td><a href="detail.php?recordID=<?php echo $row_shipping_methods['id']; ?>"> <?php echo $row_shipping_methods['name']; ?>&nbsp; </a> </td>
      <td><?php echo $row_shipping_methods['desc']; ?>&nbsp; </td>
      <td><?php echo $row_shipping_methods['is_activated']; ?>&nbsp; </td>
      <td><?php echo $row_shipping_methods['is_cod']; ?></td>
      <td><a href="activate.php?id=<?php echo $row_shipping_methods['id']; ?>">激活</a> <a href="../shipping_method_area/index.php?shipping_method_id=<?php echo $row_shipping_methods['id']; ?>">配送区域</a> <a href="update.php?id=<?php echo $row_shipping_methods['id']; ?>">编辑</a> <a href="remove.php?id=<?php echo $row_shipping_methods['id']; ?>">删除</a></td>
    </tr>
    <?php } while ($row_shipping_methods = mysql_fetch_assoc($shipping_methods)); ?>
</table>
<div align="right"><br>
  <?php echo $totalRows_shipping_methods ?> 记录 总数
  </p>
</div>
</body>
</html>
<?php
mysql_free_result($shipping_methods);
?>
