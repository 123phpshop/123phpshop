<?php 
require_once('../../Connections/localhost.php'); 
//require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/privileges.php');

global $global_file_list_array;
$add_privielges_exception="";

function file_list_select($path)  
{  
	if ($handle = opendir($path))//打开路径成功  
	{  
		while (false !== ($file = readdir($handle)))//循环读取目录中的文件名并赋值给$file  
		{  
			$exclude_dir=array("js","css","Connections",".","..","_notes",".git",".gitignore","_mmServerScript");
			if (!in_array($file,$exclude_dir))//排除当前路径和前一路径  
			{  
				if (is_dir($path."/".$file))  
				{  
					global $global_file_list_array;
					$global_file_list_array[]=$path."/".$file; 
					file_list_select($path."/".$file);  
				}else  
				{  
				   global $global_file_list_array;
				   $global_file_list_array[]=$path."/".$file;  
				}  
			}  
		}  
	}
}
	 
file_list_select($_SERVER['DOCUMENT_ROOT']);
  
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
?>
<?php
// 检查权限的名称是否重复
$colname_getByName = "-1";
if (isset($_POST['name'])) {
  $colname_getByName = (get_magic_quotes_gpc()) ? $_POST['name'] : addslashes($_POST['name']);
}
mysql_select_db($database_localhost, $localhost);
$query_getByName = sprintf("SELECT * FROM privilege WHERE name = '%s'", $colname_getByName);
$getByName = mysql_query($query_getByName, $localhost) or die(mysql_error());
$row_getByName = mysql_fetch_assoc($getByName);
$totalRows_getByName = mysql_num_rows($getByName);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
// 检查上一级权限是否存在
$colname_getParent = "0";
if ( isset($_GET['parent_id']) && $_GET['parent_id']!="") {
  $colname_getParent = (get_magic_quotes_gpc()) ? $_GET['parent_id'] : addslashes($_GET['parent_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_getParent = sprintf("SELECT * FROM privilege WHERE id = %s", $colname_getParent);
$getParent = mysql_query($query_getParent, $localhost) or die(mysql_error());
$row_getParent = mysql_fetch_assoc($getParent);
$totalRows_getParent = mysql_num_rows($getParent);
?>
<?php
// 如果权限名称不重复的话，那么正式创建权限
if ($totalRows_getByName==0 && (isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
 
 	// 插入数据库
		$insertSQL = sprintf("INSERT INTO privilege (name, file_name, pid) VALUES (%s, %s, %s)",
					   GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['controller_action'], "text"),
                       GetSQLValueString($colname_getParent, "int"));
	 
  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

	if(isset($_POST['biz_rule']) && $_POST['biz_rule']!=""){
	
		// 创建权限文件
		$privilege=new Privilege();
		$privilege->id=mysql_insert_id();
		$privilege->biz_rule=$_POST['biz_rule'];
		
		$privileges=new Privileges();
		$add_result=$privileges->add($privilege);
		if(!$add_result){
			$add_privielges_exception="权限文件创建失败！";
		}
	}
	
	 if($add_privielges_exception==""){
		    $insertGoTo = "../privileges/index.php?parent=" . $colname_getParent;
		    header(sprintf("Location: %s", $insertGoTo));
	 }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">[添加权限]</p><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<?php if ($totalRows_getByName > 0) { // Show if recordset not empty ?>
  <p class="phpshop123_infobox">错误：权限名称重复！</p>
  <?php } // Show if recordset not empty ?>
  
  <?php if ($add_privielges_exception!="") { // Show if recordset not empty ?>
  <p>错误<?=$add_privielges_exception?></p>
  <?php } // Show if recordset not empty ?>
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <p>&nbsp;</p>
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input type="text" name="name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">文件:</td>
      <td><select name="controller_action">
        <?php  foreach($global_file_list_array as $file_item){?>
		<option value="<?=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?>"><?=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?></option>
		<?php }?>
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">菜单:</td>
      <td><label>
        <input name="is_menu" type="checkbox" id="is_menu" value="checkbox" />
        如果想要将这个权限设置为左侧的菜单项，请勾选此项
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">其他条件:</td>
      <td><label>
        <textarea name="biz_rule" cols="30" rows="5"></textarea>
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="插入记录"></td>
    </tr>
  </table>
  <input type="hidden" name="parent_id" value="">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($getByName);

mysql_free_result($getParent);
?>
