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
mysql_select_db($database_localhost, $localhost);
$query_shopinfo = "SELECT * FROM shop_info WHERE id = 1";
$shopinfo = mysql_query($query_shopinfo, $localhost) or die(mysql_error());
$row_shopinfo = mysql_fetch_assoc($shopinfo);
$totalRows_shopinfo = mysql_num_rows($shopinfo);
?>
<?php
// *** Validate request to login to this site.


$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

 if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php?error=用户名或密码错误，请重新输入";
  $MM_redirectLoginFailed_captcha_error = "login.php?error=验证码错误，请重新输入";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_localhost, $localhost);
  
  //	  检查是否输入了验证码？如果么有输入,或是输入的验证码是否和SESSION中的验证码不一致，那么直接跳转到失败页面
  if(!isset($_POST['captcha']) OR $_POST['captcha']!=$_SESSION['captcha']){
  		 header("Location: ". $MM_redirectLoginFailed_captcha_error );
		 return;
  }
  
     $LoginRS__query=sprintf("SELECT id,username, password FROM user WHERE username='%s' AND password='%s' and is_delete=0",
    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? md5($password ): addslashes(md5($password))); 
   
  $LoginRS = mysql_query($LoginRS__query, $localhost) or die(mysql_error());
  $user=mysql_fetch_assoc( $LoginRS);
      $loginFoundUser = mysql_num_rows($LoginRS);
    if ($loginFoundUser) {
     $loginStrGroup = "";
   
    //declare two session variables and assign them
    $_SESSION['username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
	$_SESSION['user_id'] = $user['id'];
	$last_login_at=date('Y-m-d H:i:s');
	$last_login_ip=$_SERVER['REMOTE_ADDR'];
	$update_last_login_sql="update user set last_login_at='".$last_login_at."', last_login_ip='".$last_login_ip."' where id=".$user['id'];
	mysql_query($update_last_login_sql, $localhost);
    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
include($template_path."login.php");
?>
