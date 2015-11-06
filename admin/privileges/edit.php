<?php require_once('../../Connections/localhost.php'); ?>
<?php  require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/privileges.php');?>

<?php
global $global_file_list_array;
$privielges_exceptions="";
$privielges_biz_rule="";

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

function file_list_select($path)  
{  
	if ($handle = opendir($path))//打开路径成功  
	{  
		while (false !== ($file = readdir($handle)))//循环读取目录中的文件名并赋值给$file  
		{  
			$exclude_dir=array("js","css","Connections",".","..","_notes",".git",".gitignore","_mmServerScripts");
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
?>
<?php
//	检查ID是否存在
$colname_getById = "-1";
if (isset($_GET['id'])) {
  $colname_getById = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_getById = sprintf("SELECT * FROM privilege WHERE id = %s", $colname_getById);
$getById = mysql_query($query_getById, $localhost) or die(mysql_error());
$row_getById = mysql_fetch_assoc($getById);
$totalRows_getById = mysql_num_rows($getById);
?>
<?php
//	获取biz_rule
if($totalRows_getById>0){
	$privileges=new Privileges();
	$privielges_biz_rule=$privileges->get_by_id($colname_getById);
}
?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($totalRows_getById>0 && (isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
		$updateSQL = sprintf("UPDATE privilege SET name=%s,is_menu=%s, file_name=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
					   GetSQLValueString($_POST['is_menu'], "int"),
                       GetSQLValueString($_POST['file_name'], "text"),
                       GetSQLValueString($_POST['id'], "int"));
  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
 
 //	 这里我们需要对权限约束文件进行更新
	$privilege=new Privilege();
	$privilege->id=$colname_getById;
	$privilege->biz_rule=$_POST['biz_rule'];

	$privileges=new Privileges();
	$update_result=$privileges->update($privilege);
	
	if(!$update_result){
		$privielges_exceptions="权限文件更新失败！";
	}
	
	if($privielges_exceptions==""){
	   $updateGoTo = "index.php?parent=".$row_getById['parent_id'];
	   header(sprintf("Location: %s", $updateGoTo));
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">编辑权限</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>

<?php if ($totalRows_getById == 0) { // Show if recordset empty ?>
  <table  class="error_box" width="101%" border="0">
    <tr>
      <th scope="row"><div align="left" class="phpshop123_infobox">错误：权限不存在</div></th>
    </tr>
  </table>
 
    <?php } // Show if recordset empty ?>
	
	<?php if ($privielges_exceptions!="") { // Show if recordset empty ?>
  <table  class="error_box" width="101%" border="0">
    <tr>
      <th scope="row"><div align="left" class="phpshop123_infobox">错误：
        <?=$privielges_exceptions?>
      </div></th>
    </tr>
  </table>
 
    <?php } // Show if recordset empty ?>
	
	 <?php if ($totalRows_getById > 0) { // Show if recordset not empty ?>
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table align="center" class="phpshop123_form_box">
      <tr valign="baseline">
        <td nowrap align="right">名称:</td>
        <td><input type="text" name="name" value="<?php echo $row_getById['name']; ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">文件:</td>
        <td>
		<select name="file_name">
			<?php  foreach($global_file_list_array as $file_item){?>
				<option <?php if(str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item)==$row_getById['file_name']){ echo " selected ";}?> value="<?=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?>"><?=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?></option>
			<?php }?>
		</select>		</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">菜单:</td>
        <td><label>
          <input <?php if (!(strcmp($row_getById['is_menu'],1))) {echo "checked=\"checked\"";} ?> name="is_menu" type="checkbox" id="is_menu" value="checkbox" />
          如果想要将这个权限设置为左侧的菜单项，请勾选此项 </label></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right" valign="top">约束:</td>
        <td><textarea name="biz_rule" cols="50" rows="5"><?php echo $privielges_biz_rule; ?></textarea>        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">&nbsp;</td>
        <td><input type="submit" value="更新记录"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id" value="<?php echo $row_getById['id']; ?>">
  </form>
  <?php } // Show if recordset not empty ?><p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($getById);
?>
