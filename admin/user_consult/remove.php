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
$doc_url="consult.html#list";
$support_email_question="删除用户咨询";
$could_delete=1;

$colname_news = "-1";

if (isset($_GET['id'])) {
  $colname_news = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_news = sprintf("SELECT * FROM product_consult WHERE id = %s", $colname_news);
$news = mysql_query($query_news, $localhost) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);

if($totalRows_news==0){
	$could_delete=0;
} 

if($could_delete==1){

	$update_catalog = sprintf("update `product_consult` set is_delete=1 where id = %s", $colname_news);
	$update_catalog_query = mysql_query($update_catalog, $localhost);
	if(!$update_catalog_query){
		$could_delete=0;
	}else{
		 $update_sql=sprintf("update product set consulted_num=consulted_num-1 where id=%s and consulted_num>0",$row_news['product_id']);
	  	$Result2 = mysql_query($update_sql, $localhost) or die(mysql_error());
		
		if(!$Result2){
			$could_delete=0;
		}else{
			$remove_succeed_url="index.php";
			header("Location: " . $remove_succeed_url );
		}
 	}
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
  <p>由于以下原因，您不能删除这条记录，请及时修正，或是联系123phpshop.com的技术支持人员！<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?></p>
  <p>1. 记录不存在，请检查参数之后再试。</p>
  <p>2. 系统错误，无法删除，请稍后再试。 </p>
  <p>您也可以<a href="index.php">点击这里返回</a>。</p>
  <?php } ?>
</div>

</body>
</html>
<?php
mysql_free_result($news);
?>
