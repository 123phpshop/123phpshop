<?php
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
<?php require_once('../../Connections/localhost.php'); ?>
<?php
$maxRows_news = 50;
$pageNum_news = 0;
if (isset($_GET['pageNum_news'])) {
  $pageNum_news = $_GET['pageNum_news'];
}
$startRow_news = $pageNum_news * $maxRows_news;

mysql_select_db($database_localhost, $localhost);
$query_news = "SELECT * FROM news WHERE is_delete = 1";
$query_limit_news = sprintf("%s LIMIT %d, %d", $query_news, $startRow_news, $maxRows_news);
$news = mysql_query($query_limit_news, $localhost) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);

if (isset($_GET['totalRows_news'])) {
  $totalRows_news = $_GET['totalRows_news'];
} else {
  $all_news = mysql_query($query_news);
  $totalRows_news = mysql_num_rows($all_news);
}
$totalPages_news = ceil($totalRows_news/$maxRows_news)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">文章回收站</p>

  <?php if ($totalRows_news == 0) { // Show if recordset empty ?>
    <div class="phpshop123_infobox"><p>回收站中空空如也！</p></div>
    <?php } // Show if recordset empty ?>

<?php if ($totalRows_news > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th width="73%" scope="col">标题</th>
      <th width="8%" scope="col">创建时间</th>
      <th width="19%" scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td scope="col"><?php echo $row_news['title']; ?></td>
        <td scope="col"><?php echo $row_news['create_time']; ?></td>
        <td scope="col"><div align="right"><a onclick="return confirm('您确实要恢复这条记录吗？')" href="unrecycle.php?id=<?php echo $row_news['id']; ?>">恢复</a></div></td>
      </tr>
      <?php } while ($row_news = mysql_fetch_assoc($news)); ?>
  </table>
  <?php } // Show if recordset not empty ?><p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($news);
?>
