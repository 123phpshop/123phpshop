<?php require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);
$query_shipping_methods = "SELECT * FROM shipping_method where is_delete=0";
$shipping_methods = mysql_query($query_shipping_methods, $localhost) or die(mysql_error());
$row_shipping_methods = mysql_fetch_assoc($shipping_methods);
$totalRows_shipping_methods = mysql_num_rows($shipping_methods);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">配送方式列表</p>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td>名称</td>
    <td>介绍</td>
    <td><div align="center">是否被激活</div></td>
    <td>操作</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><label></label>
      <a href="detail.php?recordID=<?php echo $row_shipping_methods['id']; ?>"> <?php echo $row_shipping_methods['name']; ?>&nbsp; </a> </td>
      <td><?php echo $row_shipping_methods['desc']; ?>&nbsp; </td>
      <td><div align="center"><?php echo $row_shipping_methods['is_activated']==1?"√":""; ?>&nbsp; </div></td>
      <td><?php if( $row_shipping_methods['is_activated']==0){ ?><a href="activate.php?id=<?php echo $row_shipping_methods['id']; ?>">激活</a><?php }else{ ?><a href="deactivate.php?id=<?php echo $row_shipping_methods['id']; ?>" onclick="return confirm('您确实要卸载这中配送方式吗？')">卸载</a><?php } ?> <a href="/admin/shipping_method_area/<?php echo $row_shipping_methods['config_file_path']; ?>/add.php">添加配送区域</a> <a href="../shipping_method_area/index.php?shipping_method_id=<?php echo $row_shipping_methods['id']; ?>">配送区域</a> <a href="update.php?id=<?php echo $row_shipping_methods['id']; ?>">编辑</a> <a href="remove.php?id=<?php echo $row_shipping_methods['id']; ?>" onclick="return confirm('您确实要删除这条记录吗？')">删除</a></td>
    </tr>
    <?php } while ($row_shipping_methods = mysql_fetch_assoc($shipping_methods)); ?>
</table>
<div align="right"><br>
  记录总数：<?php echo $totalRows_shipping_methods ?></p>
</div>
</body>
</html>
<?php
mysql_free_result($shipping_methods);
?>
