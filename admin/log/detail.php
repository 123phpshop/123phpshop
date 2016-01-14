<?php 
	ob_start();
	$file_name=$_GET['file'];
	// 检查文件是否存在，如果不存在，那么直接返回
	$file=is_file($_SERVER['DOCUMENT_ROOT']."/logs/".$file_name);
		if(!$file){
			$insertGoTo = "index.php";
		     header(sprintf("Location: %s", $insertGoTo));
			 return;
	}
	// 如果文件存在，那么读取文件的内容
	$contents=file_get_contents($_SERVER['DOCUMENT_ROOT']."/logs/".$file_name);
	 
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body >
<p class="phpshop123_title">查看日志文件</p>
<p><?php echo str_replace("FATAL","<span style='color:red;font-weight:bold;'>FATAL</span>",nl2br($contents));?></p>
</body>


</html>
