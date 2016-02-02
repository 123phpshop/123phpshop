<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_shopinfo['name']; ?></title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
</head>
<body style="margin:0px;">
<?php include_once($template_path.'widget/top_full_nav.php'); ?>
<?php //include_once('widget/ad/1024_one_image.php'); ?>
<?php include_once($template_path.'widget/logo_search_cart.php'); ?>
<table width="1210" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" bgcolor="#B1191A" style="padding-left:10px;color:white;font:400 15px/44px 'microsoft yahei';"><a href="catalogs.php" class="to_all_catas">全部分类</a></td>
    <td>&nbsp;</td>
    <td width="187" height="44">&nbsp;</td>
  </tr>
</table>
<div style="margin:0px;padding:0px;width:100%;border:none;border-top:2px solid #B1191A;"></div>
<table width="1210"   border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="210" height="465"  valign="top" id="index_cat">
     	<?php include_once($template_path.'widget/main_nav.php'); ?>
 	</td>
    <td height="465" align="center" valign="top" style="padding:2px 2px 0px 2px;">
		<?php include_once($template_path.'widget/index_image_slide.php'); ?>
	</td>
    <td width="250"  valign="top" style="padding:2px 0px 0px 0px;">
	<?php include_once($template_path.'widget/index_news_tab.php'); ?>
  </tr>
</table>
<?php
  include_once($template_path.'widget/index_catalog_floor.php'); ?> 
<?php include_once($template_path.'widget/footer.php'); ?>
 </body>
</html>