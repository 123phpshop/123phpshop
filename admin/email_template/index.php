<?php require_once('../../Connections/localhost.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_email_templates = 50;
$pageNum_email_templates = 0;
if (isset($_GET['pageNum_email_templates'])) {
  $pageNum_email_templates = $_GET['pageNum_email_templates'];
}
$startRow_email_templates = $pageNum_email_templates * $maxRows_email_templates;

mysql_select_db($database_localhost, $localhost);
$query_email_templates = "SELECT * FROM email_templates WHERE is_delete = 0 ORDER BY id DESC";
$query_limit_email_templates = sprintf("%s LIMIT %d, %d", $query_email_templates, $startRow_email_templates, $maxRows_email_templates);
$email_templates = mysql_query($query_limit_email_templates, $localhost) or die(mysql_error());
$row_email_templates = mysql_fetch_assoc($email_templates);

if (isset($_GET['totalRows_email_templates'])) {
  $totalRows_email_templates = $_GET['totalRows_email_templates'];
} else {
  $all_email_templates = mysql_query($query_email_templates);
  $totalRows_email_templates = mysql_num_rows($all_email_templates);
}
$totalPages_email_templates = ceil($totalRows_email_templates/$maxRows_email_templates)-1;

$queryString_email_templates = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_email_templates") == false && 
        stristr($param, "totalRows_email_templates") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_email_templates = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_email_templates = sprintf("&totalRows_email_templates=%d%s", $totalRows_email_templates, $queryString_email_templates);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <p class="phpshop123_title">邮件模板</p>

<?php if ($totalRows_email_templates > 0) { // Show if recordset not empty ?>
  <p>
  <table width="100%" border="0" align="center" class="phpshop123_list_box">
    <tr>
      <td>模板名称</td>
      <td>代码</td>
      <td>标题</td>
      <td>创建时间</td>
      <td>操作</td>
    </tr>
      <?php do { ?>
          <tr>
            <td><a href="update.php?id=<?php echo $row_email_templates['id']; ?>"> <?php echo $row_email_templates['name']; ?>&nbsp; </a> </td>
            <td><?php echo $global_phpshop123_email_send_time[$row_email_templates['code']]; ?>&nbsp; </td>
            <td><?php echo $row_email_templates['title']; ?>&nbsp; </td>
            <td><?php echo $row_email_templates['create_time']; ?>&nbsp; </td>
            <td><a href="remove.php?id=<?php echo $row_email_templates['id']; ?>" onclick="return confirm('您确实要删除这条记录吗？');">删除</a> <a href="update.php?id=<?php echo $row_email_templates['id']; ?>">更新</a></td>
          </tr>
          <?php } while ($row_email_templates = mysql_fetch_assoc($email_templates)); ?>
  </table>
  <br>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_email_templates > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_email_templates=%d%s", $currentPage, 0, $queryString_email_templates); ?>">第一页</a>
                <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_email_templates > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_email_templates=%d%s", $currentPage, max(0, $pageNum_email_templates - 1), $queryString_email_templates); ?>">前一页</a>
                <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_email_templates < $totalPages_email_templates) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_email_templates=%d%s", $currentPage, min($totalPages_email_templates, $pageNum_email_templates + 1), $queryString_email_templates); ?>">下一页</a>
                <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_email_templates < $totalPages_email_templates) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_email_templates=%d%s", $currentPage, $totalPages_email_templates, $queryString_email_templates); ?>">最后一页</a>
                <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  记录 <?php echo ($startRow_email_templates + 1) ?> 到 <?php echo min($startRow_email_templates + $maxRows_email_templates, $totalRows_email_templates) ?> (总共 <?php echo $totalRows_email_templates ?>)
  <?php } // Show if recordset not empty ?>	

   <?php if ($totalRows_email_templates == 0) { // Show if recordset empty ?>
  <p><a href="add.php" class="phpshop123_infobox">现在还没有模板，欢迎添加！</a></p>
    <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($email_templates);
?>
