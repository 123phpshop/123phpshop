<?php require_once('../../Connections/localhost.php'); ?><?php
$colname_DetailRS1 = "-1";
if (isset($_GET['pid'])) {
  $colname_DetailRS1 = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['recordID'];
$query_DetailRS1 = sprintf("SELECT * FROM area  WHERE id = $recordID");
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
		
<p>区域详细:</p>
<p>&nbsp;</p>
<table border="1" align="center">
  
  <tr>
    <td>id</td>
    <td><?php echo $row_DetailRS1['id']; ?> </td>
  </tr>
  <tr>
    <td>name</td>
    <td><?php echo $row_DetailRS1['name']; ?> </td>
  </tr>
  <tr>
    <td>pid</td>
    <td><?php echo $row_DetailRS1['pid']; ?> </td>
  </tr>
  <tr>
    <td>level_depth</td>
    <td><?php echo $row_DetailRS1['level_depth']; ?> </td>
  </tr>
  <tr>
    <td>level_path</td>
    <td><?php echo $row_DetailRS1['level_path']; ?> </td>
  </tr>
  <tr>
    <td>child_num</td>
    <td><?php echo $row_DetailRS1['child_num']; ?> </td>
  </tr>
  
  
</table>

</body>
</html><?php
mysql_free_result($DetailRS1);
?>
