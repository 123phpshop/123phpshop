<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php');  
/**
	以下几种情况不做验证：
	1. 管理员登录页面
	2. 该用户拥有全部的权限
**/ 

 
if(_is_admin_area() && !_is_admin_login_page () && !_is_admin_index() &&   $_SESSION['privileges']!=array("/admin") && !in_array($_SERVER['REQUEST_URI'],$_SESSION['privileges_array'])){
	 
	 
	 var_dump($_SESSION);
 	  /*$_SESSION['admin_username'] = NULL;
	  $_SESSION['admin_id'] = NULL;
	  $_SESSION['PrevUrl'] = NULL;
	  unset($_SESSION['admin_username']);
	  unset($_SESSION['admin_id']);
	  unset($_SESSION['PrevUrl']);*/
  
 	$to_illegal = "/admin/illegal_access.php";
	header(sprintf("Location: %s", $to_illegal)); 
}



