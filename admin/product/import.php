
<?php 

$doc_url="ad.html#list";
$support_email_question="导入产品";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">导入商品</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<form action="" method="post" enctype="multipart/form-data" name="import_goods_form" id="import_goods_form">
  <input type="file" name="file" />
  <label>
  <input name="导入" type="submit" id="导入" value="提交" />
  </label>
  <p>&nbsp;</p>
  <p>提示：请按照123phpshop产品固定格式导入，且只能是csv格式，点<a href="../../uploads/impor_example.csv">击这里下载范本</a>。</p>
  
  <p>&nbsp;</p>
</form>
<p>&nbsp; </p>
</body>
</html>
