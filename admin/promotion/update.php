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
$doc_url="ad.html#list";
$support_email_question="更新促销活动";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE promotion SET name=%s, start_date=%s, end_date=%s, promotion_limit=%s, amount_lower_limit=%s, amount_uper_limit=%s, promotion_type=%s, present_products=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),
                       GetSQLValueString($_POST['promotion_limit'], "int"),
                       GetSQLValueString($_POST['amount_lower_limit'], "double"),
                       GetSQLValueString($_POST['amount_uper_limit'], "double"),
                       GetSQLValueString($_POST['promotion_type'], "int"),
                       GetSQLValueString($_POST['present_products'], "text"),
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

function load_promotion_limit_value(){
	
}
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
      <td width="9%" align="right" nowrap>Name:</td>
      <td width="91%"><input type="text" name="name" value="<?php echo $row_promotion['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">开始日期:</td>
      <td><input type="text" name="start_date" id="start_date"  value="<?php echo $row_promotion['start_date']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">End_date:</td>
      <td><input type="text" name="end_date" id="end_date"  value="<?php echo $row_promotion['end_date']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Promotion_limit:</td>
      <td> 
        <select name="promotion_limit"  id="promotion_limit" onchange="show_limit_filter()">
		 <?php foreach($const_promotion_limit as $key=>$value){ ?>
		<option value="<?php echo $key;?>" <?php if (!(strcmp($key, $row_promotion['promotion_limit']))) {echo "selected=\"selected\"";} ?>><?php echo $value;?></option>
       	<?php } ?>
         <input name="name_filter" type="text" id="name_filter" style="display:none;" onchange="do_filter()"/>         </td>
         </td>
    </tr>
	<?php if($row_promotion['promotion_limit']>1){ ?>
    <tr valign="baseline" id="filter_results_row">
      <td nowrap align="right">参与对象：</td>
      <td id="filter_results_td"><?php
	  switch($row_promotion['promotion_limit']){
	  		case 2:
				$widget_file="_catalog_load.php";
			break;
			case 3:
				$widget_file="_brand_load.php";
			break;
			case 4:
				$widget_file="_goods_load.php";
			break;
	  }
	  include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/promotion/".$widget_file);
	  ?></td>
    </tr>
	<?php } ?>
      <tr valign="baseline">
      <td align="right">Promotion_type:</td>
      <td>满 <input name="amount_lower_limit" maxlength="10" id="amount_lower_limit"  type="text" value="<?php echo $row_promotion['amount_lower_limit'];?>"/> 元 <select name="promotion_type"  id="promotion_type" onchange="promotion_type_filter()">
	  	<?php foreach($const_promotion_types as $key=>$value){ ?>
		<option value="<?php echo $key;?>" <?php if (!(strcmp($key, $row_promotion['promotion_type']))) {echo "selected=\"selected\"";} ?>><?php echo $value;?></option>
       	<?php } ?>
        </select>
		<?php if($row_promotion['promotion_type']>1){ ?>
		 <input name="promotion_type_val" type="text" id="promotion_type_val" maxlength="10" value="<?php echo $row_promotion['promotion_type_val']; ?>"/>[如果是满减的话，请输入满减的金额例如：12.58；如果是满折的话，请输入满折的百分比，例如：70，就是输入70%]
		<?php } ?>
		</td>
    </tr>
      <tr valign="baseline" id="presents_tr" <?php if($row_promotion['promotion_type']>1){ ?>style="display:none;"<?php } ?>>
      <td nowrap align="right">选择赠品:</td>
      <td><input type="text" name="present_products" size="32"></td>
    </tr>
	 <tr valign="baseline" id="presents_sel_tr" <?php if($row_promotion['promotion_type']>1){ ?>style="display:none;"<?php } ?>>
      <td nowrap align="right">赠送商品:</td>
      <td><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/promotion/_presents_load.php");?></td>
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
function show_limit_filter(){
	console.log("asdfasd");
}
</script>
</body>
</html>
<?php
mysql_free_result($promotion);
?>
