<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_shipping_method = "-1";
if (isset($_GET['shipping_method_id'])) {
  $colname_shipping_method = (get_magic_quotes_gpc()) ? $_GET['shipping_method_id'] : addslashes($_GET['shipping_method_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_shipping_method = sprintf("SELECT id, shipping_method_id, area, shipping_by_quantity, name FROM shipping_method_area WHERE shipping_method_id = %s and is_delete=0", $colname_shipping_method);
$shipping_method = mysql_query($query_shipping_method, $localhost) or die(mysql_error());
$row_shipping_method = mysql_fetch_assoc($shipping_method);
$totalRows_shipping_method = mysql_num_rows($shipping_method);

$colname_shipping_method_folder = "-1";
if (isset($_GET['shipping_method_id'])) {
  $colname_shipping_method_folder = (get_magic_quotes_gpc()) ? $_GET['shipping_method_id'] : addslashes($_GET['shipping_method_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_shipping_method_folder = sprintf("SELECT * FROM shipping_method WHERE id = %s", $colname_shipping_method_folder);
$shipping_method_folder = mysql_query($query_shipping_method_folder, $localhost) or die(mysql_error());
$row_shipping_method_folder = mysql_fetch_assoc($shipping_method_folder);
$totalRows_shipping_method_folder = mysql_num_rows($shipping_method_folder);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">配送区域列表</p>
<?php if ($totalRows_shipping_method > 0) { // Show if recordset not empty ?>

  <table width="100%" border="0" align="center" class="phpshop123_list_box">
    <tr>
      <td width="8%">ID</td>
      <td width="9%">名称</td>
      <td width="14%">计费标准</td>
      <td width="61%">区域</td>
      <td width="8%">操作</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_shipping_method['id']; ?>&nbsp; </td>
        <td><a href="detail.php?recordID=<?php echo $row_shipping_method['id']; ?>"> <?php echo $row_shipping_method['name']; ?>&nbsp; </a> </td>
        <td><?php echo $row_shipping_method['shipping_by_quantity']==1?"数量":"重量"; ?></td>
        <td><?php echo $row_shipping_method['area']; ?></td>
        <td><a href="remove.php?id=<?php echo $row_shipping_method['id']; ?>">删除</a> <a href="/admin/shipping_method_area/<?php echo $row_shipping_method_folder['config_file_path'];?>/update.php?id=<?php echo $row_shipping_method['id']; ?>">更新</a> &nbsp; </td>
      </tr>
      <?php } while ($row_shipping_method = mysql_fetch_assoc($shipping_method)); ?>
      </table>
  <br>
  记录总数:<?php echo $totalRows_shipping_method ?></p>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_shipping_method == 0) { // Show if recordset empty ?>
    <span class="phpshop123_infobox">没有记录！</span>
  <?php } // Show if recordset empty ?>
</body>
</html>
<?php
mysql_free_result($shipping_method);

mysql_free_result($shipping_method_folder);
?>
