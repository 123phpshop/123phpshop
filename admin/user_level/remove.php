<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_item = "-1";
if (isset($_GET['id'])) {
  $colname_item = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$query_item = sprintf("SELECT * FROM user_levels WHERE id = %s", $colname_item);
$item = mysqli_query($localhost,$query_item);
if(!$item){$logger->fatal("数据库操作失败:".$query_item);}
$row_item = mysqli_fetch_assoc($item);
$totalRows_item = mysqli_num_rows($item);

$doc_url="user_level.html#remove";
$support_email_question="删除用户等级";log_admin($support_email_question);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
</body>
</html>
<?php
mysqli_free_result($item);
?>
