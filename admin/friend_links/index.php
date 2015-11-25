<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="links.html#list";
$support_email_question="查看友情链接列表";
mysql_select_db($database_localhost, $localhost);
$query_links = "SELECT * FROM friend_links WHERE is_delete = 0 ORDER BY sort DESC";
$links = mysql_query($query_links, $localhost) or die(mysql_error());
$row_links = mysql_fetch_assoc($links);
$totalRows_links = mysql_num_rows($links);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <span class="phpshop123_title">友情链接</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
  <a href="add.php">
  <?php if ($totalRows_links == 0) { // Show if recordset empty ?>
   </a>
<p class="phpshop123_infobox"><a href="add.php">没有记录，欢迎添加
    <?php } // Show if recordset empty ?>
  </a>
  <?php if ($totalRows_links > 0) { // Show if recordset not empty ?>
    <table width="100%" border="1" align="center" class="phpshop123_list_box">
      <tr>
        <td>连接文字</td>
        <td>连接网址</td>
        <td>创建时间</td>
        <td>排序</td>
        <td>操作</td>
      </tr>
      <?php do { ?>
      <tr>
        <td><a href="update.php?id=<?php echo $row_links['id']; ?>"> <?php echo $row_links['link_text']; ?>&nbsp; </a> </td>
        <td><?php echo $row_links['link_url']; ?>&nbsp; </td>
        <td><?php echo $row_links['create_time']; ?>&nbsp; </td>
        <td><?php echo $row_links['sort']; ?></td>
        <td><a onclick="return confirm('请确定要删除这个友情链接吗？');" href="remove.php?id=<?php echo $row_links['id']; ?>">删除</a> <a href="update.php?id=<?php echo $row_links['id']; ?>">更新</a> </td>
      </tr>
      <?php } while ($row_links = mysql_fetch_assoc($links)); ?>
  </table>
    <br />
     记录总数:<?php echo $totalRows_links ?>
    <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($links);
?>
