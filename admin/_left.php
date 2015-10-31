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

a {
	text-decoration:none;
	color:white;
}

.menu_item_row{
	width:100%;
 	float:left;
	height:39px;
	line-height:39px;
 	border-bottom:1px solid #515151;
   	background-color:#373737;
 	color:white;
}

a[parent] .menu_item_row{
	padding-left:20px;
}

.menu_item_row:hover{
	cursor:pointer;
 	background-color:#515151;
}

.menu_item_active{
	background-color:#999999 !important;
	
}
.menu_item{
	padding-left:10px;
	float:left;
}
.right_indicator{
	font-family:"FontAwesome";
	font-weight:bold;
	float:right;
	color:#FFFFFF;
	padding-right:18px;
}
</style>
<script language="JavaScript" type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
</head>

<body>
<div id="menu_div" style="width:100%;">
<div class="menu_title" style="text-align:center;padding-left:0px;line-height:66px;color:#FFFFFF;height:66px;">欢迎<?php echo $_SESSION['admin_username']; ?></div>
<a href="_main.php" target="main"  id="dashboard"><div class="menu_item_row" style="border-top:1px solid #515151;"><div class="menu_item">控制面板</div><div class="right_indicator"></div></div></a>
<a href="product/index.php" target="main"  id="goods"><div class="menu_item_row"><div class="menu_item" >商品管理</div><div class="right_indicator" style="">></div></div></a>
<a href="catalog/index.php" target="main" id="goods_catalogs" parent="goods"><div class="menu_item_row" ><div class="menu_item">》 商品分类</div><div class="right_indicator" style=""></div></div></a>
<a href="product_type/index.php" target="main"  id="goods_types" parent="goods"><div class="menu_item_row"><div class="menu_item">》 商品类型</div><div class="right_indicator" style=""></div></div></a>

<a href="brands/index.php" target="main" id="brands" parent="goods"><div class="menu_item_row" ><div class="menu_item" >》 品牌管理</div><div class="right_indicator" style="">></div></div></a>
<a href="brands/add.php" target="main" id="brand_add" parent="goods"><div class="menu_item_row" ><div class="menu_item" >》 添加品牌</div><div class="right_indicator" style="">></div></div></a>
<a href="product/recycled.php" target="main" id="goods_recycled" parent="goods"><div class="menu_item_row" ><div class="menu_item" >》 商品回收站</div><div class="right_indicator" style="">></div></div></a>

<a href="user_comments/index.php" target="main" id="comments" parent="goods"><div class="menu_item_row" ><div class="menu_item" >》 商品评论</div><div class="right_indicator" style=""></div></div></a>
<a href="user_consult/index.php" target="main" id="consults" parent="goods"><div class="menu_item_row" ><div class="menu_item" >》 商品咨询</div><div class="right_indicator" style=""></div></div></a>

 <a href="order/index.php" target="main" id="orders"><div class="menu_item_row" ><div class="menu_item" >订单管理</div><div class="right_indicator" style="">></div></div></a>
<a href="order/index.php?status=100" target="main" id="order_delivery"  parent="orders"><div class="menu_item_row" ><div class="menu_item" >》 发货订单</div><div class="right_indicator" style="">></div></div></a>
<a href="order/index.php?status=-150" target="main" id="order_return"  parent="orders"><div class="menu_item_row" ><div class="menu_item" >》 退货订单</div><div class="right_indicator" style="">></div></div></a>
<a href="order/recycle.php" target="main" id="order_recycled"  parent="orders"><div class="menu_item_row" ><div class="menu_item" >》 订单回收站</div><div class="right_indicator" style="">></div></div></a>

 <a href="ad/index.php" target="main"  id="ads"><div class="menu_item_row"><div class="menu_item" >广告管理</div><div class="right_indicator" style="">></div></div></a>
<a href="ad/add.php" target="main" id="ad_add"   parent="ads"><div class="menu_item_row" ><div class="menu_item">》 添加广告</div><div class="right_indicator" style="">></div></div></a>
<a href="users/index.php" target="main" id="users"><div class="menu_item_row" ><div class="menu_item" >用户管理</div><div class="right_indicator" style="">></div></div></a>
<a href="users/add.php" target="main" id="user_add"  parent="users" ><div class="menu_item_row" ><div class="menu_item">》 添加用户</div><div class="right_indicator" style="">></div></div></a> 
 <a href="news/index.php" target="main" id="articles"><div class="menu_item_row" ><div class="menu_item" >文章管理</div><div class="right_indicator" style="">></div></div></a>
<a href="news_catalog/index.php " target="main" id="article_catalogs" parent="articles"><div class="menu_item_row" ><div class="menu_item" >》 文章分类</div><div class="right_indicator" style=""></div></div></a>
<a href="news/recycled.php" target="main" id="article_recycled"   parent="articles"><div class="menu_item_row" ><div class="menu_item">》 文章回收站</div><div class="right_indicator" style="">></div></div></a>

<a href="javascript://" target="main"  id="privileges_manage"><div class="menu_item_row"><div class="menu_item" >权限管理</div><div class="right_indicator" style="">></div></div></a>
<a href="admin/index.php" target="main"  id="admins" parent="privileges_manage"><div class="menu_item_row"><div class="menu_item" >》 管理员列表</div><div class="right_indicator" style="">></div></div></a>
<a href="admin/add.php" target="main"  id="admin_add"   parent="privileges_manage"><div class="menu_item_row"><div class="menu_item">》 添加管理员</div><div class="right_indicator" style="">></div></div></a>
<a href="roles/index.php" target="main"  id="roles"   parent="privileges_manage"><div class="menu_item_row"><div class="menu_item">》 角色列表</div><div class="right_indicator" style="">></div></div></a>
<a href="roles/add.php" target="main"  id="roles_add"   parent="privileges_manage"><div class="menu_item_row"><div class="menu_item">》 添加角色</div><div class="right_indicator" style="">></div></div></a>
<a href="privileges/index.php" target="main"  id="privileges"   parent="privileges_manage"><div class="menu_item_row"><div class="menu_item">》 权限列表</div><div class="right_indicator" style="">></div></div></a>
<a href="privileges/add.php" target="main"  id="privileges_add"   parent="privileges_manage"><div class="menu_item_row"><div class="menu_item">》 添加权限</div><div class="right_indicator" style="">></div></div></a>

<a href="admin/update_password.php" target="main" id="password"   parent="admins"><div class="menu_item_row" ><div class="menu_item">》 密码设置</div><div class="right_indicator" style="">></div></div></a>

<a href="info.php" target="main"  id="system"><div class="menu_item_row"><div class="menu_item">系统设置</div><div class="right_indicator" style="">></div></div></a>
<a href="pay_method/index.php" target="main" id="pay_methods" parent="system"><div class="menu_item_row" ><div class="menu_item">》 支付方式</div><div class="right_indicator" style="">></div></div></a>
<a href="pay_method/add.php" target="main" id="pay_methods_add"   parent="system"><div class="menu_item_row" ><div class="menu_item">》 添加支付</div><div class="right_indicator" style="">></div></div></a>

<a href="shipping_method/index.php" target="main" id="shipping_methods" parent="system"><div class="menu_item_row" ><div class="menu_item" >》 配送方式</div><div class="right_indicator" style="">></div></div></a>
<a href="shipping_method/add.php" target="main" id="shipping_methods_add"  parent="system"><div class="menu_item_row" ><div class="menu_item" >》 添加配送方式</div><div class="right_indicator" style="">></div></div></a>

<a href="express_company/index.php" target="main" id="logistics" parent="system"><div class="menu_item_row" ><div class="menu_item" >》 快递公司</div><div class="right_indicator" style=""></div></div></a>

<a href="info.php" target="main"  id="shop_info" parent="system"><div class="menu_item_row"><div class="menu_item">》 店铺信息</div><div class="right_indicator" style="">></div></div></a>
<a href="/admin/mail/" target="main" id="smtp" parent="system"><div class="menu_item_row"><div class="menu_item">》 邮件服务器</div><div class="right_indicator" style="">></div></div></a>
<a href="area/index.php" target="main" id="areas"  parent="system"><div class="menu_item_row" ><div class="menu_item">》 区域管理</div><div class="right_indicator" style="">></div></div></a>
<a href="http://www.123phpshop.com/client_portal/" target="main"  id="family"><div class="menu_item_row"><div class="menu_item" >123PHPSHOP家族软件</div><div class="right_indicator" style=""></div></div></a>
</div>
<script>
$().ready(function(){
	$("a[parent]").hide();
	$("a[parent] .right_indicator").hide();
	
$("a").click(function(){
  	$("a[parent="+$(this).attr('id')+"]").each(function(){
 		if($(this).attr("style")=="display: none;"){
			$(this).attr("style","display: inline;");
 		}else{
			$(this).attr("style","display: none;");
		}
		
	});
});
 
$(".menu_item_row").click(function(){
	$(".menu_item_row[id!="+$(this).attr('id')+"]").removeClass("menu_item_active");
	$(this).addClass("menu_item_active");
});
 
});
</script>
</body>
</html>
