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
 ?><?php 
require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="promotion.html#add";
$support_email_question="添加促销活动";log_admin($support_email_question);
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
try{

	// @todo 参数验证

   $insertSQL = sprintf("INSERT INTO promotion (user_group,user_group_value,amount_lower_limit,name, start_date, end_date, promotion_limit, promotion_limit_value,promotion_type, present_products,promotion_type_val) VALUES (%s,%s,%s,%s,%s, %s, %s,%s, %s, %s, %s)",
   						GetSQLValueString($_POST['user_group_id'], "int"),
  					   GetSQLValueString(isset($_POST['user_group_value'])?implode(",",$_POST['user_group_value']):"", "text"),
  					   GetSQLValueString($_POST['amount_lower_limit'], "double"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),
                       GetSQLValueString($_POST['promotion_limit'], "int"),
					   GetSQLValueString(implode(",",$_POST['promotion_limit_value']), "text"),
                       GetSQLValueString($_POST['promotion_type'], "int"),
                       GetSQLValueString(implode(",",$_POST['present_products']), "text"),
					   GetSQLValueString($_POST['promotion_type_val'], "int"));
	 
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($insertSQL, $localhost) ;
		if(!$Result1){$logger->fatal("数据库操作失败:".$insertSQL.mysql_error());}
		$insertGoTo = "index.php";
		header(sprintf("Location: %s", $insertGoTo));
	}catch(Exception $ex){
		$logger->fatal($ex->getMessage());
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
<link href="/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet" type="text/css" />

</head>

<body>
  <span class="phpshop123_title">添加促销</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
  <a href="index.php"><input style="float:right;" type="submit" name="Submit2" value="促销列表" /></a>
<form method="post" name="form1" id="form1"  action="<?php echo $editFormAction; ?>">

  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td width="14%" align="right" nowrap>名称:</td>
      <td width="86%"><input name="name" type="text" value="" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">开始时间:</td>
      <td><input name="start_date" type="text" id="start_date" value="" size="32" maxlength="11"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">结束时间:</td>
      <td><input name="end_date" type="text" id="end_date" value="" size="32" maxlength="11"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">可参与的用户:</td>
      <td><label>
        <select name="user_group_id" id="user_group_id" onchange="show_user_group()">
          <option value="100">注册用户</option>
          <option value="200">指定用户组</option>
        </select>
      </label></td>
    </tr>
   <tr valign="baseline" style="display:none;" id="user_level_tr">
      <td nowrap align="right">用户组：</td>
      <td id="user_level_td">&nbsp;</td>
    </tr>
	
    <tr valign="baseline">
      <td nowrap align="right">促销范围:</td>
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
      <td nowrap align="right">促销类型:</td>
      <td>满
        <label>
        <input name="amount_lower_limit" type="text" id="amount_lower_limit" size="10" maxlength="10" />
        元        </label>
        <select name="promotion_type" id="promotion_type" onchange="promotion_type_filter()">
        <?php foreach($const_promotion_types as $key=>$value){ ?>
			<option value="<?php echo $key;?>"><?php echo $value;?></option>
       	<?php } ?>
      </select>
        <span id="free_shipping_bugy_label" style="display:none;color:red;">[ 点击购买此功能]</span>
        <input name="promotion_type_val" type="text" id="promotion_type_val" maxlength="32" style="display:none;"/>
         [如果是满减的话，请输入满减的金额例如：12.58；如果是满折的话，请输入折扣的百分比例如：7折，就输入70%]      </td>
    </tr>
    <tr valign="baseline" id="presents_tr">
      <td nowrap align="right">赠送产品:</td>
      <td><input type="text" name="present_goods_name"  id="present_goods_name" size="32" onchange="filter_presents()"></td>
    </tr>
	<tr valign="baseline" id="presents_sel_tr">
      <td nowrap align="right">选择产品:</td>
      <td id="presents_sel_td"></td>
    </tr>
	
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input name="add_promotion_button" type="submit" id="add_promotion_button" value="添加"></td>
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
		$("#free_shipping_bugy_label").hide();
		$("#add_promotion_button").removeAttr("disabled");
		$("#add_promotion_button").attr("enabled","true");
		return;
	}
	
	if(promotion_type=="4"){ // 如果是免运费功能的话
 		$("#free_shipping_bugy_label").show();
		$("#add_promotion_button").attr("disabled","true");
 		return;
	}
	
	$("#add_promotion_button").removeAttr("disabled");
	$("#add_promotion_button").attr("enabled","true");
	$("#presents_tr").hide();
	$("#presents_sel_tr").hide();
	$("#promotion_type_val").show();
	$("#free_shipping_bugy_label").hide();
	return;
}

function filter_presents(){
	var name=$("#present_goods_name").val();
	var url="/admin/widgets/promotion/_presents_search.php?name="+name;
	$("#presents_sel_td").load(url);
}	


</script>
<script>
$().ready(function(){
 	$("#form1").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
             },
            start_date: {
                required: true,
                minlength: 10 
            },
            end_date: {
                required: true,
                minlength: 10  
            },
 			amount_lower_limit: {
                required: true,
 				digits:true
            }
        } 
    });
});

var show_user_group=function(){
 	var user_group_id=$("#user_group_id").val();
  	if(user_group_id==200){
		$("#user_level_tr").show();
		$("#user_level_td").load("/admin/widgets/promotion/_usergroups.php");
		return;
	}
	$("#user_level_tr").hide();
	$("#user_level_td").html("");
	return;
	 
}
</script>
</body>
</html>