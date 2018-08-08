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
 *  作者:    123PHPSHOP团队
 *  手机:    13391334121
 *  邮箱:    service@123phpshop.com
 */
?><?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/Connections/localhost.php';
$colname_role_menu = "-1";
if (isset($_SESSION['role_id'])) {
    $colname_role_menu = (get_magic_quotes_gpc()) ? $_SESSION['role_id'] : addslashes($_SESSION['role_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_role_menu = sprintf("SELECT `privileges` FROM `role` WHERE id = %s", $colname_role_menu);
$role_menu = mysql_query($query_role_menu, $localhost);
if (!$role_menu) {$logger->fatal("数据库操作失败:" . $query_role_menu);}
$row_role_menu = mysql_fetch_assoc($role_menu);
$totalRows_role_menu = mysql_num_rows($role_menu);
mysql_select_db($database_localhost, $localhost);
if ($row_role_menu['privileges'] == "1") {
    //  如果是全部权限的话权限的话，那么不进行过滤
    $query_menu = "SELECT id,name,file_name FROM privilege WHERE pid=0 and is_delete=0 and is_menu=1 order by sort asc";
} else {
    // 如果是有限权限的话，那么进行过滤
    $query_menu = "SELECT id,name,file_name FROM privilege WHERE pid=0 and is_delete=0 and is_menu=1 and id in (" . $row_role_menu['privileges'] . ") order by sort asc";
}
$menu = mysql_query($query_menu, $localhost);
if (!$menu) {$logger->fatal("数据库操作失败:" . $query_menu);}
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {

    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_id']);
    unset($_SESSION['role_id']);
    unset($_SESSION['privileges']);
    unset($_SESSION['PrevUrl']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_id']);
    unset($_SESSION['PrevUrl']);

    $logoutGoTo = "login.php";
    if ($logoutGoTo) {
        header("Location: $logoutGoTo");
        exit;
    }
}

?>
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
<div class="menu_title" style="text-align:center;padding-left:0px;line-height:66px;color:#FFFFFF;height:66px;">欢迎<?php echo $_SESSION['admin_username']; ?><a href="<?php echo $logoutAction ?>" target="_parent"> [退出]</a></div>
  <?php do {?>
  <a href="<?php echo $row_menu['file_name'] == '' ? 'javascript://' : $row_menu['file_name']; ?>" target="main"  id="menu_item_<?php echo $row_menu['id']; ?>">
  <div class="menu_item_row" style="border-top:1px solid #515151;">
    <div class="menu_item"><?php echo $row_menu['name']; ?></div>
    <div class="right_indicator">></div>
  </div>
  </a>
  <?php

    // 这里需要检查是否拥有admin全部的权限，如果有的话，那么

    mysql_select_db($database_localhost, $localhost);
    if ($row_role_menu['privileges'] == "1") {
        //  如果是全部权限的话权限的话，那么不进行过滤
        $query_sub_menu = "SELECT * FROM privilege WHERE pid = " . $row_menu['id'] . " and is_delete=0 and is_menu=1 order by sort asc";
    } else {
        $query_sub_menu = "SELECT * FROM privilege WHERE pid = " . $row_menu['id'] . " and is_delete=0 and is_menu=1 and id in (" . $row_role_menu['privileges'] . ") order by sort asc";
    }

    $sub_menu = mysql_query($query_sub_menu, $localhost);if (!$sub_menu) {$logger->fatal("数据库操作失败:" . $query_sub_menu);}
    $row_sub_menu = mysql_fetch_assoc($sub_menu);
    $totalRows_sub_menu = mysql_num_rows($sub_menu);

    if ($totalRows_sub_menu > 0) {
        ?>
<?php do {?>
	<a href="<?php echo $row_sub_menu['file_name']; ?><?php echo $row_sub_menu['para']; ?>" target="main"  id="goods_index" parent="menu_item_<?php echo $row_menu['id']; ?>"><div class="menu_item_row"><div class="menu_item" >》 <?php echo $row_sub_menu['name']; ?></div><div class="right_indicator" style="">></div></div></a>
<?php } while ($row_sub_menu = mysql_fetch_assoc($sub_menu));?>
<?php }?>
<?php } while ($row_menu = mysql_fetch_assoc($menu));?>

<a href="http://www.123phpshop.com/client_portal/" target="main"  id="family"><div class="menu_item_row"><div class="menu_item" >123PHPSHOP家族软件</div><div class="right_indicator" style="">></div></div></a>
<a href="http://www.123phpshop.com/services.php" target="main"  id="family_app" parent="family"><div class="menu_item_row"><div class="menu_item" >服务</div><div class="right_indicator" style=""></div></div></a>
<a href="http://www.123phpshop.com/doc/v1.4/" target="main"  id="family_app" parent="family"><div class="menu_item_row"><div class="menu_item" >文档</div><div class="right_indicator" style=""></div></div></a>

<a href="http://www.123phpshop.com/register.php" target="main"  id="family_register" parent="family"><div class="menu_item_row"><div class="menu_item" >注册</div><div class="right_indicator" style=""></div></div></a>
<a href="http://www.123phpshop.com/login.php" target="main"  id="family_login" parent="family"><div class="menu_item_row"><div class="menu_item" >登录</div><div class="right_indicator" style=""></div></div></a>
<a href="http://www.123phpshop.com/product_list.php" target="main"  id="family_app" parent="family"><div class="menu_item_row"><div class="menu_item" >插件</div><div class="right_indicator" style=""></div></div></a>
<a href="http://tieba.baidu.com/f?ie=utf-8&kw=123phpshop" target="main"  id="family_app" parent="family"><div class="menu_item_row"><div class="menu_item" >论坛</div><div class="right_indicator" style=""></div></div></a>
</div>
<script>
$().ready(function(){
	$("a[parent]").hide();
	$("a[parent] .right_indicator").hide();

$("a").click(function(){
  	$("a[parent="+$(this).attr('id')+"]").each(function(){

  		if($(this).css('display')=='none'){
 			$(this).css("display","inline");
 		}else{
			$(this).css("display","none");
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
<?php
mysql_free_result($menu);

mysql_free_result($sub_menu);

mysql_free_result($role_menu);
?>