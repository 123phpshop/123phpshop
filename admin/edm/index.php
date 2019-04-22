<?php require_once('../../Connections/localhost.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_edm_list = 50;
$pageNum_edm_list = 0;
if (isset($_GET['pageNum_edm_list'])) {
  $pageNum_edm_list = $_GET['pageNum_edm_list'];
}
$startRow_edm_list = $pageNum_edm_list * $maxRows_edm_list;


$query_edm_list = "SELECT edm_list.*,edm_news.title as magzine_title FROM edm_list inner join edm_news on edm_news.id=edm_list.magzine_id ORDER BY edm_list.id DESC";
$query_limit_edm_list = sprintf("%s LIMIT %d, %d", $query_edm_list, $startRow_edm_list, $maxRows_edm_list);
$edm_list = mysqli_query($localhost)or die(mysqli_error($localhost),$query_limit_edm_list);
$row_edm_list = mysqli_fetch_assoc($edm_list);

if (isset($_GET['totalRows_edm_list'])) {
  $totalRows_edm_list = $_GET['totalRows_edm_list'];
} else {
  $all_edm_list = mysqli_query($localhost,$query_edm_list);
  $totalRows_edm_list = mysqli_num_rows($all_edm_list);
}
$totalPages_edm_list = ceil($totalRows_edm_list/$maxRows_edm_list)-1;

$queryString_edm_list = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_edm_list") == false && 
        stristr($param, "totalRows_edm_list") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_edm_list = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_edm_list = sprintf("&totalRows_edm_list=%d%s", $totalRows_edm_list, $queryString_edm_list);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">EDM群发列队</span>

<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<?php if ($totalRows_edm_list == 0) { // Show if recordset empty ?>
  <p class="phpshop123_infobox">当前还没有列队</p>
  <?php }else{ // Show if recordset empty ?><table width="50%" border="0" align="right">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_edm_list > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, 0, $queryString_edm_list); ?>">第一页</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_edm_list > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, max(0, $pageNum_edm_list - 1), $queryString_edm_list); ?>">前一页</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_edm_list < $totalPages_edm_list) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, min($totalPages_edm_list, $pageNum_edm_list + 1), $queryString_edm_list); ?>">下一页</a>
        <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_edm_list < $totalPages_edm_list) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, $totalPages_edm_list, $queryString_edm_list); ?>">最后一页</a>
        <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td>ID</td>
    <td>邮件地址</td>
    <td>杂志</td>
    <td>状态</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_edm_list['id']; ?>&nbsp; </td>
      <td><a href="untitled.php?recordID=<?php echo $row_edm_list['id']; ?>"> <?php echo $row_edm_list['email']; ?>&nbsp; </a> </td>
      <td><?php echo $row_edm_list['magzine_title']; ?>&nbsp; </td>
      <td><?php echo $global_edm_status[$row_edm_list['status']]; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_edm_list = mysqli_fetch_assoc($edm_list)); ?>
</table>
<br>
<table border="0" width="50%" align="right">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_edm_list > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, 0, $queryString_edm_list); ?>">第一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_edm_list > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, max(0, $pageNum_edm_list - 1), $queryString_edm_list); ?>">前一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_edm_list < $totalPages_edm_list) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, min($totalPages_edm_list, $pageNum_edm_list + 1), $queryString_edm_list); ?>">下一页</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_edm_list < $totalPages_edm_list) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_edm_list=%d%s", $currentPage, $totalPages_edm_list, $queryString_edm_list); ?>">最后一页</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
记录 <?php echo ($startRow_edm_list + 1) ?> 到 <?php echo min($startRow_edm_list + $maxRows_edm_list, $totalRows_edm_list) ?> (总共 <?php echo $totalRows_edm_list ?>)
<?php
}
?>
</body>
</html>

