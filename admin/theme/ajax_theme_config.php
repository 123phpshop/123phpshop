<?php require_once('../../Connections/localhost.php'); ?>
<?php
$result=array();
$result['code']="0";
$result['data']="";
$result['message']="SUCCEED";
$folder_name=$_POST['folder_name'];
$theme_folder_dir=$www_root."/theme/".$folder_name;
$theme_config_dir=$www_root."/theme/".$folder_name."/config.php";

try{

// 检查post过来的文件夹参数是否正常
if(empty($folder_name)){
		throw new Exception("参数错误！请联系123phpshop.com获取解决方案");
}
// 如果文件夹名称合法的话，那么检查是否存在
if(!is_dir($theme_folder_dir)){
	throw new Exception("模板文件夹不存在！请联系123phpshop.com获取解决方案");
}
// 如果存在的话，那么检查配置文件是否存在
if(!is_file($theme_config_dir)){
	throw new Exception("模板配置文件不存在！请联系123phpshop.com获取解决方案");
}

include($theme_config_dir);
// 如果存在的话，那么加载配置文件返回
$result['data']=$theme_config;
}catch(Exception $ex){
	$result['code']="1";
 	$result['message']=$ex->getMessage();
} 
	echo 	json_encode($result);
 