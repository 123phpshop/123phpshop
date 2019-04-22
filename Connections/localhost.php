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
	header(sprintf("Location: %s", $install_url));exit();
}
$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("set names utf8");
require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/start.php";
?>