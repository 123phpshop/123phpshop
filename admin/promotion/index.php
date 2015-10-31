<?php require_once('../../Connections/localhost.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_promotions = 50;
$pageNum_promotions = 0;
if (isset($_GET['pageNum_promotions'])) {
  $pageNum_promotions = $_GET['pageNum_promotions'];
}
$startRow_promotions = $pageNum_promotions * $maxRows_promotions;

mysql_select_db($database_localhost, $localhost);
$query_promotions = "SELECT * FROM promotion ORDER BY id DESC";
$query_limit_promotions = sprintf("%s LIMIT %d, %d", $query_promotions, $startRow_promotions, $maxRows_promotions);
$promotions = mysql_query($query_limit_promotions, $localhost) or die(mysql_error());
$row_promotions = mysql_fetch_assoc($promotions);

if (isset($_GET['totalRows_promotions'])) {
  $totalRows_promotions = $_GET['totalRows_promotions'];
} else {
  $all_promotions = mysql_query($query_promotions);
  $totalRows_promotions = mysql_num_rows($all_promotions);
}
$totalPages_promotions = ceil($totalRows_promotions/$maxRows_promotions)-1;

$queryString_promotions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_promotions") == false && 
        stristr($param, "totalRows_promotions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_promotions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_promotions = sprintf("&totalRows_promotions=%d%s", $totalRows_promotions, $queryString_promotions);

/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">促销列表</p>
<?php if ($totalRows_promotions == 0) { // Show if recordset empty ?>
  <p class="phpshop123_title"><a href="add.php" class="phpshop123_infobox">没有记录，欢迎添加</a></p>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_promotions > 0) { // Show if recordset not empty ?>
  <p>
  <table width="100%" border="1" align="center" class="phpshop123_list_box">
    <tr>
      <td>id</td>
      <td>name</td>
      <td>start_date</td>
      <td>end_date</td>
      <td>promotion_limit</td>
      <td>amount_lower_limit</td>
      <td>amount_uper_limit</td>
      <td>promotion_type</td>
      <td>create_time</td>
      <td>操作</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_promotions['id']; ?>&nbsp; </td>
        <td><a href="update.php?id=<?php echo $row_promotions['id']; ?>"> <?php echo $row_promotions['name']; ?>&nbsp; </a> </td>
        <td><?php echo $row_promotions['start_date']; ?>&nbsp; </td>
        <td><?php echo $row_promotions['end_date']; ?>&nbsp; </td>
        <td><?php echo $const_promotion_limit[$row_promotions['promotion_limit']]; ?>&nbsp; </td>
        <td><?php echo $row_promotions['amount_lower_limit']; ?>&nbsp; </td>
        <td><?php echo $row_promotions['amount_uper_limit']; ?>&nbsp; </td>
        <td><?php echo $const_promotion_types[$row_promotions['promotion_type']]; ?>&nbsp; </td>
        <td><?php echo $row_promotions['create_time']; ?></td>
        <td><a href="remove.php?id=<?php echo $row_promotions['id']; ?>">删除</a> <a href="update.php?id=<?php echo $row_promotions['id']; ?>">更新</a> </td>
      </tr>
      <?php } while ($row_promotions = mysql_fetch_assoc($promotions)); ?>
      </table>
  <br>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_promotions > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_promotions=%d%s", $currentPage, 0, $queryString_promotions); ?>">第一页</a>
          <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_promotions > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_promotions=%d%s", $currentPage, max(0, $pageNum_promotions - 1), $queryString_promotions); ?>">前一页</a>
          <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_promotions < $totalPages_promotions) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_promotions=%d%s", $currentPage, min($totalPages_promotions, $pageNum_promotions + 1), $queryString_promotions); ?>">下一页</a>
          <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_promotions < $totalPages_promotions) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_promotions=%d%s", $currentPage, $totalPages_promotions, $queryString_promotions); ?>">最后一页</a>
          <?php } // Show if not last page ?>      </td>
    </tr>
      </table>
  记录 <?php echo ($startRow_promotions + 1) ?> 到 <?php echo min($startRow_promotions + $maxRows_promotions, $totalRows_promotions) ?> (总共 <?php echo $totalRows_promotions ?>)
      <?php } // Show if recordset not empty ?></body>
</html>
<?php
mysql_free_result($promotions);
?>
