<?php require_once('../../Connections/localhost.php'); ?><?php
mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['recordID'];
$query_DetailRS1 = "SELECT * FROM shipping_method WHERE id = $recordID";
$DetailRS1 = mysql_query($query_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
		
<p>配送方式详细</p>
<table width="100%" border="1" align="center">
  
  <tr>
    <td>name</td>
    <td><?php echo $row_DetailRS1['name']; ?> </td>
  </tr>
  <tr>
    <td>config_file_path</td>
    <td><?php echo $row_DetailRS1['config_file_path']; ?> </td>
  </tr>
  
  <tr>
    <td>is_activated</td>
    <td><?php echo $row_DetailRS1['is_activated']; ?> </td>
  </tr>

  <tr>
    <td>is_cod</td>
    <td><?php echo $row_DetailRS1['is_cod']; ?> </td>
  </tr>
 <tr>
	<td>create_time</td>
	<td><?php echo $row_DetailRS1['create_time']; ?> </td>
</tr>
<tr>
    <td>desc</td>
    <td><?php echo $row_DetailRS1['desc']; ?> </td>
  </tr>
  
</table>

</body>
</html><?php
mysql_free_result($DetailRS1);
?>
