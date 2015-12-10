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
 ?><?php
//
// AudiStat v1.2 by Alexandre Dubus
// April 2003
//
// Add to the beginning of all your PHP pages (in a PHP block code) :
// require("stats/stats.php");
// 
// Log all 404 errors
// Add to your .htaccess (you MUST put an absolute URL)
// ErrorDocument 404 /stats/stats.php

require "config.php";

function db_open () {
	global $link;
	global $sql_host, $sql_login,$sql_passe,$sql_dbase;
	$link = mysql_connect($sql_host, $sql_login,$sql_passe)
	        or die ("Can't connect to $sql_host: $!\n");
	mysql_select_db ($sql_dbase)
	        or die ("Can't select database $sql_dbase: $!\n");
}
	
function db_close () {
	global $link;
	mysql_close($link);
}

function mysql_protect($s) {
	return "\"" . mysql_escape_string ($s) . "\"";
}

function db_add_record() {
	global $sql_table;
	global $_SERVER;

	if (isset($_SERVER['REMOTE_ADDR']))
		$remote_host	= $_SERVER['REMOTE_ADDR'];
	else
		$remote_host	= "-";

	if (isset($_SERVER['HTTP_REFERER']))
		$referer	= $_SERVER['HTTP_REFERER'];
	else
		$referer	= "-";

	if (isset($_SERVER['HTTP_USER_AGENT']))
		$user_agent	= $_SERVER['HTTP_USER_AGENT'];
	else
		$user_agent	= "-";


	$request	= "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	$a_remote_host	= mysql_protect($remote_host);
	$a_request	= mysql_protect($request);
	$a_referer	= mysql_protect($referer);
	$a_user_agent	= mysql_protect($user_agent);

	$q1 = "CREATE TABLE IF NOT EXISTS $sql_table ( time_str DATETIME, remote_host TEXT, request TEXT, referer TEXT, user_agent TEXT )";
	$r1 = mysql_query($q1);
	if (!$r1)
		print "query failed : " . mysql_error() . " : $query\n";

	$query = "INSERT $sql_table (time_str, remote_host, request, referer, user_agent) VALUES (NOW(), $a_remote_host, $a_request, $a_referer, $a_user_agent)";

	$result = mysql_query($query);
	if (!$result)
	        print "query failed : " . mysql_error() . " : $query\n";
}

# remove displaying of errors, warning ini_set is disabled on free.fr
error_reporting(0);

db_open ();
db_add_record();
db_close();
?>