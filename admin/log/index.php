<?php
 require_once('../../Connections/localhost.php');
global $global_file_list_array;
$add_privielges_exception="";
$doc_url="log.html";
$support_email_question="查看日志文件";
log_admin($support_email_question);
function log_file_list_select($path)  
{  
	if ($handle = opendir($path))//打开路径成功  
	{  
		while (false !== ($file = readdir($handle)))//循环读取目录中的文件名并赋值给$file  
		{  
			$exclude_dir=array("htaccess","js","css","Connections",".","uploads","_mmServerScripts",".settings","..","_notes",".git",".gitignore","_mmServerScript");
			
			$exclode_file_array=array(".settings",".project",".buildpath",".htaccess");
 			if (!in_array($file,$exclude_dir))//排除当前路径和前一路径  
			{   
				if (!is_dir($path."/".$file))  
				{  
  					   if(!in_array($file,$exclode_file_array)){
						   global $global_file_list_array;
						   $global_file_list_array[]=$path."/".$file;   
					   }
  				}  
			}  
		}  
	}
}
	 
log_file_list_select($_SERVER['DOCUMENT_ROOT']."/logs");
rsort($global_file_list_array);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">日志列表</p>
<?php if(count($global_file_list_array)>0){

 ?>
<table width="100%" border="0" class="phpshop123_list_box">
  <tr>
    <th width="86%" scope="col">文件名称</th>
    <th width="14%" scope="col">操作</th>
  </tr>
  <?php foreach($global_file_list_array as $global_file_list_row){?>
  <tr>
    <td><a href="detail.php?file=<?php echo str_replace($_SERVER['DOCUMENT_ROOT']."/logs/",'',$global_file_list_row);?>"><?php echo $global_file_list_row;?></a></td>
    <td><div align="center"><a href="detail.php?file=<?php echo str_replace($_SERVER['DOCUMENT_ROOT']."/logs/",'',$global_file_list_row);?>">查看</a></div></td>
  </tr><?php }?>
</table>
<?php }?>
</body>
</html>
