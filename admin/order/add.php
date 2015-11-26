<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/order.php'); ?>

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
	require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/order.php');
	$sn=gen_order_sn();
  $insertSQL = sprintf("INSERT INTO orders (sn,user_id,consignee_name,consignee_province,consignee_city,consignee_district,consignee_address,consignee_zip,consignee_mobile,invoice_is_needed, invoice_title, invoice_message,please_delivery_at,payment_method) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s, %s, %s, %s)",
  						GetSQLValueString($sn, "text"),
  						GetSQLValueString($_POST['user_id'], "int"),
						GetSQLValueString($_POST['consignee_name'], "text"),
						GetSQLValueString($_POST['consignee_province'], "text"),
						GetSQLValueString($_POST['consignee_city'], "text"),
						GetSQLValueString($_POST['consignee_district'], "text"),
						GetSQLValueString($_POST['consignee_address'], "text"),
						GetSQLValueString($_POST['consignee_zip'], "text"),
						GetSQLValueString($_POST['consignee_mobile'], "text"),
                       GetSQLValueString(isset($_POST['invoice_is_needed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['invoice_title'], "text"),
                       GetSQLValueString($_POST['invoice_message'], "text"),
                       GetSQLValueString($_POST['please_delivery_at'], "int"),
                       GetSQLValueString(100, "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
	
	$new_order_id=mysql_insert_id();
	phpshop123_log_order_new($new_order_id);
   $insertGoTo = "detail.php?recordID=".$new_order_id;
  header(sprintf("Location: %s", $insertGoTo));
}

$doc_url="ad.html#list";
$support_email_question="添加订单";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加订单</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body >
<span class="phpshop123_title">添加订单
</span>  <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>

<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="960" border="0" class="phpshop123_form_box">
    <tr>
      <td width="155">用户</td>
      <td width="1476"><label>
        <input name="username" type="text" id="username"  oninput="get_user()" placeholder="请输入用户名的部分或全称"/>
      </label></td>
    </tr>
    <tr>
      <td>选择用户</td>
      <td id="users_td">&nbsp;</td>
    </tr>
    <tr>
      <td>选择收件人</td>
      <td id="consignees_td">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>支付方式</td>
      <td><label>
        <input name="payment_method" type="radio" value="100" checked="checked" />
      支付宝</label></td>
    </tr>
    <tr>
      <td>收货时间:</td>
      <td><label>
        <select name="please_delivery_at" id="please_delivery_at">
			<?php foreach($please_deliver_at as $key=>$value){ ?>
          <option value="<?php echo $key;?>"><?php echo $value;?></option>
		  <?php } ?>
         </select>
      </label></td>
    </tr>
    <tr>
      <td>发票信息</td>
      <td><input type="checkbox" name="invoice_is_needed" id="invoice_is_needed" value="1" />
需要发票</td>
    </tr>
    <tr>
      <td>发票抬头</td>
      <td><input type="text" name="invoice_title" id="invoice_title2" /></td>
    </tr>
    <tr>
      <td>发票信息</td>
      <td><textarea name="invoice_message" id="invoice_title"></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td> 
          <input type="submit" name="Submit" value="提交" />        </td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
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
 	var consignee_name=$(that).attr("consignee_name");
	var consignee_province=$(that).attr("consignee_province");
	var consignee_city=$(that).attr("consignee_city");
	var consignee_district=$(that).attr("consignee_district");
	var consignee_address=$(that).attr("consignee_address");
	var consignee_zip=$(that).attr("consignee_zip");
	var consignee_mobile=$(that).attr("consignee_mobile");
	
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
