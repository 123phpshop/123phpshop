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

<body class="phpshop123_title">
<span>添加订单
  <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
</span>
<form id="form1" name="form1" method="post" action="">
  <table width="960" border="1">
    <tr>
      <td width="200">用户</td>
      <td width="744"><label>
        <input name="username" type="text" id="username" />
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
      <td><input name="goods_name" type="text" id="goods_name" /></td>
    </tr>
    <tr>
      <td>商品选择</td>
      <td>&nbsp;</td>
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
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
</html>
