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
$could_delete=1;
$colname_news = "-1";

// 检查下面是否有图片，如果有图片的话，那么请先删除图片然后再试
$colname_ad_images = "-1";
if (isset($_GET['id'])) {
	$colname_ad_images = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
log_admin("删除广告");

try{

	// 参数检查

	// 检查id是否存在，如果不存在，那么告知
	mysql_select_db($database_localhost, $localhost);
	$query_ad_images = sprintf("SELECT * FROM ad_images WHERE id = %s", $colname_ad_images);
	$ad_images = mysqli_query($localhost,$query_ad_images);
	if(!$ad_images){
		$logger->fatal("删除广告操作失败:".$query_ad_images);
		throw new Exception();
	}

	$row_ad_images=mysqli_fetch_assoc($ad_images);
	$totalRows_ad_images = mysql_num_rows($ad_images);
	if($totalRows_ad_images==0){
		throw new Exception();// 这需要报错
	} 

	@unlink($_SERVER['DOCUMENT_ROOT'].$row_ad_images['image_path']);
	$update_catalog = sprintf("delete from `ad_images` where id = %s", $colname_ad_images);
	$update_catalog_query = mysqli_query($localhost,$update_catalog);
	if(!$update_catalog_query){
		$logger->fatal("数据库操作失败:".$update_catalog);
		throw new Exception();
	}

	$remove_succeed_url="/admin/ad/detail.php?recordID=".$row_ad_images['ad_id'];
	header("Location: " . $remove_succeed_url );
	exit();
}catch(Exception $ex){
	$could_delete=0;
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="phpshop123_infobox">
  <?php if($could_delete==0){ ?>
  <p>由于以下原因，您不能删除这张图片：</p>
  <p>1. 图片不存在，请检查参数之后再试。</p>
  <p>2. 系统错误，无法删除，请示稍后再试。 </p>
  <p>您也可以<a href="index.php">点击这里返回</a>。</p>
  <?php } ?>
</div>

</body>
</html>
