<?php

$query_theme = "SELECT * FROM theme WHERE is_delete = 0";
$theme = mysqli_query($localhost,$query_theme);
//if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
$row_theme = mysqli_fetch_assoc($theme);
$totalRows_theme = mysqli_num_rows($theme);
$template_path=$www_root.'/theme/'.$row_theme['folder_name']."/";
$template_url='/theme/'.$row_theme['folder_name']."/";
?>