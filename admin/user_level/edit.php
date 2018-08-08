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

$doc_url="user_level.html#update";
$support_email_question="编辑用户等级";log_admin($support_email_question);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

	// 这里对字段进行验证
	$validation->set_rules ( 'name', '用户等级名称', 'required|min_length[2]|is_chinese|max_length[18]' );
	$validation->set_rules ( 'min_consumption_amount', '最低消费总额', 'required|is_float' );
 	if (! $validation->run ()) {
 		$error = $validation->error_string ( '', '' );
		$logger->fatal("用户在更新用户等级的时候未通过验证：".$error);
  	}else{
 	  $updateSQL = sprintf("UPDATE user_levels SET name=%s, min_consumption_amount=%s WHERE id=%s",
						   GetSQLValueString($_POST['name'], "text"),
						   GetSQLValueString($_POST['min_consumption_amount'], "double"),
						   GetSQLValueString($_POST['id'], "int"));
	
	  mysql_select_db($database_localhost, $localhost);
	  $Result1 = mysql_query($updateSQL, $localhost);
	  if(!$Result1){
		$logger->fatal("更新用户登记操作失败:".$updateSQL);
	  }
	  $updateGoTo = "index.php";
	  header(sprintf("Location: %s", $updateGoTo));
   }
}

$colname_item = "-1";
if (isset($_GET['id'])) {
  $colname_item = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_item = sprintf("SELECT * FROM user_levels WHERE id = %s", $colname_item);
$item = mysql_query($query_item, $localhost) ;
if(!$item){$logger->fatal("数据库操作失败:".$query_item);}
$row_item = mysql_fetch_assoc($item);
$totalRows_item = mysql_num_rows($item);
if($totalRows_item==0){
		 $remove_succeed_url="index.php";
		  header("Location: " . $remove_succeed_url );
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">用户等级更新</p>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php"); ?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
        <tr valign="baseline">
          <td width="11%" align="right" nowrap>等级名称:</td>
          <td width="89%"><input type="text" name="name" value="<?php echo $row_item['name']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">最低消费金额:</td>
          <td><input type="text" name="min_consumption_amount" value="<?php echo $row_item['min_consumption_amount']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="更新记录"></td>
        </tr>
  </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="id" value="<?php echo $row_item['id']; ?>">
</form>
    </body>
</html>
<?php
mysql_free_result($item);
?>
