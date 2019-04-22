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
 ?><?php include_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
 <?php 

$query_news = "SELECT * FROM news where is_delete=0 and catalog_id=1 ORDER BY id DESC limit 15";
$news = mysqli_query($localhost,$query_news);
if(!$news){$logger->fatal("数据库操作失败:".$query_news);}
$row_news = mysqli_fetch_assoc($news);
$totalRows_news = mysqli_num_rows($news);

 

$query_catalog = "SELECT * FROM `news_catalog` WHERE is_delete=0 and id = 1";
$catalog = mysqli_query($localhost,$query_catalog);
if(!$catalog){$logger->fatal("数据库操作失败:".$query_catalog);}
$row_catalog = mysqli_fetch_assoc($catalog);
$totalRows_catalog = mysqli_num_rows($catalog);

?>

  <div id="index_news" style="border:solid 1px #e4e4e4;height:463px;">
 	 <div id="index_news_title" style="line-height:43px;width:100%;height:43px;border-bottom:solid 1px #e4e4e4;">
	 	<div style="float:left;font-size:16px;padding-left:15px;"><?php echo $row_catalog['name']; ?></div><div style="float:right;padding-right:15px;"><a href="../news_list.php?id=1">更多</a> </div>
	 </div>
 	 <div id="index_news_content" style="width:100%;padding-left:15px;padding-top:8px;">
		 <?php do { ?>
		 <div style="width:100%;float:left;height:27px;line-height:27px;" align="left"><a style="text-decoration:none;color:#000000;" href="/news.php?id=<?php echo $row_news['id']; ?>"><?php echo $row_news['title']; ?></a></div>
		 <?php } while ($row_news = mysqli_fetch_assoc($news)); ?>
    </div>
 </div>
  <?php
mysqli_free_result($catalog);
?>