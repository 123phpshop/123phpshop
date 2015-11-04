<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body >
<span class="phpshop123_title">订单合并</span>
  <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>

<form id="order_merge_form" name="order_merge_form" method="post" action="">
  <p>&nbsp;</p>
  <table width="967" border="0" class="phpshop123_form_box">
    <tr>
      <th width="233" scope="row">将
        <label>订单:</label></th>
      <td width="718"><input name="child_order_sn" type="text" id="child_order_sn" />
      [这里填写订单序列号]</td>
    </tr>
    <tr>
      <th scope="row">合并进入:</th>
      <td><input name="main_order_sn" type="text" id="main_order_sn" />
[这里填写订单序列号]</td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td><label>
        <input type="submit" name="Submit" value="提交" />
      </label></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
