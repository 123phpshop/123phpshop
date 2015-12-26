<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_favorite = "-1";
if (isset($_GET['user_id'])) {
  $colname_favorite = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_favorite = sprintf("SELECT * FROM user_favorite WHERE user_id = %s", $colname_favorite);
$favorite = mysql_query($query_favorite, $localhost) or die(mysql_error());
$row_favorite = mysql_fetch_assoc($favorite);
$totalRows_favorite = mysql_num_rows($favorite);
?>