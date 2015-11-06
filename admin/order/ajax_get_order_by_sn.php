<?php require_once('../../Connections/localhost.php'); ?>
<?php
$result="true";
$colname_order = "-1";
if (isset($_POST['sn'])) {
  $colname_order = (get_magic_quotes_gpc()) ? $_POST['sn'] : addslashes($_POST['sn']);
}
mysql_select_db($database_localhost, $localhost);
$query_order = sprintf("SELECT * FROM orders WHERE sn = '%s'", $colname_order);
$order = mysql_query($query_order, $localhost) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);
if($totalRows_order==0){
	$result="false";
}
die($result);
?>
