<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_role = "-1";
if (isset($_GET['id'])) {
  $colname_role = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_role = sprintf("SELECT * FROM role WHERE id = %s", $colname_role);
$role = mysql_query($query_role, $localhost) or die(mysql_error());
$row_role = mysql_fetch_assoc($role);
$totalRows_role = mysql_num_rows($role);

$colname_role_privileges = "-1";
if (isset($_GET['id'])) {
  $colname_role_privileges = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

mysql_select_db($database_localhost, $localhost);
$query_privileges = "SELECT id, name, pid FROM `privilege` WHERE pid = 0 and is_delete=0";
$privileges = mysql_query($query_privileges, $localhost) or die(mysql_error());
$privielges_array=array();
while ($row_privileges = mysql_fetch_assoc($privileges)){
	$privielges_array[]=$row_privileges;
}
$final_privileges_array=array();
foreach($privielges_array as $row_privileges){
	    $query_privileges_sql = "SELECT id, name, pid FROM `privilege` WHERE pid = ".$row_privileges['id'];
		$privileges_query = mysql_query($query_privileges_sql, $localhost) or die(mysql_error());
		$children_array=array();
		while($row_privileges_children = mysql_fetch_assoc($privileges_query)){
			 $row_privileges['children'][]=$row_privileges_children;
		}
		$final_privileges_array[]=$row_privileges;
}

if($_POST && $_POST ['updated']=='updated'){
	mysql_query("delete from role_privilege where role_id=$colname_role_privileges") or die(mysql_error());
	if(isset($_POST['privielges'])){
		foreach($_POST['privielges'] as $privielges_id){
					mysql_query("insert into role_privilege(role_id,pid)values($colname_role_privileges,$privielges_id)") or die(mysql_error());
		}
	}
}

/*mysql_select_db($database_localhost, $localhost);
$query_role_privileges = sprintf("SELECT privilige_id FROM role_privilege WHERE role_id = %s", $colname_role_privileges);
$role_privileges = mysql_query($query_role_privileges, $localhost) or die(mysql_error());
$privileges_id_array=array();
while($privilege_id=mysql_fetch_assoc($role_privileges)){
		$privileges_id_array[]=$privilege_id['privilige_id'];
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
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
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<p class="phpshop123_title"><?php echo $row_role['name']; ?> : 分配权限</p>
<form id="form1" name="form1" method="post" action="">
<input name="" value="更新权限" type="submit" />
<input type="hidden" name="updated" value="updated">
  <table width="200%" border="1" class="phpshop123_list_box">
    <?php foreach ($final_privileges_array as $row_privileges ){ ?>
      <tr>
        <td><input <?php if(in_array($row_privileges['id'],$privileges_id_array)){?>checked<?php }?> type="checkbox" name="privielges[]" value="<?php echo $row_privileges['id']; ?>" onclick="check_children(this)" />
          <?php echo $row_privileges['name']; ?>
		  <?php if(isset($row_privileges['children'])){?>
		   <div class="children_box" parent="<?php echo $row_privileges['id']?>">
		   		 <?php foreach ($row_privileges['children'] as $child ){ ?>
				 	<input <?php if(in_array($child['id'],$privileges_id_array)){?>checked<?php }?> parent="<?php echo $row_privileges['id']?>" type="checkbox" name="privielges[]" value="<?php echo $child['id']; ?>" />	<?php echo $child['name'];?>
				 <?php }?>
		   </div>
		     <?php }?>
        </td>
      </tr>
      <?php } ?>
  </table>
</form>
<p>&nbsp;</p>
</body>

</html>
<?php
mysql_free_result($role);

mysql_free_result($role_privileges);
?>
