<?php require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);
$query_themes = "SELECT * FROM theme";
$themes = mysql_query($query_themes, $localhost) or die(mysql_error());
$row_themes = mysql_fetch_assoc($themes);
$totalRows_themes = mysql_num_rows($themes);

$doc_url="theme.html#add";
$support_email_question="添加模板";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">模板列表</p>
<?php if ($totalRows_themes > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th width="5%" scope="col">ID</th>
      <th width="14%" scope="col">名称</th>
      <th width="13%" scope="col">文件夹</th>
      <th width="11%" scope="col">作者</th>
      <th width="11%" scope="col">版本</th>
      <th width="11%" scope="col">联系方式</th>
      <th width="22%" scope="col">激活</th>
      <th width="13%" scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_themes['id']; ?></td>
        <td><div align="center"><a href="update.php?id=<?php echo $row_themes['id']; ?>"><?php echo $row_themes['name']; ?></a></div></td>
        <td><div align="center"><?php echo $row_themes['folder_name']; ?></div></td>
        <td><div align="center"><?php echo $row_themes['author']; ?></div></td>
        <td><div align="center"><?php echo $row_themes['version']; ?></div></td>
        <td><div align="center"><?php echo $row_themes['contact']; ?></div></td>
        <td><div align="center"><?php echo $row_themes['is_delete']==0?"√":""; ?></div></td>
        <td>安装 <?php if($row_themes['is_delete']==1){ ?><a href="activate.php?id=<?php echo $row_themes['id']; ?>">激活</a><?php } ?> <?php if($row_themes['is_delete']==0){ ?><a href="deactivate.php?id=<?php echo $row_themes['id']; ?>">停用</a> <?php } ?><a href="update.php?id=<?php echo $row_themes['id']; ?>">更新</a> </td>
      </tr>
      <?php } while ($row_themes = mysql_fetch_assoc($themes)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_themes == 0) { // Show if recordset empty ?>
    <p><a href="add.php">现在还没有模板，欢迎点击这里添加。</a></p>
    <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($themes);
?>
