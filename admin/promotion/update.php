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
  $updateSQL = sprintf("UPDATE promotion SET name=%s, start_date=%s, end_date=%s, promotion_limit=%s, amount_lower_limit=%s, amount_uper_limit=%s, promotion_type=%s, present_products=%s, create_time=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),
                       GetSQLValueString($_POST['promotion_limit'], "int"),
                       GetSQLValueString($_POST['amount_lower_limit'], "double"),
                       GetSQLValueString($_POST['amount_uper_limit'], "double"),
                       GetSQLValueString($_POST['promotion_type'], "int"),
                       GetSQLValueString($_POST['present_products'], "text"),
                       GetSQLValueString($_POST['create_time'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_promotion = "-1";
if (isset($_GET['id'])) {
  $colname_promotion = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_promotion = sprintf("SELECT * FROM promotion WHERE id = %s", $colname_promotion);
$promotion = mysql_query($query_promotion, $localhost) or die(mysql_error());
$row_promotion = mysql_fetch_assoc($promotion);
$totalRows_promotion = mysql_num_rows($promotion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
<link href="/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>
<body>
<span class="phpshop123_title">更新促销</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="<?php echo $row_promotion['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Start_date:</td>
      <td><input type="text" name="start_date" id="start_date"  value="<?php echo $row_promotion['start_date']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">End_date:</td>
      <td><input type="text" name="end_date" id="end_date"  value="<?php echo $row_promotion['end_date']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Promotion_limit:</td>
      <td><label>
        <select name="promotion_limit"  id="promotion_limit">
		 <?php foreach($const_promotion_limit as $key=>$value){ ?>
		<option value="<?php echo $key;?>" <?php if (!(strcmp("value", $row_promotion['promotion_limit']))) {echo "selected=\"selected\"";} ?>><?php echo $value;?></option>
       	<?php } ?>
        </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Amount_lower_limit:</td>
      <td><input type="text" name="amount_lower_limit" value="<?php echo $row_promotion['amount_lower_limit']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Amount_uper_limit:</td>
      <td><input type="text" name="amount_uper_limit" value="<?php echo $row_promotion['amount_uper_limit']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Promotion_type:</td>
      <td><select name="promotion_type"  id="promotion_type">
	  	<?php foreach($const_promotion_types as $key=>$value){ ?>
		<option value="<?php echo $key;?>" <?php if (!(strcmp("value", $row_promotion['promotion_type']))) {echo "selected=\"selected\"";} ?>><?php echo $value;?></option>
       	<?php } ?>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Present_products:</td>
      <td><input type="text" name="present_products" value="<?php echo $row_promotion['present_products']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Create_time:</td>
      <td><input type="text" name="create_time" value="<?php echo $row_promotion['create_time']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_promotion['id']; ?>">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script>
$().ready(function(){
  	$("#start_date").datepicker({ dateFormat: 'yy-mm-dd' }); // 初始化日历
	$("#end_date").datepicker({ dateFormat: 'yy-mm-dd' }); // 初始化日历
});
</script>
</body>
</html>
<?php
mysql_free_result($promotion);
?>
