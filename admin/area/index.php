<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_areas = "0";
if (isset($_GET['pid'])) {
  $colname_areas = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
mysql_select_db($database_localhost, $localhost);
$query_areas = sprintf("SELECT * FROM area WHERE pid = %s", $colname_areas);
$areas = mysql_query($query_areas, $localhost) or die(mysql_error());
$row_areas = mysql_fetch_assoc($areas);
$totalRows_areas = mysql_num_rows($areas);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<p>地址列表</p>
<table width="100%" border="1" align="center">
  <tr>
    <td>id</td>
    <td>name</td>
    <td>level_depth</td>
    <td>操作</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_areas['id']; ?>&nbsp; </td>
      <td><a href="detail.php?recordID=<?php echo $row_areas['id']; ?>"> <?php echo $row_areas['name']; ?>&nbsp; </a> </td>
      <td><?php echo $row_areas['level_depth']; ?>&nbsp; </td>
      <td><a href="remove.php?id=<?php echo $row_areas['id']; ?>">删除</a> <a href="update.php?id=<?php echo $row_areas['id']; ?>">更新</a> <a href="index.php?pid=<?php echo $row_areas['id']; ?>">子区域列表</a> <a href="add.php?pid=<?php echo $row_areas['id']; ?>">添加子区域</a></td>
    </tr>
    <?php } while ($row_areas = mysql_fetch_assoc($areas)); ?>
</table>
<br>
<?php echo $totalRows_areas ?> 记录 总数
</p>
</body>
</html>
<?php
mysql_free_result($areas);
?>
