<?php require_once('../../Connections/localhost.php'); ?>
<?php
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
$error="";
try{
// 检查模板文件夹是否存在，如果不存在那么告知退出
$theme_folder_dir=$www_dir."/theme/".$_GET['folder_name'];
if(!is_dir($theme_folder_dir)){
	throw new Exception("模板不存在！欢迎拨打13391334121进行咨询！");
}
  
// 检查模板文件夹中的配置文件是否存在，如果不存在，那么告知退出
$theme_folder_config_path=$www_dir."/theme/".$_GET['folder_name']."/config.php";
if(!is_file($theme_folder_config_path)){
	throw new Exception("模板配置不存在！欢迎拨打13391334121进行咨询！");
}

// 检查配置文件中的定义是否合法，如果不合法，那么告知退出
include($theme_folder_config_path);
if(!isset($theme_config['author']) || $theme_config['author']!=''){

}
}catch(Exception $ex){
	$error=$ex->getMessage();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO theme (name, folder_name, author, version, contact, is_default) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['folder_name'], "text"),
                       GetSQLValueString($_POST['author'], "text"),
                       GetSQLValueString($_POST['version'], "text"),
                       GetSQLValueString($_POST['contact'], "text"),
                       GetSQLValueString(isset($_POST['is_default']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

// 获取模板文件夹的
$theme_array=array();
$theme_folder=$www_root."/theme/";
if(is_dir($theme_folder)){
	$dirs=scandir($theme_folder);
	foreach($dirs as $dir){
		if($dir!="." && $dir!=".." && !is_dir($dir) ){
			$theme_array[]=$dir;
		}
	}
}

$doc_url="theme.html#add";
$support_email_question="添加模板";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">安装模板</p>
 
</body>
</html>
