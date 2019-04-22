<?php require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);
$query_leveles = "SELECT * FROM user_levels ORDER BY id ASC";
$leveles = mysqli_query($localhost,$query_leveles);
if(!$leveles){$logger->fatal("数据库操作失败:".$query_leveles);}
$row_leveles = mysqli_fetch_assoc($leveles);
$totalRows_leveles = mysql_num_rows($leveles);

$doc_url="user_level.html#index";
$support_email_question="查看用户等级列表";log_admin($support_email_question);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="file:///E|/vhost/123phpshop_v15/css/common_admin.css" rel="stylesheet" type="text/css" />
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">用户等级 </span>
<a href="add.php">
  <input style="float:right;" type="submit" name="Submit2" value="添加用户等级" />
</a>
</p>
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
        <td scope="col"><a href="remove.php?id=<?php echo $row_leveles['id']; ?>" onclick="return confirm('您确认要删除这条记录吗？')">删除</a> <a href="edit.php?id=<?php echo $row_leveles['id']; ?>">更新</a></td>
      </tr>
      <?php } while ($row_leveles = mysqli_fetch_assoc($leveles)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_leveles == 0) { // Show if recordset empty ?>
  <p><a href="add.php" class="phpshop123_infobox">现在还没有记录，欢迎添加！</a></p>
  <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($leveles);
?>
