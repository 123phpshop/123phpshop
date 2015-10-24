<?php require_once('../../Connections/localhost.php'); 
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_brands = 20;
$pageNum_brands = 0;
if (isset($_GET['pageNum_brands'])) {
  $pageNum_brands = $_GET['pageNum_brands'];
}
$startRow_brands = $pageNum_brands * $maxRows_brands;

mysql_select_db($database_localhost, $localhost);
$query_brands = "SELECT * FROM brands where is_delete=0 ORDER BY id DESC";
$query_limit_brands = sprintf("%s LIMIT %d, %d", $query_brands, $startRow_brands, $maxRows_brands);
$brands = mysql_query($query_limit_brands, $localhost) or die(mysql_error());
$row_brands = mysql_fetch_assoc($brands);

if (isset($_GET['totalRows_brands'])) {
  $totalRows_brands = $_GET['totalRows_brands'];
} else {
  $all_brands = mysql_query($query_brands);
  $totalRows_brands = mysql_num_rows($all_brands);
}
$totalPages_brands = ceil($totalRows_brands/$maxRows_brands)-1;

$queryString_brands = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_brands") == false && 
        stristr($param, "totalRows_brands") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_brands = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_brands = sprintf("&totalRows_brands=%d%s", $totalRows_brands, $queryString_brands);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_brands > 0) { // Show if recordset not empty ?>
  <p class="phpshop123_title">品牌列表</p>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_brands > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, 0, $queryString_brands); ?>">第一页</a>
          <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_brands > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, max(0, $pageNum_brands - 1), $queryString_brands); ?>">前一页</a>
          <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_brands < $totalPages_brands) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, min($totalPages_brands, $pageNum_brands + 1), $queryString_brands); ?>">下一页</a>
          <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_brands < $totalPages_brands) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, $totalPages_brands, $queryString_brands); ?>">最后一页</a>
          <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>
  <table width="100%" border="0" align="center" class="phpshop123_list_box">
    <tr>
      <td>名称</td>
      <td>排序</td>
      <td>创建时间</td>
      <td>网址</td>
      <td>操作</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><div align="left"><a href="detail.php?recordID=<?php echo $row_brands['id']; ?>"> <?php echo $row_brands['name']; ?>&nbsp; </a> </div></td>
        <td><?php echo $row_brands['sort']; ?></td>
        <td><?php echo $row_brands['create_time']; ?>&nbsp; </td>
        <td><?php echo $row_brands['url']; ?></td>
        <td><a href="remove.php?id=<?php echo $row_brands['id']; ?>">删除</a> <a href="update.php?id=<?php echo $row_brands['id']; ?>">更新</a></td>
      </tr>
      <?php } while ($row_brands = mysql_fetch_assoc($brands)); ?>
  </table>
  <br>
  <table border="0" width="40%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_brands > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, 0, $queryString_brands); ?>">第一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_brands > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, max(0, $pageNum_brands - 1), $queryString_brands); ?>">前一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_brands < $totalPages_brands) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, min($totalPages_brands, $pageNum_brands + 1), $queryString_brands); ?>">下一页</a>
            <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_brands < $totalPages_brands) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_brands=%d%s", $currentPage, $totalPages_brands, $queryString_brands); ?>">最后一页</a>
            <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  <p>记录 <?php echo ($startRow_brands + 1) ?> 到 <?php echo min($startRow_brands + $maxRows_brands, $totalRows_brands) ?> (总共 <?php echo $totalRows_brands ?> ) </p>
  </p>
 <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_brands == 0) { // Show if recordset not empty ?>
        <p><a href="add.php">没有记录，欢迎添加！</a></p>
    <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($brands);
?>
