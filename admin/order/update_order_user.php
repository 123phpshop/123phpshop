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
 ?><?php require_once('../../Connections/localhost.php'); ?>
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
  $updateSQL = sprintf("UPDATE orders SET user_id=%s, consignee_id=%s, invoice_is_needed=%s, invoice_title=%s, invoice_message=%s, please_delivery_at=%s, consignee_name=%s, consignee_province=%s, consignee_city=%s, consignee_district=%s, consignee_address=%s, consignee_zip=%s, consignee_mobile=%s WHERE id=%s",
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['consignee_id'], "int"),
                       GetSQLValueString(isset($_POST['invoice_is_needed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['invoice_title'], "text"),
                       GetSQLValueString($_POST['invoice_message'], "text"),
                       GetSQLValueString($_POST['please_delivery_at'], "int"),
                       GetSQLValueString($_POST['consignee_name'], "text"),
                       GetSQLValueString($_POST['consignee_province'], "text"),
                       GetSQLValueString($_POST['consignee_city'], "text"),
                       GetSQLValueString($_POST['consignee_district'], "text"),
                       GetSQLValueString($_POST['consignee_address'], "text"),
                       GetSQLValueString($_POST['consignee_zip'], "text"),
                       GetSQLValueString($_POST['consignee_mobile'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) ;
  if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}

  $updateGoTo = "index.php?id=" . $row_order['id'] . "";
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_order = "-1";
if (isset($_GET['id'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE id = %s", $colname_order);
$order = mysql_query($query_order, $localhost) ;
if(!$order){$logger->fatal("数据库操作失败:".$query_order);}
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

mysql_select_db($database_localhost, $localhost);
$query_user = "SELECT id, username FROM `user` WHERE id = ".$row_order['user_id'];
$user = mysql_query($query_user, $localhost) ;
if(!$user){$logger->fatal("数据库操作失败:".$query_user);}
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

$doc_url="order.html#update_user";
$support_email_question="更新订单用户";log_admin($support_email_question);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>更新订单用户</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">更新订单用户<a href="index.php">
  <input style="float:right;" type="submit" name="Submit2" value="订单列表" />
</a></p>
 <form action="<?php echo $editFormAction; ?>" method="post" name="form1" class="phpshop123_form_box">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">用户:</td>
      <td><input name="username" type="text" id="username" value="<?php echo $row_user['username'];?>" oninput="get_user()"/>
    </tr>
    <tr class="phpshop123_form_box">
      <td>选择用户</td>
      <td id="users_td"><?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/order/_user_load.php');?></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right">是否需要发票:</td>
      <td><input type="checkbox" name="invoice_is_needed" <?php if($row_order['invoice_is_needed']==1){ ?>checked <?php } ?>value="<?php echo $row_order['invoice_is_needed']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">发票抬头:</td>
      <td><input type="text" name="invoice_title" value="<?php echo $row_order['invoice_title']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">发票注释:</td>
      <td><input type="text" name="invoice_message" value="<?php echo $row_order['invoice_message']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">收货时间:</td>
      <td><label>
        <select name="please_delivery_at" id="please_delivery_at">
			<?php foreach($please_deliver_at as $key=>$value){ ?>
          	<option value="<?php echo $key;?>" <?php if($key==$row_order['please_delivery_at']){ ?>selected <?php } ?> ><?php echo $value;?></option>
		  <?php } ?>
        </select>
      </label></td>
    </tr>
	
	<tr>
      <td>选择收件人</td>
      <td id="consignees_td"><?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/order/_consignee_load.php');?></td>
    </tr>
     <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="consignee_id" value="<?php echo $row_order['consignee_id']; ?>" size="32">
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_order['id']; ?>">
</form>
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
<script>
function get_user(){
	var name_filter=$("#username").val();
	var url="/admin/widgets/order/_user_filter.php?name="+name_filter;
	$("#users_td").load(url);	
}

function get_consignee(user_id){
 	var consignee_filter_url="/admin/widgets/order/_consignee_filter.php?user_id="+user_id;
	$("#consignees_td").load(consignee_filter_url);
 }

function get_goods(){
	var name_filter=$("#goods_name").val();
	var url="/admin/widget/_goods_filter.php?name="+name_filter;
	$("#goods_td").load(url);
}

 
function set_consignee(that){
	var consignee_id=$(that).val();
 	var consignee_name=$(that).attr("consignee_name");
	var consignee_province=$(that).attr("consignee_province");
	var consignee_city=$(that).attr("consignee_city");
	var consignee_district=$(that).attr("consignee_district");
	var consignee_address=$(that).attr("consignee_address");
	var consignee_zip=$(that).attr("consignee_zip");
	var consignee_mobile=$(that).attr("consignee_mobile");
	
	$("#consignee_id").val(consignee_id);
 	$("#consignee_name").val(consignee_name);
	$("#consignee_province").val(consignee_province);
	$("#consignee_city").val(consignee_city);
	$("#consignee_district").val(consignee_district);
	$("#consignee_address").val(consignee_address);
	$("#consignee_zip").val(consignee_zip);
	$("#consignee_mobile").val(consignee_mobile);
 }
 </script>
</body>
</html>
<?php
mysql_free_result($user);
?>