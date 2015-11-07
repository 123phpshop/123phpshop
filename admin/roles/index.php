<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="ad.html#list";
$support_email_question="查看角色列表";
mysql_select_db($database_localhost, $localhost);
$query_getRoles = "SELECT * FROM `role` ORDER BY id DESC";
$getRoles = mysql_query($query_getRoles, $localhost) or die(mysql_error());
$row_getRoles = mysql_fetch_assoc($getRoles);
$totalRows_getRoles = mysql_num_rows($getRoles);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">角色列表</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<?php if ($totalRows_getRoles > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">角色名称</th>
      <th scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_getRoles['id']; ?></td>
        <td><?php echo $row_getRoles['name']; ?></td>
        <td><a href="remove.php?id=<?php echo $row_getRoles['id']; ?>">删除</a> <a href="edit.php?id=<?php echo $row_getRoles['id']; ?>">更新</a> <a href="assign.php?id=<?php echo $row_getRoles['id']; ?>">权限</a></td>
      </tr>
      <?php } while ($row_getRoles = mysql_fetch_assoc($getRoles)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_getRoles == 0) { // Show if recordset empty ?>
  <p class="phpshop123_infobox">还没有角色，<a href="add.php">点击这里</a>添加 ! </p>
  <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($getRoles);
?>
