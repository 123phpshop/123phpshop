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
<?php  require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/privileges.php');?>

<?php
global $global_file_list_array;
$privielges_exceptions="";
$privielges_biz_rule="";
$doc_url="privilege.html#update";
$support_email_question="编辑权限";
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
/*if($totalRows_getById>0){
	$privileges=new Privileges();
	$privielges_biz_rule=$privileges->get_by_id($colname_getById);
}*/
?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($totalRows_getById>0 && (isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
		$updateSQL = sprintf("UPDATE privilege SET name=%s,is_menu=%s, file_name=%s, sort=%s, para=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
					   GetSQLValueString(isset($_POST['is_menu'])?1:0, "int"),
                       GetSQLValueString($_POST['file_name'], "text"),
					   GetSQLValueString($_POST['sort'], "int"),
                        GetSQLValueString($_POST['para'], "text"),
                       GetSQLValueString($_POST['id'], "int"));
  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
 
 //	 这里我们需要对权限约束文件进行更新
	/*$privilege=new Privilege();
	$privilege->id=$colname_getById;
	$privilege->biz_rule=$_POST['biz_rule'];

	$privileges=new Privileges();
	$update_result=$privileges->update($privilege);
	
	if(!$update_result){
		$privielges_exceptions="权限文件更新失败！";
	}*/
	
	if($privielges_exceptions==""){
	   $updateGoTo = "index.php?parent_id=".$row_getById['pid'];
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
<span class="phpshop123_title">编辑权限</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>

<?php if ($totalRows_getById == 0) { // Show if recordset empty ?>
  <a href="index.php">
  <input style="float:right;" type="submit" name="Submit2" value="权限列表" />
  </a>
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
		<option value="">不设置</option>
 			<?php  foreach($global_file_list_array as $file_item){?>
				<option <?php if(str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item)==$row_getById['file_name']){ echo " selected ";}?> value="<?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?>"><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?></option>
			<?php }?>
		</select>
 		<input name="para" type="text" id="para" value="<?php echo $row_getById['para']; ?>"/>
	    </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">菜单:</td>
        <td><label>
          <input <?php if ($row_getById['is_menu']==1) {echo "checked=\"checked\"";} ?> name="is_menu" type="checkbox" id="is_menu" value="checkbox" />
          如果想要将这个权限设置为左侧的菜单项，请勾选此项 </label></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right" valign="top">排序：</td>
        <td><label>
          <input name="sort" type="text" id="sort" value="<?php echo $row_getById['sort']; ?>"/>
        </label></td>
      </tr>
      <!--tr valign="baseline">
        <td nowrap align="right" valign="top">约束:</td>
        <td><textarea name="biz_rule" cols="50" rows="5"><?php echo $privielges_biz_rule; ?></textarea>        </td>
      </tr-->
      <tr valign="baseline">
        <td nowrap align="right">&nbsp;</td>
        <td><input type="submit" value="更新记录"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id" value="<?php echo $row_getById['id']; ?>">
  </form>
  <?php } // Show if recordset not empty ?><script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#new_consignee_form").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
             },
            mobile: {
                required: true,
                minlength: 11,
				digits:true   
            },
            address: {
                required: true,
                minlength: 3   
            },
 			zip: {
                required: true,
                minlength: 6,
				digits:true
            }
        } 
    });
});</script>
</body>
</html>
<?php
mysql_free_result($getById);
?>