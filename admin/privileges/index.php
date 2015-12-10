<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
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
$doc_url="privilege.html#list";
$support_email_question="查看权限列表";
$maxRows_getPrivileges = 50;
$pageNum_getPrivileges = 0;
if (isset($_GET['pageNum_getPrivileges'])) {
  $pageNum_getPrivileges = $_GET['pageNum_getPrivileges'];
}
$startRow_getPrivileges = $pageNum_getPrivileges * $maxRows_getPrivileges;

$colname_getById = "0";
if (isset($_GET['parent_id'])) {
  $colname_getById = (get_magic_quotes_gpc()) ? $_GET['parent_id'] : addslashes($_GET['parent_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_getPrivileges = "SELECT * FROM privilege where is_delete=0 and pid=".$colname_getById." ORDER BY sort ASC";
$query_limit_getPrivileges = sprintf("%s LIMIT %d, %d", $query_getPrivileges, $startRow_getPrivileges, $maxRows_getPrivileges);
$getPrivileges = mysql_query($query_limit_getPrivileges, $localhost) or die(mysql_error());
$row_getPrivileges = mysql_fetch_assoc($getPrivileges);

if (isset($_GET['totalRows_getPrivileges'])) {
  $totalRows_getPrivileges = $_GET['totalRows_getPrivileges'];
} else {
  $all_getPrivileges = mysql_query($query_getPrivileges);
  $totalRows_getPrivileges = mysql_num_rows($all_getPrivileges);
}
$totalPages_getPrivileges = ceil($totalRows_getPrivileges/$maxRows_getPrivileges)-1;

$queryString_getPrivileges = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getPrivileges") == false && 
        stristr($param, "totalRows_getPrivileges") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getPrivileges = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getPrivileges = sprintf("&totalRows_getPrivileges=%d%s", $totalRows_getPrivileges, $queryString_getPrivileges);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">权限列表</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<?php if ($totalRows_getPrivileges == 0) { // Show if recordset empty ?>
  <table  class="info_box" width="100%" border="0">
    <tr>
      <th class="phpshop123_infobox" scope="row"><div align="left">信息：现在还没有权限，您可以<a href="add.php">点击这里</a>添加</div></th>
    </tr>
  </table>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_getPrivileges > 0) { // Show if recordset not empty ?>

    <div align="right"><a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, max(0, $pageNum_getPrivileges - 1), $queryString_getPrivileges); ?>">第一页</a> <a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, max(0, $pageNum_getPrivileges - 1), $queryString_getPrivileges); ?>">前一页</a> <a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, min($totalPages_getPrivileges, $pageNum_getPrivileges + 1), $queryString_getPrivileges); ?>">下一页</a> <a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, $totalPages_getPrivileges, $queryString_getPrivileges); ?>">最后一页</a><br />
  </div>
	  
	  
  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th scope="col">权限名称</th>
      <th scope="col">排序</th>
      <th scope="col">操作</th>
    </tr>
	 <?php do { ?>
    <tr>
        <td><?php echo $row_getPrivileges['name']; ?></td>
        <td><?php echo $row_getPrivileges['sort']; ?></td>
        <td><a href="edit.php?id=<?php echo $row_getPrivileges['id']; ?>">更新</a> <a href="remove.php?id=<?php echo $row_getPrivileges['id']; ?>" onclick="return confirm('您确实要删除这项权限么?')">删除</a> <a href="index.php?parent_id=<?php echo $row_getPrivileges['id']; ?>">子权限列表</a> <a href="add.php?parent_id=<?php echo $row_getPrivileges['id']; ?>">添加子权限</a></td>
        <?php } while ($row_getPrivileges = mysql_fetch_assoc($getPrivileges)); ?></tr>
  </table>
  
  <div align="right"><br />
      <a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, max(0, $pageNum_getPrivileges - 1), $queryString_getPrivileges); ?>">第一页</a> <a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, max(0, $pageNum_getPrivileges - 1), $queryString_getPrivileges); ?>">前一页</a> <a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, min($totalPages_getPrivileges, $pageNum_getPrivileges + 1), $queryString_getPrivileges); ?>">下一页</a> <a href="<?php printf("%s?pageNum_getPrivileges=%d%s", $currentPage, $totalPages_getPrivileges, $queryString_getPrivileges); ?>">最后一页</a>  </div>
	  
  <?php } // Show if recordset not empty ?><p>&nbsp; </p>
  
</body>
</html>