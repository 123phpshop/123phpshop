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

$doc_url="user_level.html#add";
$support_email_question="添加用户等级";log_admin($support_email_question);
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	// 这里对字段进行验证
	$validation->set_rules ( 'name', '用户等级名称', 'required|min_length[2]|is_chinese|max_length[18]' );
	$validation->set_rules ( 'min_consumption_amount', '最低消费总额', 'required|is_natural_no_zero' );
 	if (! $validation->run ()) {
	
		$error = $validation->error_string ( '', '' );
		$logger->fatal("用户在添加用户等级的时候未通过验证：".$error);
 		
 	}else{
 
		  $insertSQL = sprintf("INSERT INTO user_levels (name, min_consumption_amount) VALUES (%s, %s)",
							   GetSQLValueString($_POST['name'], "text"),
							   GetSQLValueString($_POST['min_consumption_amount'], "double"));
		
		  mysql_select_db($database_localhost, $localhost);
		  $Result1 = mysql_query($insertSQL, $localhost);
		  if(!$Result1){
			$logger->fatal("添加用户登记操作失败:".$insertSQL);
		  }
		  
		  $remove_succeed_url="index.php";
		  header("Location: " . $remove_succeed_url );
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
<span class="phpshop123_title">添加用户等级 </span>
 <a href="index.php">
  <input style="float:right;" type="submit" name="Submit2" value="等级列表" />
</a>
</p>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php"); ?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input type="text" name="name" value="<?php echo_post('name');?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">最低消费金额:</td>
      <td><input type="text" name="min_consumption_amount" value="<?php echo_post('min_consumption_amount');?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="插入记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
