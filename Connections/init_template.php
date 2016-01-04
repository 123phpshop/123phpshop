<?php
mysql_select_db($database_localhost, $localhost);
$query_theme = "SELECT * FROM theme WHERE is_delete = 0";
$theme = mysql_query($query_theme, $localhost) or die(mysql_error());
$row_theme = mysql_fetch_assoc($theme);
$totalRows_theme = mysql_num_rows($theme);
$template_path=$www_root.'/theme/'.$row_theme['folder_name']."/";
$template_url='/theme/'.$row_theme['folder_name']."/";
?>