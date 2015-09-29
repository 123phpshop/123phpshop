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
<?php require_once('../Connections/localhost.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style>
body{
	font-family:微软雅黑;
	padding:0px;
	margin:0px;
	background-color:#373737;
	font-size:12px;
	 
}

 

#menu_div div{
	padding:10px;
	border-bottom:1px solid #C4C4C4;
	border-bottom-width:0px;
	border-left-width:0px;
	border-right-width:0px;
	background-color:#373737;
 	text-align:center;
	color:white;
	text-align:left;
	padding-left:40px;
}

#menu_div div:hover{
	cursor:pointer;
} 

div a {
	text-decoration:none;
	color:white;
}
</style>
</head>

<body>
<div id="menu_div" style="width:100%;">
<div class="menu_title" style="text-align:center;padding-left:0px;line-height:26px;">欢迎<?php echo $_SESSION['admin_username']; ?></div>
<div class="menu_item" ><a href="catalog/index.php" target="_main">产品分类</a></div>
<div class="menu_item" ><a href="product/index.php" target="_main">产品管理</a></div>
<div class="menu_item" ><a href="product/recycled.php" target="_main">产品回收站</a></div>
<div class="menu_item" ><a href="order/index.php" target="_main">订单管理</a></div>
<div class="menu_item" ><a href="order/recycle.php" target="_main">订单回收站</a></div>
<div class="menu_item" ><a href="brands/index.php" target="_main">品牌管理</a></div>
<div class="menu_item" ><a href="brands/add.php" target="_main">添加品牌</a></div>
<div class="menu_item" ><a href="shipping_method/index.php" target="_main">配送方式</a></div>
<div class="menu_item" ><a href="shipping_method/add.php" target="_main">添加配送</a></div>
<div class="menu_item" ><a href="ad/index.php" target="_main">广告管理</a></div>
<div class="menu_item" ><a href="ad/add.php" target="_main">添加广告</a></div>
 
<div class="menu_item" ><a href="users/index.php" target="_main">用户列表</a></div>
<div class="menu_item" ><a href="users/add.php" target="_main">添加用户</a></div> 
<div class="menu_item" ><a href="pay_method/index.php" target="_main">支付方式</a></div>
<div class="menu_item" ><a href="admin/update_password.php" target="_main">密码设置</a></div>
<div class="menu_item" ><a href="user_comments/index.php" target="_main">评论管理</a></div>
<div class="menu_item" ><a href="user_consult/index.php" target="_main">咨询管理</a></div>
<div class="menu_item" ><a href="news_catalog/index.php " target="_main">文章分类</a></div>
<div class="menu_item" ><a href="news/index.php" target="_main">文章管理</a></div>
<div class="menu_item" ><a href="news/recycled.php" target="_main">文章回收站</a></div>
<div class="menu_item" ><a href="admin/index.php" target="_main">管理员列表</a></div>
<div class="menu_item" ><a href="admin/add.php" target="_main">添加管理员</a></div>
<div class="menu_item" ><a href="info.php" target="_main">店铺信息</a></div>
<div class="menu_item" ><a href="area/index.php" target="_main">区域管理</a></div>
<div class="menu_item" ><a href="http://www.123phpshop.com/client_portal/" target="_main">家族软件</a></div>
</div>
</body>
</html>
