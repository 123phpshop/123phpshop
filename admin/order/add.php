<?php
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

<form id="form1" name="form1" method="post" action="">
  <table width="960" border="0" class="phpshop123_form_box">
    <tr>
      <td width="200">用户</td>
      <td width="744"><label>
        <input name="username" type="text" id="username"  onchange="get_user()"/>
      </label></td>
    </tr>
    <tr>
      <td>用户选择</td>
      <td id="users_td">&nbsp;</td>
    </tr>
    <tr>
      <td>收件人</td>
      <td id="consignees_td">&nbsp;</td>
    </tr>

    <tr>
      <td>商品</td>
      <td><input name="goods_name" type="text" id="goods_name" onchange="get_goods()"/></td>
    </tr>
    <tr>
      <td>商品选择</td>
      <td id="goods_td">&nbsp;</td>
    </tr>
    <tr>
      <td>支付方式</td>
      <td><label>
        <input type="radio" name="radiobutton" value="radiobutton" />
      </label>
      支付宝</td>
    </tr>
    <tr>
      <td>发票信息</td>
      <td><label>
        <input type="radio" name="radiobutton" value="radiobutton" />
      需要发票<br />
      <input type="text" name="textfield3" />
      <br />
      <input type="text" name="textfield32" />
      </label></td>
    </tr>
  </table>
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
</script>
</body>
</html>
