<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_comments = 50;
$pageNum_comments = 0;
if (isset($_GET['pageNum_comments'])) {
  $pageNum_comments = $_GET['pageNum_comments'];
}
$startRow_comments = $pageNum_comments * $maxRows_comments;

$colname_comments = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_comments = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}

$where_string=_get_comment_where_query_string();
$query_comments = "SELECT user_favorite.*, product.name  FROM user_favorite inner join product on product.id=user_favorite.product_id WHERE user_favorite.user_id = $colname_comments  and  user_favorite.is_delete=0 ORDER BY user_favorite.id DESC";
$query_limit_comments = sprintf("%s LIMIT %d, %d", $query_comments, $startRow_comments, $maxRows_comments);
$comments = mysqli_query($localhost);if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL,$query_limit_comments);}
$row_comments = mysqli_fetch_assoc($comments);

if (isset($_GET['totalRows_comments'])) {
  $totalRows_comments = $_GET['totalRows_comments'];
} else {
  $all_comments = mysqli_query($localhost,$query_comments);
  $totalRows_comments = mysql_num_rows($all_comments);
}
$totalPages_comments = ceil($totalRows_comments/$maxRows_comments)-1;

$queryString_comments = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_comments") == false && 
        stristr($param, "totalRows_comments") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_comments = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_comments = sprintf("&totalRows_comments=%d%s", $totalRows_comments, $queryString_comments);

function _get_comment_where_query_string(){
	
	$result="";
	
	if(isset($_GET['message']) && trim($_GET['message'])!=''){
		$result.=" and message like '%".$_GET['message']."%'";
	}
	
 	return $result;
	
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收藏列表</title>
<link href="../../css/common_user.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <p class="phpshop123_user_title">收藏列表</p>

   <?php if ($totalRows_comments > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" align="center" class="phpshop123_user_list_box">
    <tr>
      <td width="62%">商品</td>
      <td width="17%"><div align="right">收藏时间</div></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="/product.php?id=<?php echo $row_comments['product_id']; ?>#comment" target="_blank"><?php echo $row_comments['name']; ?></a></td>
        <td><div align="right"><?php echo $row_comments['create_time']; ?>&nbsp; </div></td>
      </tr>
      <?php } while ($row_comments = mysqli_fetch_assoc($comments)); ?>
  </table>
  <br>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, 0, $queryString_comments); ?>" class="phpshop123_user_paging">第一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, max(0, $pageNum_comments - 1), $queryString_comments); ?>" class="phpshop123_user_paging">前一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, min($totalPages_comments, $pageNum_comments + 1), $queryString_comments); ?>" class="phpshop123_user_paging">下一页</a>
            <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, $totalPages_comments, $queryString_comments); ?>" class="phpshop123_user_paging">最后一页</a>
            <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  记录 <?php echo ($startRow_comments + 1) ?> 到 <?php echo min($startRow_comments + $maxRows_comments, $totalRows_comments) ?> (总共 <?php echo $totalRows_comments ?>)  
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_comments == 0) { // Show if recordset empty ?>
    <p class="phpshop123_user_title">暂无评论！</p>
    <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($comments);
?>