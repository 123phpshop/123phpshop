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
$maxRows_DetailRS1 = 20;
$pageNum_DetailRS1 = 0;
$doc_url="brand.html#detail";
$support_email_question="查看品牌详情";
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['recordID'];
$query_DetailRS1 = "SELECT * FROM brands WHERE id = $recordID ORDER BY id DESC";
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
		
<p class="phpshop123_title">品牌详细</p><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<table width="100%" border="0" align="center" class="phpshop123_form_box">
  
  <tr>
    <td>ID</td>
    <td><?php echo $row_DetailRS1['id']; ?> </td>
  </tr>
  <tr>
    <td>名称</td>
    <td><?php echo $row_DetailRS1['name']; ?> </td>
  </tr>
  <tr>
    <td>图片</td>
    <td><img src="<?php echo $row_DetailRS1['image_path'] ;?>"/> </td>
  </tr>
  <tr>
    <td>创建时间</td>
    <td><?php echo $row_DetailRS1['create_time']; ?> </td>
  </tr>
  <tr>
    <td>产品数量</td>
    <td><?php echo $row_DetailRS1['product_num']; ?> </td>
  </tr>
  <tr>
    <td>是否被删除</td>
    <td><?php echo $row_DetailRS1['is_delete']=="0"?"否":"√"; ?> </td>
  </tr>
  <tr>
    <td>网址</td>
    <td><?php echo $row_DetailRS1['url']; ?></td>
  </tr>
  <tr>
    <td>排序;</td>
    <td><?php echo $row_DetailRS1['sort']; ?></td>
  </tr>
  <tr>
    <td>介绍</td>
    <td><?php echo $row_DetailRS1['desc']; ?></td>
  </tr>
</table>

</body>
</html><?php
mysql_free_result($DetailRS1);
?>