<?php require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);
$query_leveles = "SELECT * FROM user_levels ORDER BY id ASC";
$leveles = mysql_query($query_leveles, $localhost) or die(mysql_error());
$row_leveles = mysql_fetch_assoc($leveles);
$totalRows_leveles = mysql_num_rows($leveles);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="file:///E|/vhost/123phpshop_v15/css/common_admin.css" rel="stylesheet" type="text/css" />
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">用户等级 </p>
<?php if ($totalRows_leveles > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" class="phpshop123_list_box">
    <tr>
      <th scope="col">等级</th>
      <th scope="col">最低消费金额</th>
      <th scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td scope="col"><a href="edit.php?id=<?php echo $row_leveles['id']; ?>"><?php echo $row_leveles['name']; ?></a></td>
        <td scope="col"><?php echo $row_leveles['min_consumption_amount']; ?></td>
        <td scope="col"><a href="remove.php?id=<?php echo $row_leveles['id']; ?>">删除</a> <a href="edit.php?id=<?php echo $row_leveles['id']; ?>">更新</a></td>
      </tr>
      <?php } while ($row_leveles = mysql_fetch_assoc($leveles)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_leveles == 0) { // Show if recordset empty ?>
  <p><a href="add.php" class="phpshop123_infobox">现在还没有记录，欢迎添加！</a></p>
  <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($leveles);
?>
