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
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style>
*{
	font-family:"Microsoft Yahei";
}
 
table{
	border-collapse:collapse;
	
}
.STYLE1 {font-size: 18px}
.news_catalog_item{border-bottom:1px solid #ffffff;}
.news_catalog_item a{
	padding-left:45px;color:#666;text-decoration:none;
}
 
.news_link a{
	color:#666;
	text-decoration:none;
	font-size:12px;
	line-height:22px;
}
</style>
</head>

<body style="margin:0px;background-color:#f9f9f9" >
<?php include_once('widget/top_full_nav.php'); ?>
<?php include_once('widget/logo_search_cart.php'); ?>
<div style="height:15px;"></div>
<table border="0" align="center">
  <tr>
    <td width="220" rowspan="3" valign="top" style="font-family:'microsoft yahei';font-size:14px;paddin-right:10px;">
	
	<table width="210" border="0">
      <tr>
        <td height="40" bgcolor="#7dd589" style="color:#ffffff;"><div align="center"><span class="STYLE1">新闻分类</span></div></td>
      </tr>
	   <?php do { ?>
      <tr>
           <td height="41" class="news_catalog_item" bgcolor="#eaeaea" style=""><a href="news_list.php?id=<?php echo $row_news_catalogs['id']; ?>"><?php echo $row_news_catalogs['name']; ?></a></td>
         </tr> <?php } while ($row_news_catalogs = mysqli_fetch_assoc($news_catalogs)); ?>
    </table>
	
	</td>
    <td height="43" bgcolor="#eaeaea" style="color:#666;font-family:'microsoft yahei';font-size:14px;padding-left:13px;"><?php echo $row_news_catalog['name']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td width="770" height="800" valign="top" bgcolor="#FFFFFF"><table width="700" border="1" align="center" cellspacing="0" bordercolor="#eaeaea">
      <tr>
        <td width="700"><table width="700" border="0">
          <tr>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
          
          <table width="700" border="0" align="center">
		  <tr >
		   <td width="700" class="news_link">
		   <?php if($totalRows_row_news>0){ ?>
		   <ul>
            <?php do { ?>
                      <li><a href="news.php?id=<?php echo $row_news['id']; ?>"><?php echo $row_news['title']; ?></a></li>
               <?php } while ($row_news = mysqli_fetch_assoc($news)); ?>
			  </ul>
			<?php  } ?>
			  </td>
		      </tr>
          </table>
          <p>&nbsp;</p></td>
      </tr>
    </table>
    
    <div align="center"></div></td>
  </tr>
</table>
<?php include_once('widget/footer.php'); ?>
</body>
</html>