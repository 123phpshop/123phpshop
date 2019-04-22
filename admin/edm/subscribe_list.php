<?php require_once('../../Connections/localhost.php'); ?>
<?php

$doc_url="edm_subscribe.html";
$support_email_question="查看邮件订阅列表";
log_admin($support_email_question);

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_subs = 50;
$pageNum_subs = 0;
if (isset($_GET['pageNum_subs'])) {
  $pageNum_subs = $_GET['pageNum_subs'];
}
$startRow_subs = $pageNum_subs * $maxRows_subs;


$query_subs = "SELECT * FROM email_subscribe where is_delete=0 ORDER BY id DESC";
$query_limit_subs = sprintf("%s LIMIT %d, %d", $query_subs, $startRow_subs, $maxRows_subs);
$subs = mysqli_query($localhost,$query_limit_subs)or die(mysqli_error($localhost).$query_limit_subs);
$row_subs = mysqli_fetch_assoc($subs);

if (isset($_GET['totalRows_subs'])) {
  $totalRows_subs = $_GET['totalRows_subs'];
} else {
  $all_subs = mysqli_query($localhost,$query_subs);
  $totalRows_subs = mysqli_num_rows($all_subs);
}
$totalPages_subs = ceil($totalRows_subs/$maxRows_subs)-1;

$queryString_subs = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_subs") == false && 
        stristr($param, "totalRows_subs") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_subs = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_subs = sprintf("&totalRows_subs=%d%s", $totalRows_subs, $queryString_subs);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">邮件订阅管理</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td>id</td>
    <td>email</td>
    <td>创建时间</td>
    <td>操作</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_subs['id']; ?>&nbsp; </td>
      <td><a href="untitled.php?recordID=<?php echo $row_subs['id']; ?>"> <?php echo strip_tags($row_subs['email']); ?>&nbsp; </a> </td>
      <td><?php echo $row_subs['create_time']; ?></td>
      <td><a href="subscrib_remove.php?id=<?php echo $row_subs['id']; ?>">删除</a> <a href="subscrib_update.php?id=<?php echo $row_subs['id']; ?>">更新</a> </td>
    </tr>
    <?php } while ($row_subs = mysqli_fetch_assoc($subs)); ?>
</table>
<br>
<table border="0" width="50%" align="right">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_subs > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_subs=%d%s", $currentPage, 0, $queryString_subs); ?>">第一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_subs > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_subs=%d%s", $currentPage, max(0, $pageNum_subs - 1), $queryString_subs); ?>">前一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_subs < $totalPages_subs) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_subs=%d%s", $currentPage, min($totalPages_subs, $pageNum_subs + 1), $queryString_subs); ?>">下一页</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_subs < $totalPages_subs) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_subs=%d%s", $currentPage, $totalPages_subs, $queryString_subs); ?>">最后一页</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
记录 <?php echo ($startRow_subs + 1) ?> 到 <?php echo min($startRow_subs + $maxRows_subs, $totalRows_subs) ?> (总共 <?php echo $totalRows_subs ?>条记录）
</body>
</html>
<?php
mysqli_free_result($subs);
?>
