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
$doc_url="news.html#recover";
$support_email_question="恢复杂志";
log_admin($support_email_question);
$could_delete=1;
$colname_product = "-1";
if (isset($_GET['id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT * FROM news WHERE id = %s", $colname_product);
$product = mysql_query($query_product, $localhost) ;
if(!$product){$logger->fatal("数据库操作失败:".$query_product);}
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);

if($row_product==0){
	$could_delete=0;
} 

if($could_delete==1){

	$update_catalog = sprintf("update `news` set is_delete=0 where id = %s", $colname_product);
	$update_catalog_query = mysql_query($update_catalog, $localhost);
	if(!$update_catalog_query){
		$logger->fatal("数据库操作失败:".$update_catalog);
		$could_delete=0;
	}else{
		$remove_succeed_url="index.php?catalog_id=".$row_product['catalog_id'];
		header("Location: " . $remove_succeed_url );
 	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php if($could_delete==0){ ?>
<p>由于一下原因，您不能恢复这条记录：<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div></p>
<p>1.	记录不存在，请检查参数之后再试。</p>
<p>2. 	系统错误，无法删除，请示稍后再试。 </p>
<p>您也可以<a href="../拷贝于 news/index.php">点击这里返回</a>。
  <?php } ?>
</p>
</body>
</html>