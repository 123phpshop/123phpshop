<?php require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);
$query_promotions = "SELECT * FROM promotion WHERE is_delete = 0 and start_date<=".date('Ymd')." and end_date>=".date('Ymd') ;
$promotions = mysql_query($query_promotions, $localhost) or die(mysql_error());
$row_promotions = mysql_fetch_assoc($promotions);
$totalRows_promotions = mysql_num_rows($promotions);
?>