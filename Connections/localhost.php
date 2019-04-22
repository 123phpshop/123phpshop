<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
error_reporting(0); 
$hostname_localhost = "";
$database_localhost = "";
$username_localhost = "";
$password_localhost = "";
if($hostname_localhost==""){
	$install_url = '/install/';
	$home_url = '/';

	//    检查当前是不属于安装区域
	if (!_is_install_area()) {
		//    检查是否已经安装，如果没有安装，那么跳转到安装区域
		if (!isset($hostname_localhost) || trim($hostname_localhost) == "") {
			header(sprintf("Location: %s", $install_url));exit();
		}
	} else {
		//    检查是否已经安装，如果没有安装，那么跳转到安装区域
		if (isset($hostname_localhost) && trim($hostname_localhost) != "") {
			header(sprintf("Location: %s", $home_url));exit();
		}
	}
}
$localhost = mysqli_connect($hostname_localhost, $username_localhost, $password_localhost,$database_localhost) or trigger_error(mysqli_error($localhost),E_USER_ERROR);
mysqli_query($localhost,"set names utf8");
require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/start.php";
/**
 * 检查当前页面是否属于管理员页面。
 */
function _is_install_area()
{
    $curr_url = $_SERVER['REQUEST_URI'];
    return strpos($curr_url, '/install/') > -1;
}

?>