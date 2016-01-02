<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_history = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_history = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_history = sprintf("SELECT * FROM user_view_history WHERE user_id = %s", $colname_history);
$history = mysql_query($query_history, $localhost) or die(mysql_error());
$row_history = mysql_fetch_assoc($history);
$totalRows_history = mysql_num_rows($history);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>浏览历史</title>
</head>

<body>
<p>浏览历史</p>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($history);
?>
