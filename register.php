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
 ?><?php require_once('Connections/localhost.php'); ?>
<?php
$error=array();

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

mysql_select_db($database_localhost, $localhost);
$query_shopinfo = "SELECT * FROM shop_info WHERE id = 1";
$shopinfo = mysql_query($query_shopinfo, $localhost) or die(mysql_error());
$row_shopinfo = mysql_fetch_assoc($shopinfo);
$totalRows_shopinfo = mysql_num_rows($shopinfo);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	//		检查用户名是否已经被占用
	$colname_get_user_by_username = "-1";
	if (isset($_POST['username'])) {
	  $colname_get_user_by_username = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_get_user_by_username = sprintf("SELECT * FROM `user` WHERE username = '%s'", $colname_get_user_by_username);
	$get_user_by_username = mysql_query($query_get_user_by_username, $localhost) or die(mysql_error());
	$row_get_user_by_username = mysql_fetch_assoc($get_user_by_username);
	$totalRows_get_user_by_username = mysql_num_rows($get_user_by_username);
 
 	
	//	 如果用户名没有被占用的话
	if($totalRows_get_user_by_username==0){
		$insertSQL = sprintf("INSERT INTO user (username, password,register_at,last_login_at,last_login_ip) VALUES (%s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['username'], "text"),
						   GetSQLValueString(md5($_POST['password']), "text"),
						    GetSQLValueString(date('Y-m-d H:i:s'), "text"),
							 GetSQLValueString(date('Y-m-d H:i:s'), "text"),
							  GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"));
	
	  mysql_select_db($database_localhost, $localhost);
	  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
	
	  // 这里需要初始化一个session的值
	   //declare two session variables and assign them
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['MM_UserGroup'] = "";	      
	$_SESSION['user_id'] = mysql_insert_id();
	
	
	  $insertGoTo = "index.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
	}
  
}

include($template_path."register.php");
?>