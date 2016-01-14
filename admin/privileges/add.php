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
 ?><?php require_once('../../Connections/localhost.php');  
//require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/privileges.php');

global $global_file_list_array;
$add_privielges_exception="";

function file_list_select($path)  
{  
	if ($handle = opendir($path))//打开路径成功  
	{  
		while (false !== ($file = readdir($handle)))//循环读取目录中的文件名并赋值给$file  
		{  
			$exclude_dir=array("js","css","Connections",".","uploads","_mmServerScripts",".settings","..","_notes",".git",".gitignore","_mmServerScript");
			
			$exclode_file_array=array(".settings",".project",".buildpath");
 			if (!in_array($file,$exclude_dir))//排除当前路径和前一路径  
			{   
				if (is_dir($path."/".$file))  
				{  
					   if(!in_array($file,$exclode_file_array)){
						global $global_file_list_array;
						$global_file_list_array[]=$path."/".$file; 
						file_list_select($path."/".$file);
					 }
 				}else  
				{  
 					   if(!in_array($file,$exclode_file_array)){
						   global $global_file_list_array;
						   $global_file_list_array[]=$path."/".$file;   
					   }
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
$doc_url="privilege.html#add";
$support_email_question="添加权限";
$colname_getByName = "-1";
if (isset($_POST['name'])) {
  $colname_getByName = (get_magic_quotes_gpc()) ? $_POST['name'] : addslashes($_POST['name']);
}
mysql_select_db($database_localhost, $localhost);
$query_getByName = sprintf("SELECT * FROM privilege WHERE name = '%s'", $colname_getByName);
$getByName = mysql_query($query_getByName, $localhost) ;
if(!$getByName){$logger->fatal("数据库操作失败:".$query_getByName);}
$row_getByName = mysql_fetch_assoc($getByName);
$totalRows_getByName = mysql_num_rows($getByName);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>

<?php

$colname_getParent = "0";
if ( isset($_GET['parent_id']) && $_GET['parent_id']!="") {
  $colname_getParent = (get_magic_quotes_gpc()) ? $_GET['parent_id'] : addslashes($_GET['parent_id']);
}


// 如果权限名称不重复的话，那么正式创建权限
if ($totalRows_getByName==0 && (isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
 
 	// 插入数据库
		$insertSQL = sprintf("INSERT INTO privilege (name, file_name, pid,sort,para) VALUES (%s, %s, %s, %s, %s)",
					   GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['controller_action'], "text"),
                       GetSQLValueString($colname_getParent, "int"),
                       GetSQLValueString($_POST['sort'], "int") ,
                       GetSQLValueString($_POST['para'], "text")
					   );
	 
  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) ;
  if(!$Result1){$logger->fatal("数据库操作失败:".$insertSQL);}
	/**
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
	}**/
	
	 if($add_privielges_exception==""){
		    $insertGoTo = "../privileges/index.php?parent=" . $colname_getParent;
		   // header(sprintf("Location: %s", $insertGoTo));
	 }
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
$getParent = mysql_query($query_getParent, $localhost) ;
if(!$getParent){$logger->fatal("数据库操作失败:".$query_getParent);}
$row_getParent = mysql_fetch_assoc($getParent);
$totalRows_getParent = mysql_num_rows($getParent);

$colname_privileges = "-1";
if (isset($_GET['parent_id'])) {
  $colname_privileges = (get_magic_quotes_gpc()) ? $_GET['parent_id'] : addslashes($_GET['parent_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_privileges = sprintf("SELECT * FROM privilege WHERE pid = %s  and is_delete=0 ORDER BY sort asc", $colname_privileges);
$privileges = mysql_query($query_privileges, $localhost) ;
if(!$privileges){$logger->fatal("数据库操作失败:".$query_privileges);}
$row_privileges = mysql_fetch_assoc($privileges);
$totalRows_privileges = mysql_num_rows($privileges);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">添加权限</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php">
<input style="float:right;" type="submit" name="Submit2" value="权限列表" />
</a>
<?php if ($totalRows_getByName > 0) { // Show if recordset not empty ?>
  <p class="phpshop123_infobox">错误：权限名称重复！</p>
  <?php } // Show if recordset not empty ?>
  
  <?php if ($add_privielges_exception!="") { // Show if recordset not empty ?>
  <p>错误<?=$add_privielges_exception?></p>
  <?php } // Show if recordset not empty ?>
  <form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <p>&nbsp;</p>
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input type="text" name="name" id="name"  value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">文件:</td>
      <td><select name="controller_action">
	  	<option value="">不设置</option>
        <?php  foreach($global_file_list_array as $file_item){?>
		<option value="<?=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?>"><?=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_item);?></option>
		<?php }?>
      </select>
        <label>
        <input name="para" type="text" id="para" />
        </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">菜单:</td>
      <td><label>
        <input name="is_menu" type="checkbox" id="is_menu" value="checkbox" />
        如果想要将这个权限设置为左侧的菜单项，请勾选此项
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">排序</td>
      <td><input name="sort" id="sort"  type="text" value="" size="32" />
      [数值越小越靠前，只能使用正整数]</td>
    </tr>
    <!--tr valign="baseline">
      <td nowrap align="right">其他条件:</td>
      <td><label>
        <textarea name="biz_rule" cols="30" rows="5"></textarea>
      </label></td>
    </tr-->
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="插入记录"></td>
    </tr>
  </table>
  <input type="hidden" name="parent_id" value="">
  <input type="hidden" name="MM_insert" value="form1">
</form>
  <?php if ($totalRows_privileges > 0) { // Show if recordset not empty ?>
  <p class="phpshop123_title"><?php echo isset($row_getParent['name'])?$row_getParent['name'].":":"";?> 权限列表</p>
   <table width="100%" border="1" class="phpshop123_list_box">
     <tr>
       <th width=" " scope="col">权限名称</th>
       <th width=" " scope="col">顺序</th>
       <th width="" scope="col">文件</th>
       <th width="" scope="col">操作</th>
     </tr>
     <?php do { ?>
      <tr>
        <td><?php echo $row_privileges['name']; ?></td>
        <td><?php echo $row_privileges['sort']; ?></td>
        <td><?php echo $row_privileges['file_name']; ?></td>
        <td><a href="remove.php?id=<?php echo $row_privileges['id']; ?>" onclick="return confirm('您确实要删除这个权限吗?');">删除</a> <a href="edit.php?id=<?php echo $row_privileges['id']; ?>">编辑</a></td>
      </tr>
      <?php } while ($row_privileges = mysql_fetch_assoc($privileges)); ?>
  </table>
    <?php } // Show if recordset not empty ?>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#form1").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
				remote:{
                    url: "_ajax_name.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'name': function(){return $("#name").val();}
                    }
				}
             }
        },
         messages: {
			name: {
  				remote:"权限已存在"
            } 
        } 
    });
});</script>
</body>
</html>