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
 ?><?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
$colname_news_cata = "-1";
if (isset($_GET['catalog_id'])) {
  $colname_news_cata = (get_magic_quotes_gpc()) ? $_GET['catalog_id'] : addslashes($_GET['catalog_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_news_cata = sprintf("SELECT * FROM `catalog` WHERE is_delete=0 and pid = %s", $colname_news_cata);
$news_cata = mysql_query($query_news_cata, $localhost) or die(mysql_error());
$row_news_cata = mysql_fetch_assoc($news_cata);
$totalRows_news_cata = mysql_num_rows($news_cata);
?>
<?php if($totalRows_news_cata>0){?>
<style>
.sub_cata{
	height:31px;
	color:#333;
	line-height:28px;
	padding-left:13px;
	font-size:14px;	
	font-weight:400;
 	background-color:#f7f7f7;
	border-top:1px solid #ddd;
}

#sub_cata_list{
	border:1px solid #ddd;
}
#sub_cata_list a{
	text-decoration:none;
	
}

.sub_cata:hover{
	background-color:#fff;
}
</style>
<div id="sub_cata_list"  width="100%">
<?php do { ?>
	<a href="/product_list.php?catalog_id=<?php echo $row_news_cata['id']; ?>">
		<div class="sub_cata"><?php echo $row_news_cata['name']; ?></div>
	</a> 
<?php } while ($row_news_cata = mysql_fetch_assoc($news_cata)); ?> 
</div>
<p>
<?php } ?>
<?php

mysql_free_result($news_cata);
?>