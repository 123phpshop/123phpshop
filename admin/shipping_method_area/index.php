<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_shipping_method = "-1";
if (isset($_GET['shipping_method_id'])) {
  $colname_shipping_method = (get_magic_quotes_gpc()) ? $_GET['shipping_method_id'] : addslashes($_GET['shipping_method_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_shipping_method = sprintf("SELECT id, name FROM shipping_method WHERE id = %s", $colname_shipping_method);
$shipping_method = mysql_query($query_shipping_method, $localhost) or die(mysql_error());
$row_shipping_method = mysql_fetch_assoc($shipping_method);
$totalRows_shipping_method = mysql_num_rows($shipping_method);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<p><?php echo $row_shipping_method['name']; ?>：配送区域列表 </p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($shipping_method);
?>
