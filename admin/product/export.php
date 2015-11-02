<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once('../../Connections/lib/csv.php'); ?>
<?php 

if ((isset($_POST["phpshop_op"])) && ($_POST["phpshop_op"] == "export_goods_form")) {
	export_goods();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body class="phpshop123_title">
<p>导出商品</p>
<form id="export_goods_form" name="export_goods_form" method="post" action="">
  <input type="submit" name="Submit" value="导出" />
  <input type="hidden" name="phpshop_op" value="export_goods_form" />
</form>
</body>

</html>
