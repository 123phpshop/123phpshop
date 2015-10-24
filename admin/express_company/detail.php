<?php require_once('../../Connections/localhost.php'); ?><?php
mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['recordID'];
$query_DetailRS1 = "SELECT * FROM express_company WHERE id = $recordID";
$DetailRS1 = mysql_query($query_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
		
<p class="phpshop123_title">快递公司详细</p>
<table width="100%" border="0" align="center" class="phpshop123_form_box">
  
  <tr>
    <td>id</td>
    <td><?php echo $row_DetailRS1['id']; ?> </td>
  </tr>
  <tr>
    <td>code</td>
    <td><?php echo $row_DetailRS1['code']; ?> </td>
  </tr>
  <tr>
    <td>name</td>
    <td><?php echo $row_DetailRS1['name']; ?> </td>
  </tr>
  <tr>
    <td>disabled</td>
    <td><?php echo $row_DetailRS1['disabled']; ?> </td>
  </tr>
  <tr>
    <td>ordernum</td>
    <td><?php echo $row_DetailRS1['ordernum']; ?> </td>
  </tr>
  <tr>
    <td>website</td>
    <td><?php echo $row_DetailRS1['website']; ?> </td>
  </tr>
  <tr>
    <td>request_url</td>
    <td><?php echo $row_DetailRS1['request_url']; ?> </td>
  </tr>
  
  
</table>

</body>
</html><?php
mysql_free_result($DetailRS1);
?>
