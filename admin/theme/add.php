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
 ?><?php require_once('../../Connections/localhost.php'); ?>
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

//$logger->debug("准备添加模板"); 
$theme_folders=array();
$doc_url="template.html#add";
$support_email_question="添加模板";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	//  这里需要对post过来的参数进行验证
   $insertSQL = sprintf("INSERT INTO theme (name, folder_name, author, version, contact, intro) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['folder_name'], "text"),
                       GetSQLValueString($_POST['author'], "text"),
                       GetSQLValueString($_POST['version'], "text"),
                       GetSQLValueString($_POST['contact'], "text"),
                       GetSQLValueString($_POST['intro'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost);
  if(!$Result1){
	  
  	 $logger->fatal(mysql_error().$insertSQL);
  }
  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

// 获取模板的文件夹列表
mysql_select_db($database_localhost, $localhost);
$query_get_themes = "SELECT folder_name FROM theme";
$get_themes = mysql_query($query_get_themes, $localhost);
if(!$get_themes){
	$logger->warn("查询错误".$query_get_themes);
}
 $totalRows_get_themes = mysql_num_rows($get_themes);
if($totalRows_get_themes>0){
	while($row_get_themes= mysql_fetch_assoc($get_themes)){
		$theme_folders[]=$row_get_themes['folder_name'];
	}
}

//	$logger->debug("数据库中的模板的文件夹列表：".print_r($theme_folders,1));
// 获取模板文件夹的列表
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

//	$logger->debug("模板文件夹列表：".print_r($theme_array,1));

// 获取这两个文件夹的差集合
$theme_array = array_diff( $theme_array,$theme_folders);

//	$logger->debug("差集：".print_r($theme_array,1));


sort($theme_array);
//	$logger->debug("重新排列之后：".print_r($theme_array,1));


// 获取第一个模板的配置文件
$theme_config=array();
$theme_config['name']="";
$theme_config['author']="";
$theme_config['version']="";
$theme_config['intro']="";
$theme_config['contact']="";
if(count($theme_array)>0){
	$config_path=$theme_folder.$theme_array[0]."/config.php";
  	include_once($config_path);
}
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
-->
</style>
</head>

<body>
<span class="phpshop123_title">添加模板 <a href="index.php">
  <input style="float:right;" type="submit" name="Submit2" value="模板列表" />
</a></span><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a>
<?php if(count($theme_array)>0){ ?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
     <tr valign="baseline">
      <td nowrap align="right">文件夹:</td>
      <td><select name="folder_name" id="folder_name" onchange="get_theme_config()">
	  <?php foreach($theme_array as $them_name){ ?>
        <option value="<?php echo $them_name;?>" ><?php echo $them_name;?></option>
     <?php } ?>
      </select>
      [选择相应的文件夹会自动加载相关的配置]<span class="STYLE1">[</span><a href="http://www.123phpshop.com/product_list.php?keywords=&amp;catalog=20&amp;license=&amp;version=" target="_blank" class="STYLE1">购买模板</a><span class="STYLE1">][<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1718101117&amp;site=qq&amp;menu=yes" target="_blank" class="STYLE1">联系客服</a>]</span></td>
    </tr>
	<tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input type="text" name="name" id="name" value="<?php echo $theme_config['name'];?>" size="32"></td>
    </tr>
   
    <tr valign="baseline">
      <td nowrap align="right">作者:</td>
      <td><input type="text" name="author" id="author" value="<?php echo $theme_config['author'];?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">版本:</td>
      <td><input type="text" name="version" id="version" value="<?php echo $theme_config['version'];?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">联系邮件:</td>
      <td><input type="text" name="contact" id="contact" value="<?php echo $theme_config['contact'];?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">简介：</td>
      <td><textarea name="intro" cols="50" rows="10" id="intro" ><?php echo $theme_config['intro'];?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </table>
  <p>
    <input type="hidden" name="MM_insert" value="form1">
</p>
</form>
<?php }else{ ?>
   <p class="phpshop123_infobox">所有模板都已经入库，如果需要新的模板服务，请联右上角的QQ图标联系我们的客服人员，或是<a href="http://www.123phpshop.com/product_list.php?keywords=&amp;catalog=20&amp;license=&amp;version=" target="_blank">【点击这里访问我们的模板列表】</a></p>
  <?php } ?>
    <script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>

function get_theme_config(){
	var folder_name=$("#folder_name").val();
	var url="ajax_theme_config.php";
	var post_data={folder_name:folder_name};
	
	$.post(url,post_data,function(data){
		if(data.code=="0"){
			$("#name").val(data.data.name);
			$("#author").val(data.data.author);
			$("#version").val(data.data.version);
			$("#contact").val(data.data.contact);
			$("#intro").val(data.data.intro);
			return;
		}
		alert(data.message);
		return;
	},'json');
}

$().ready(function(){
 	$("#form1").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
				maxlength: 32
             },
			remote:{
				url: "_ajax_code.php",
				type: "post",
				dataType: 'json',
				data: {
					'code': function(){return $("#code").val();}
				}
			},
            author: {
                required: true,
				minlength: 2,
				maxlength: 32  
            },
            version: {
                required: true ,
				minlength: 2,
				maxlength: 10
            },
            contact: {
                required: true ,
				minlength: 2,
				maxlength: 32,
				email:true
            },
            intro: {
                required: true,
				minlength: 2,
				maxlength: 200
            }
        } 
    });
});
</body>
</html>
