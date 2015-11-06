<?php require_once('../../Connections/localhost.php'); ?>
<?php 
$backup_info="";
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_db_export")) {
 
 	//配置信息
	$cfg_dbhost = $hostname_localhost;
	$cfg_dbname = $database_localhost;
	$cfg_dbuser = $username_localhost;
	$cfg_dbpwd =  $password_localhost;
	$cfg_db_language = 'utf8';
	$to_file_name = date("YmdHis").".sql";
	// END 配置

	//链接数据库
	$backup_info.=date("Y-m-d H:i:s").": 连接数据库</br>";
	$link = mysql_connect($cfg_dbhost,$cfg_dbuser,$cfg_dbpwd);
	if($link){
		$backup_info.=date("Y-m-d H:i:s").": 数据库连接成功</br>";
	}else{
 		$backup_info.=date("Y-m-d H:i:s").": 数据库连接失败</br>";
		return;
	}
	mysql_select_db($cfg_dbname);
	//选择编码
	mysql_query("set names ".$cfg_db_language);
	//数据库中有哪些表
 	$tables = mysql_list_tables($cfg_dbname);
	//将这些表记录到一个数组
	$tabList = array();
	while($row = mysql_fetch_row($tables)){
		$tabList[] = $row[0];
	}
	
 	$info = "-- ----------------------------\r\n";
	$info .= "-- 日期：".date("Y-m-d H:i:s",time())."\r\n";
	$info .= "-- Power by 123phpshop(http://www.123phpshop.com)\r\n";
 	$info .= "-- ----------------------------\r\n\r\n";
	file_put_contents($to_file_name,$info,FILE_APPEND);

 	foreach($tabList as $val){
		$backup_info.=date("Y-m-d H:i:s").": 获取表".$val."的结构</br>";
		$sql = "show create table ".$val;
		$res = mysql_query($sql,$link);
		$row = mysql_fetch_array($res);
		$info = "-- ----------------------------\r\n";
		$info .= "-- 表结构: `".$val."`\r\n";
		$info .= "-- ----------------------------\r\n";
		$info .= "DROP TABLE IF EXISTS `".$val."`;\r\n";
		$sqlStr = $info.$row[1].";\r\n\r\n";
 		file_put_contents($to_file_name,$sqlStr,FILE_APPEND);
 		mysql_free_result($res);
	}

 	foreach($tabList as $val){
		
		$backup_info.=date("Y-m-d H:i:s").": 导出表".$val."的数据</br>";
		$sql = "select * from ".$val;
		$res = mysql_query($sql,$link);
 		if(mysql_num_rows($res)<1) continue;
		//
		$info = "-- ----------------------------\r\n";
		$info .= "-- 表：`".$val."` 的记录\r\n";
		$info .= "-- ----------------------------\r\n";
		file_put_contents($to_file_name,$info,FILE_APPEND);
 		while($row = mysql_fetch_row($res)){
			$sqlStr = "INSERT INTO `".$val."` VALUES (";
			foreach($row as $zd){
				$sqlStr .= "'".$zd."', ";
			}
 			$sqlStr = substr($sqlStr,0,strlen($sqlStr)-2);
			$sqlStr .= ");\r\n";
			file_put_contents($to_file_name,$sqlStr,FILE_APPEND);
		}
 		mysql_free_result($res);
		file_put_contents($to_file_name,"\r\n",FILE_APPEND);
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".basename($to_file_name)); 
		readfile($to_file_name);
		unlink($to_file_name);
	}
 	$backup_info.=date("Y-m-d H:i:s")."备份结束</br>";
	return;
 	}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body >
<span>数据库导出</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>

<form id="form_db_export" name="form_db_export" method="post" action="">
   <input type="submit" name="Submit" value="导出" />
   <input type="hidden" name="MM_insert" value="form_db_export" />
</form>

<?php if(str_length($backup_info)>0){?>
<p class="phpshop123_infobox">
 	<?php echo $backup_info;?>
 </p>
<?php } ?>
</body>
</html>
