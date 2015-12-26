<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_view_history = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_view_history = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_view_history = sprintf("SELECT * FROM user_view_history WHERE user_id = %s", $colname_view_history);
$view_history = mysql_query($query_view_history, $localhost) or die(mysql_error());
$row_view_history = mysql_fetch_assoc($view_history);
$totalRows_view_history = mysql_num_rows($view_history);
?>
<?php
mysql_free_result($view_history);
?>