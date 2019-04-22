<?php 
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
 


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
 echo  $updateSQL = sprintf("UPDATE theme SET name=%s, folder_name=%s, author=%s, version=%s, contact=%s, intro=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['folder_name'], "text"),
                       GetSQLValueString($_POST['author'], "text"),
                       GetSQLValueString($_POST['version'], "text"),
                       GetSQLValueString($_POST['contact'], "text"),
                       GetSQLValueString($_POST['intro'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysqli_query($localhost,$updateSQL);
  if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_theme = "-1";
if (isset($_GET['id'])) {
  $colname_theme = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_theme = sprintf("SELECT * FROM theme WHERE id = %s", $colname_theme);
$theme = mysqli_query($localhost,$query_theme);
if(!$theme){$logger->fatal("数据库操作失败:".$query_theme);}
$row_theme = mysqli_fetch_assoc($theme);
$totalRows_theme = mysql_num_rows($theme);

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

$doc_url="template.html#update";
$support_email_question="更新模板";log_admin($support_email_question);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <span><span class="phpshop123_title">更新模板</span><a href="index.php">
    <input style="float:right;" type="submit" name="Submit2" value="模板列表" />
  </a></span>
  
  <a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a>
  

<form method="post" name="form1"  id="form1"  action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input type="text" name="name" id="name"  value="<?php echo $row_theme['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">文件夹:</td>
      <td><select name="folder_name"  id="folder_name"  >
         <?php foreach($theme_array as $them_name){ ?>
        <option value="<?php echo $them_name;?>" <?php if($row_theme['folder_name']==$them_name){ ?> selected <?php } ?>><?php echo $them_name;?></option>
     <?php } ?>
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">作者:</td>
      <td><input type="text" name="author"  id="author"  value="<?php echo $row_theme['author']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">版本:</td>
      <td><input type="text" name="version"  id="version"  value="<?php echo $row_theme['version']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">联系邮箱:</td>
      <td><input type="text" name="contact"  id="contact"  value="<?php echo $row_theme['contact']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">简介：</td>
      <td><textarea name="intro" cols="50" rows="10" id="intro"  ><?php echo $row_theme['intro']; ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_theme['id']; ?>">
</form>
<p>&nbsp;</p>
<p class="phpshop123_title">&nbsp;</p>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
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
});</script>
</body>
</html>
<?php
mysql_free_result($theme);
?>
