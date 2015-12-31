
<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
error_reporting(E_ALL); 
$hostname_localhost = "localhost";
$database_localhost = "123phpshopv15";
$username_localhost = "root";
$password_localhost = "root";
if($hostname_localhost==""){
	require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/check_install.php";
	return;
}
$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("set names utf8");
require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/start.php";?>