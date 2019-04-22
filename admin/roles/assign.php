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
$doc_url="role.html#assign";
$support_email_question="为角色分配权限";log_admin($support_email_question);
$colname_role = "-1";
$privileges_id_array=array();

if (isset($_GET['id'])) {
  $colname_role = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

if(isset($_POST['123phpshop_op']) &&  $_POST ['123phpshop_op']=='update_privileges'){
	
 	if(!isset($_POST['privileges'])){
		$privileges="";
	}else{
		$privileges=implode(",",$_POST['privileges']);
	}
 	$sql="update role set privileges='".$privileges."' where id=".$colname_role;
	$Result1=mysqli_query($localhost,$sql) ;
	if(!$Result1){$logger->fatal("数据库操作失败:".$sql);}
 }
 

$query_role = sprintf("SELECT * FROM role WHERE id = %s", $colname_role);
$role = mysqli_query($localhost,$query_role);
if(!$role){$logger->fatal("数据库操作失败:".$query_role);}
$row_role = mysqli_fetch_assoc($role);
$totalRows_role = mysql_num_rows($role);
if($totalRows_role>0){
	$privileges_id_array=explode(",",$row_role['privileges']);
}
$colname_role_privileges = "-1";
if (isset($_GET['id'])) {
  $colname_role_privileges = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}


$query_privileges = "SELECT id, name, pid FROM `privilege` WHERE pid = 0 and is_delete=0";
$privileges = mysqli_query($localhost,$query_privileges);
if(!$privileges){$logger->fatal("数据库操作失败:".$query_privileges);}
$privileges_array=array();
while ($row_privileges = mysqli_fetch_assoc($privileges)){
	$privileges_array[]=$row_privileges;
}
$final_privileges_array=array();
foreach($privileges_array as $row_privileges){
	    $query_privileges_sql = "SELECT id, name, pid FROM `privilege` WHERE pid = ".$row_privileges['id'];
		$privileges_query = mysqli_query($localhost,$query_privileges_sql);
		if(!$privileges_query){$logger->fatal("数据库操作失败:".$query_privileges_sql);}
		$children_array=array();
		while($row_privileges_children = mysqli_fetch_assoc($privileges_query)){
			 $row_privileges['children'][]=$row_privileges_children;
		}
		$final_privileges_array[]=$row_privileges;
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common.css" rel="stylesheet" type="text/css" />

<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<span class="phpshop123_title"><?php echo $row_role['name']; ?> : 分配权限</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php">
  <input style="float:right;" type="button" name="Submit2" value="角色列表" />
  </a>
  
<form id="form1" name="form1" method="post" action="">
<input name="" value="更新权限" type="submit" />
<input type="hidden" name="123phpshop_op" value="update_privileges">
  
  <table width="200%" border="1" class="phpshop123_list_box">
    <?php foreach ($final_privileges_array as $row_privileges ){ ?>
      <tr>
        <td><input <?php if(in_array($row_privileges['id'],$privileges_id_array)){?>checked<?php }?> type="checkbox" name="privileges[]" value="<?php echo $row_privileges['id']; ?>" onclick="check_children(this)" />
          <?php echo $row_privileges['name']; ?>
		  <?php if(isset($row_privileges['children'])){?>
		   <div class="children_box" parent="<?php echo $row_privileges['id']?>">
		   		 <?php foreach ($row_privileges['children'] as $child ){ ?>
				 	&nbsp;&nbsp;&nbsp;&nbsp;<input <?php if(in_array($child['id'],$privileges_id_array)){?>checked<?php }?> parent="<?php echo $row_privileges['id']?>" type="checkbox" name="privileges[]" value="<?php echo $child['id']; ?>" />	<?php echo $child['name'];?><br>
				 <?php }?>
		   </div>
		     <?php }?>
        </td>
      </tr>
      <?php } ?>
  </table>
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript">

$(document).ready(function(){
	if($("input[value=1]").attr("checked")=="checked"){
		$("input[type=checkbox][value!=1]").attr("checked","checked");
		$("input[type=checkbox][value!=1]").attr("disabled","123");
	}
});

function check_children(item){
	
	var ivalue=$(item).val();
	var parent_status=$(item).attr("checked");
	var parent_id=ivalue;
	
	if(ivalue==1 && parent_status=="checked"){
			$("input[type=checkbox][value!=1]").attr("checked","checked");
			$("input[type=checkbox][value!=1]").attr("disabled","123");
			return ;
	}
	
	if(ivalue==1 && parent_status==undefined){
			$("input[type=checkbox][value!=1]").removeAttr("checked");
			$("input[type=checkbox][value!=1]").removeAttr("disabled");
			return ;
	}
	
	 if(parent_status!="checked"){
		$("input[parent="+parent_id+"]").removeAttr("checked");
		return true;
	 }else{
		$("input[parent="+parent_id+"]").attr("checked","checked");
		return false;
	 }
}
</script>
</body>
</html>