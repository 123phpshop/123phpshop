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
<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="order.html#update";
$support_email_question="更新订单";
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
  $updateSQL = sprintf("UPDATE orders SET sn=%s, user_id=%s, should_paid=%s, actual_paid=%s, shipping_method=%s, payment_method=%s, invoice_is_needed=%s, invoice_title=%s, invoice_message=%s, consignee_id=%s, please_delivery_at=%s, memo=%s, express_company_id=%s, express_sn=%s, consignee_name=%s, consignee_province=%s, consignee_city=%s, consignee_district=%s, consignee_address=%s, consignee_zip=%s, consignee_mobile=%s WHERE id=%s",
                       GetSQLValueString($_POST['sn'], "text"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['should_paid'], "double"),
                       GetSQLValueString($_POST['actual_paid'], "double"),
                       GetSQLValueString($_POST['shipping_method'], "int"),
                       GetSQLValueString($_POST['payment_method'], "text"),
                       GetSQLValueString($_POST['invoice_is_needed'], "int"),
                       GetSQLValueString($_POST['invoice_title'], "text"),
                       GetSQLValueString($_POST['invoice_message'], "text"),
                       GetSQLValueString($_POST['consignee_id'], "int"),
                       GetSQLValueString($_POST['please_delivery_at'], "int"),
                       GetSQLValueString($_POST['memo'], "text"),
                       GetSQLValueString($_POST['express_company_id'], "int"),
                       GetSQLValueString($_POST['express_sn'], "text"),
                       GetSQLValueString($_POST['consignee_name'], "text"),
                       GetSQLValueString($_POST['consignee_province'], "text"),
                       GetSQLValueString($_POST['consignee_city'], "text"),
                       GetSQLValueString($_POST['consignee_district'], "text"),
                       GetSQLValueString($_POST['consignee_address'], "text"),
                       GetSQLValueString($_POST['consignee_zip'], "text"),
                       GetSQLValueString($_POST['consignee_mobile'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$colname_order = "-1";
if (isset($_GET['id'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE id = %s", $colname_order);
$order = mysql_query($query_order, $localhost) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);
 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>  <span class="phpshop123_title">更新订单信息：</span>    <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
 <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
   <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">Sn:</td>
      <td><input type="text" name="sn" value="<?php echo $row_order['sn']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline" >
      <td nowrap align="right">用户:</td>
	  <?php 
	  mysql_select_db($database_localhost, $localhost);
	$query_user = "SELECT * FROM `user` WHERE is_delete=0 and id = ".$row_order['user_id'];
	$user = mysql_query($query_user, $localhost) or die(mysql_error());
	$row_user = mysql_fetch_assoc($user);
	$totalRows_user = mysql_num_rows($user);
 	  ?>
      <td><?php echo $row_user['username']; ?> <input type="text" name="user_name_filter" id="user_name_filter" value="" size="32" onchange="change_order_user()"></td>
    </tr>
	
	 <tr valign="baseline" id="user_tr" style="display:none;">
      <td nowrap align="right">选择用户:</td>
      <td id="user_td"></td>
    </tr>
     <tr valign="baseline">
      <td nowrap align="right">Should_paid:</td>
      <td><input type="text" name="should_paid" value="<?php echo $row_order['should_paid']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Actual_paid:</td>
      <td><input type="text" name="actual_paid" value="<?php echo $row_order['actual_paid']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Shipping_method:</td>
      <td><select name="shipping_method">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", $row_order['shipping_method']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", $row_order['shipping_method']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Payment_method:</td>
      <td><select name="payment_method">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", $row_order['payment_method']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", $row_order['payment_method']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
      </select>      </td>
    </tr>
    <tr valign="baseline" id="consignee_tr">
      <td nowrap align="right">收货人:</td>
      <td id="consignee_td"><table width="960" border="1" class="phpshop123_list_box">
        <tr id="consignee_td">
          <?php
			mysql_select_db($database_localhost, $localhost);
			$query_consignee = "SELECT * FROM user_consignee WHERE  is_delete=0 and user_id = ".$row_order['user_id'];
			$consignee = mysql_query($query_consignee, $localhost) or die(mysql_error());
			$row_consignee = mysql_fetch_assoc($consignee);
			$totalRows_consignee = mysql_num_rows($consignee);
 		   do { ?>
            <td scope="row"> 
              <input type="radio" name="radiobutton" value="radiobutton" />             </td>
            <td><?php echo $row_consignee['name']; ?></td>
            <td><?php echo $row_consignee['mobile']; ?></td>
            <td><?php echo $row_consignee['province']; ?></td>
            <td><?php echo $row_consignee['city']; ?></td>
            <td><?php echo $row_consignee['district']; ?></td>
            <td><?php echo $row_consignee['address']; ?></td>
            <?php } while ($row_consignee = mysql_fetch_assoc($consignee)); ?></tr>

      </table></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">商品：</td>
      <td><table width="100%" border="1" class="phpshop123_list_box">
        <tr>
          <th scope="col">商品列表</th>
          <th scope="col">价格</th>
          <th scope="col">数量</th>
          <th scope="col">小计</th>
          <th scope="col">操作</th>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>[删除][更新]</td>
          </tr>
      </table></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">可以收货的时间:</td>
      <td><select name="please_delivery_at">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1",$row_order['please_delivery_at']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
       </select>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Memo:</td>
      <td><textarea name="memo" cols="50" rows="5"><?php echo $row_order['memo']; ?></textarea>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Express_company_id:</td>
      <td><select name="express_company_id">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1",$row_order['express_company_id']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", $row_order['express_company_id']))) {echo "SELECTED";} ?>>[ 标签 ]</option>
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Express_sn:</td>
      <td><input type="text" name="express_sn" value="<?php echo $row_order['express_sn']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_name:</td>
      <td><input type="text" name="consignee_name" value="<?php echo $row_order['consignee_name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">收货省市:</td>
      <td><?php include($_SERVER['DOCUMENT_ROOT'].'/widget/area/index.php');?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_city:</td>
      <td><input type="text" name="consignee_city" value="<?php echo $row_order['consignee_city']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_district:</td>
      <td><input type="text" name="consignee_district" value="<?php echo $row_order['consignee_district']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_address:</td>
      <td><input type="text" name="consignee_address" value="<?php echo $row_order['consignee_address']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_zip:</td>
      <td><input type="text" name="consignee_zip" value="<?php echo $row_order['consignee_zip']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Consignee_mobile:</td>
      <td><input type="text" name="consignee_mobile" value="<?php echo $row_order['consignee_mobile']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice_is_needed:</td>
      <td valign="baseline">
          <input type="checkbox" name="invoice_is_needed" id="invoice_is_needed"  value="checkbox" <?php if (!(strcmp($row_order['invoice_is_needed'],"1"))) {echo "CHECKED";} ?>/>
        </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice_title:</td>
      <td><input type="text" name="invoice_title" value="<?php echo $row_order['invoice_title']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Invoice_message:</td>
      <td><textarea name="invoice_message" cols="50" rows="5"><?php echo $row_order['invoice_message']; ?></textarea>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_order['id']; ?>">
</form>
<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../../js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 $("#").validate();
});

addressInit('province', 'city', 'district', '<?php echo $row_order['consignee_province']; ?>', '<?php echo $row_order['consignee_city']; ?>', '<?php echo $row_order['consignee_district']; ?>');

function change_order_user(){
	
	var user_name=$("#user_name_filter").val();
	var url="/admin/widgets/order/_user_filter.php?name="+user_name;
	
	//	显示选择用户tr
	$("#user_tr").show();
	
	// 将结果加载在用户tr中的td中
	$("#user_td").load(url);
	
	
}


function show_consignee(user_id){
	var url="/admin/widgets/order/_consignee_filter.php?user_id="+user_id;

 	//	显示选择用户tr
	$("#consignee_tr").show();
	
	// 将结果加载在用户tr中的td中
	$("#consignee_td").load(url);
}

</script>
</body>
</html>