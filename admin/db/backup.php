<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php 
$doc_url="db.html";
$support_email_question="备份数据库";
log_admin($support_email_question);
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
<span class="phpshop123_title">数据库导出</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>

<form id="form_db_export" name="form_db_export" method="post" action="">
   <input type="submit" name="Submit" value="导出" id="db_export_button" onclick="export_db()" />
   <input type="hidden" name="MM_insert" value="form_db_export" />
</form>

<?php if(strlen($backup_info)>0){?>
<p class="phpshop123_infobox">
 	<?php echo $backup_info;?>
</p>
<?php } ?>
<script language="JavaScript" type="text/javascript"
		src="/js/jquery-1.7.2.min.js"></script>
<script>
function export_db(){
	$("#db_export_button").val('正在导出...');
	$("#db_export_button").attr('disabeld','disabled');
	return true;
}
</script>
</body>
</html>