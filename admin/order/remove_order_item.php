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
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/order.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/order.class.php'); ?>
<?php

$doc_url = "order.html#update_product";
$support_email_question = "删除订单商品";
log_admin($support_email_question);
$could_delete = 1;
$colname_news = "-1";

if (isset ( $_GET ['id'] )) {
	$colname_news = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
}
mysql_select_db ( $database_localhost, $localhost );
$query_news = sprintf ( "SELECT * FROM order_item WHERE id = %s", $colname_news );
$news = mysql_query ( $query_news, $localhost );
if(!$news){$logger->fatal("数据库操作失败:".$query_news);}

$row_news = mysql_fetch_assoc ( $news );
$totalRows_news = mysql_num_rows ( $news );
if ($totalRows_news == 0) {
	$could_delete = 0;
}

if ($could_delete == 1) {
	
	// 更新订单中的这个产品为删除
	try {
		// 更新订单费用和促销信息
		$order_array = get_order_full_by_id ( $row_news ['order_id'] );
		$adminOrder = new AdminOrder ( $order_array );
		$adminOrder->remove_product ( $row_news );
		$could_delete = 1;
	} catch ( Exception $e ) {
		$could_delete = 0;
	}
	
	if ($could_delete == 1) {
		$remove_succeed_url = "detail.php?recordID=" . $row_news ['order_id'];
		header ( "Location: " . $remove_succeed_url );
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="phpshop123_infobox">
<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
  <?php if($could_delete==0){ ?>
  <p>由于以下原因，您不能删除这件订单商品，请及时修正，或是联系123phpshop.com的技术支持人员！</p>
		<p>1. 订单商品不存在，请检查参数之后再试。</p>
		<p>2. 系统错误，无法删除，请稍后再试。</p>
		<p>
			您也可以<a href="index.php">点击这里返回</a>。
		</p>
  <?php } ?>
</div>

</body>
</html>