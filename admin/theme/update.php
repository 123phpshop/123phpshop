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
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

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
$theme = mysql_query($query_theme, $localhost) or die(mysql_error());
$row_theme = mysql_fetch_assoc($theme);
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
$support_email_question="更新模板";
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
