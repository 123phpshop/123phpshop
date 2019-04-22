<?php 
ob_start();
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
 ?><?php require_once('Connections/localhost.php'); ?>
<?php
$error=array();
$error="";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$query_shopinfo = "SELECT * FROM shop_info WHERE id = 1";
$shopinfo = mysqli_query($localhost,$query_shopinfo);
if(!$shopinfo){
	$logger->warn("获取店铺信息数据库操作失败");
}

$row_shopinfo = mysqli_fetch_assoc($shopinfo);
$totalRows_shopinfo = mysqli_num_rows($shopinfo);
if($totalRows_shopinfo==0){
	$logger->warn("获取店铺信息失败，没有找到店铺id为1的记录。");
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
try{
	// 这里对字段进行验证
 	$validation->set_rules('username', '用户名', 'required|min_length[6]|max_length[18]|alpha_dash');
	$validation->set_rules('password', '密码',  'required|min_length[8]|max_length[18]|alpha_dash');
	$validation->set_rules('passconf', '密码验证', 'required|matches[passconf]');
 	if (!$validation->run())
	{
	   throw new Exception($validation->error_string('<br>',''));
   	}

	//		检查用户名是否已经被占用
	$colname_get_user_by_username = "-1";
	if (isset($_POST['username'])) {
	  $colname_get_user_by_username = (get_magic_quotes_gpc()) ? $_POST['username'] : addslashes($_POST['username']);
	}
	
	$query_get_user_by_username = sprintf("SELECT * FROM `user` WHERE username = '%s'", $colname_get_user_by_username);
	$get_user_by_username = mysqli_query($localhost,$query_get_user_by_username);
	if(!$get_user_by_username){$logger->fatal("数据库操作失败:".$query_get_user_by_username);}
	$row_get_user_by_username = mysqli_fetch_assoc($get_user_by_username);
	$totalRows_get_user_by_username = mysqli_num_rows($get_user_by_username);
 	if($totalRows_get_user_by_username>0){
 		 throw new Exception("用户名已经被占用，请修改后重试！");
	}
 	
	//	 如果用户名没有被占用的话
	if($totalRows_get_user_by_username==0){
		$insertSQL = sprintf("INSERT INTO user (username, password,register_at,last_login_at,last_login_ip) VALUES (%s, %s, %s, %s, %s)",
		
			GetSQLValueString($_POST['username'], "text"),
			GetSQLValueString(md5($_POST['password']), "text"),
			GetSQLValueString(date('Y-m-d H:i:s'), "text"),
			GetSQLValueString(date('Y-m-d H:i:s'), "text"),
			GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"));
	
	  
	  $Result1 = mysqli_query($localhost,$insertSQL);
 
		if(! $Result1>0){
			 $logger->fatal("用户注册错误：".mysqli_error($localhost).$insertSQL);
			 throw new Exception("系统错误，请稍后重试");
		}
	
	  // 这里需要初始化一个session的值
	   //declare two session variables and assign them
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['MM_UserGroup'] = "";	      
		$_SESSION['user_id'] = mysql_insert_id();
	
	 // 如果注册成功的话
	  $insertGoTo = "index.php";
 	  header(sprintf("Location: %s", $insertGoTo));
	}
	
	}catch(Exception $ex){
 		 $error=$ex->getMessage();
	}
}
 include($template_path."register.php");
?>