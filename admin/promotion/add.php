<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
?>
<?php 
require_once('../../Connections/localhost.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO promotion (name, start_date, end_date, promotion_limit, promotion_type, present_products) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),
                       GetSQLValueString($_POST['promotion_limit'], "int"),
                       GetSQLValueString($_POST['promotion_type'], "int"),
                       GetSQLValueString($_POST['present_products'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "index.php";
   header(sprintf("Location: %s", $insertGoTo));
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
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <span class="phpshop123_title">添加促销</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td width="14%" align="right" nowrap>Name:</td>
      <td width="86%"><input type="text" name="name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Start_date:</td>
      <td><input type="text" name="start_date" id="start_date" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">End_date:</td>
      <td><input type="text" name="end_date" id="end_date" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Promotion_limit:</td>
      <td><select name="promotion_limit" id="promotion_limit" onchange="show_limit_filter()">
        <?php foreach($const_promotion_limit as $key=>$value){ ?>
		<option value="<?php echo $key;?>"><?php echo $value;?></option>
       	<?php } ?>
      </select>
         <input name="name_filter" type="text" id="name_filter" style="display:none;" onchange="do_filter()"/>         </td>
    </tr>
    <tr valign="baseline" id="filter_results_row" style="display:none;">
      <td nowrap align="right">参与对象：</td>
      <td id="filter_results_td">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Promotion_type:</td>
      <td><select name="promotion_type" id="promotion_type" ">
         <?php foreach($const_promotion_types as $key=>$value){ ?>
		<option value="<?php echo $key;?>"><?php echo $value;?></option>
       	<?php } ?>
      </select>      </td>
    </tr>
    <tr valign="baseline" id="present_products_row">
      <td nowrap align="right">Present_products:</td>
      <td><input type="text" name="present_products" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="插入记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
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
</script>
</body>
</html>
