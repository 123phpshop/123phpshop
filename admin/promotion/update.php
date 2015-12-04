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
$doc_url="promotion.html#update";
$support_email_question="更新促销活动";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE promotion SET name=%s, start_date=%s, end_date=%s, promotion_limit=%s, amount_lower_limit=%s, promotion_limit_value=%s, promotion_type=%s, promotion_type_val=%s,present_products=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),
                       GetSQLValueString($_POST['promotion_limit'], "int"),
                       GetSQLValueString($_POST['amount_lower_limit'], "double"),
                       GetSQLValueString(implode(",",$_POST['promotion_limit_value']), "text"),
                       GetSQLValueString($_POST['promotion_type'], "int"),
					   GetSQLValueString($_POST['promotion_type_val'], "int"),
                       GetSQLValueString(implode(",",$_POST['present_products']), "text"),
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
      <td width="9%" align="right" nowrap>促销名称:</td>
      <td width="91%"><input type="text" name="name" value="<?php echo $row_promotion['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">开始日期:</td>
      <td><input type="text" name="start_date" id="start_date"  value="<?php echo $row_promotion['start_date']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">结束日期:</td>
      <td><input type="text" name="end_date" id="end_date"  value="<?php echo $row_promotion['end_date']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">促销范围:</td>
      <td> 
        <select name="promotion_limit"  id="promotion_limit" onchange="show_limit_filter()">
		 <?php foreach($const_promotion_limit as $key=>$value){ ?>
		<option value="<?php echo $key;?>" <?php if (!(strcmp($key, $row_promotion['promotion_limit']))) {echo "selected=\"selected\"";} ?>><?php echo $value;?></option>
       	<?php } ?>
           <input name="name_filter" style="margin-left:7px;<?php if($row_promotion['promotion_limit']==1){?>display:none;<?php } ?>" type="text" id="name_filter"  oninput="do_filter()"/>         
 		  </td>
         </td>
    </tr>
     <tr valign="baseline" id="filter_results_row" <?php if($row_promotion['promotion_limit']==1){ ?>style="display:none;"<?php } ?>>
      <td nowrap align="right">参与对象：</td>
      <td id="filter_results_td"><?php
	  if($row_promotion['promotion_limit']>1){ 
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
	  }
	  ?></td>
    </tr>
       <tr valign="baseline"> 
      <td align="right">促销类型:</td> 
      <td>满 <input name="amount_lower_limit" maxlength="10" id="amount_lower_limit"  type="text" value="<?php echo $row_promotion['amount_lower_limit'];?>"/> 元 <select name="promotion_type"  id="promotion_type" onchange="promotion_type_filter()">
	  	<?php foreach($const_promotion_types as $key=>$value){ ?>
		<option value="<?php echo $key;?>" <?php if (!(strcmp($key, $row_promotion['promotion_type']))) {echo "selected=\"selected\"";} ?>><?php echo $value;?></option>
       	<?php } ?>
        </select>
		
		 <input name="promotion_type_val" type="text" id="promotion_type_val" maxlength="10" value="<?php echo $row_promotion['promotion_type_val']; ?>" <?php if($row_promotion['promotion_type']==1){ ?>style="display:none;"<?php } ?>/>[如果是满减的话，请输入满减的金额例如：12.58；如果是满折的话，请输入满折的百分比，例如：70，就是输入70%]
		
		</td>
    </tr>
      <tr valign="baseline" id="presents_tr" <?php if($row_promotion['promotion_type']>1){ ?>style="display:none;"<?php } ?>>
      <td nowrap align="right">选择赠品:</td>
      <td><input type="text" name="present_goods_name"  id="present_goods_name" size="32" oninput="filter_presents()"></td>
    </tr>
	 <tr valign="baseline" id="presents_sel_tr" <?php if($row_promotion['promotion_type']>1){ ?>style="display:none;"<?php } ?>>
      <td nowrap align="right">赠送商品:</td>
      <td id="presents_sel_td"><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/promotion/_presents_load.php");?></td>
    </tr>
     <tr valign="baseline">
      <td nowrap align="right" >&nbsp;</td>
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
	 var promotion_limit_id=$("#promotion_limit").val();
	switch(promotion_limit_id){
		case "1":
			$("#name_filter").hide();
			$("#filter_results_row").hide();
			break;
 		
		default:
  			$("#name_filter").show();
			$("#filter_results_row").show();
			$("#name_filter").val("");
			$("#filter_results_td").html("");
    	} 
}

 function do_filter(){
  	var promotion_limit_id=$("#promotion_limit").val();
 	var name=$("#name_filter").val();
	
	switch(promotion_limit_id){
		case "2": 	// 分类
			var url='/admin/widgets/promotion/_catalog_search.php?name='+name;
 		break;
		case "3":	// 品牌
			var url='/admin/widgets/promotion/_brand_search.php?name='+name;
 		break;
		case "4":	// 商品
			var url='/admin/widgets/promotion/_goods_search.php?name='+name;
 		break;
	}
	
	$("#filter_results_td").load(url); 
}

function promotion_type_filter(){
 	var promotion_type=$("#promotion_type").val();
  	if(promotion_type=="1"){
 		$("#presents_tr").show();
		$("#presents_sel_tr").show();
		$("#promotion_type_val").hide();
		return;
	}
	$("#presents_tr").hide();
	$("#presents_sel_tr").hide();
	$("#promotion_type_val").show();
	return;
}

function filter_presents(){
 	var name=$("#present_goods_name").val();
 	var url="/admin/widgets/promotion/_presents_search.php?name="+name;
	$("#presents_sel_td").load(url);
}	
</script>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
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
