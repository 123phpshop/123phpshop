<?php require_once('../../Connections/localhost.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
$doc_url="admin_log.html";
$support_email_question="查看管理员日志文件";
$maxRows_admin_logs = 50;
$pageNum_admin_logs = 0;
$where='';
if (isset($_GET['pageNum_admin_logs'])) {
  $pageNum_admin_logs = $_GET['pageNum_admin_logs'];
}
$startRow_admin_logs = $pageNum_admin_logs * $maxRows_admin_logs;
$where=_get_where($_GET);


$query_admin_logs = "SELECT * FROM admin_op $where ORDER BY id DESC";
$query_limit_admin_logs = sprintf("%s LIMIT %d, %d", $query_admin_logs, $startRow_admin_logs, $maxRows_admin_logs);
$admin_logs = mysqli_query($localhost,$query_limit_admin_logs);
if(!$admin_logs){$logger->fatal($query_admin_logs);}
$row_admin_logs = mysqli_fetch_assoc($admin_logs);

if (isset($_GET['totalRows_admin_logs'])) {
  $totalRows_admin_logs = $_GET['totalRows_admin_logs'];
} else {
  $all_admin_logs = mysqli_query($localhost,$query_admin_logs);
  $totalRows_admin_logs = mysqli_num_rows($all_admin_logs);
}
$totalPages_admin_logs = ceil($totalRows_admin_logs/$maxRows_admin_logs)-1;

$queryString_admin_logs = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_admin_logs") == false && 
        stristr($param, "totalRows_admin_logs") == false) {
      	array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_admin_logs = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_admin_logs = sprintf("&totalRows_admin_logs=%d%s", $totalRows_admin_logs, $queryString_admin_logs);


function _get_where($get){
	$_should_and=0;	
	$where_string='';
	
 	if(isset($get['message']) && trim($get['message'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		if(strpos($where_string,"where")==false){
			$where_string.=" where ";
		}
		$where_string.=" message like'%".trim($get['message'])."%'";
		
		$_should_and=1;	
 	}
	
	if(isset($get['admin_username']) && trim($get['admin_username'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		
		if(false==strpos($where_string,"where")){
			$where_string.=" where ";
		}
			
		$where_string.=" admin_id like'%".trim($get['admin_username'])."%'";
	}
	
	return $where_string;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">管理员操作日志</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<form id="form1" name="form1" method="get" action="">
  <table width="100%" border="0" class="phpshop123_infobox">
    <tr>
      <td width="32%"><div align="center">操作：
        <input name="message" type="text" id="message" value="<?php echo isset($_GET['message'])?$_GET['message']:'';?>" />
      </div></td>
      <td width="23%"><div align="center">管理员:
        <input name="admin_username" type="text" id="admin_username" value="<?php echo isset($_GET['admin_username'])?$_GET['admin_username']:'';?>" />
      </div></td>
      <td width="45%"><div align="left">
          <input type="submit" name="Submit" value="提交" />
      </div></td>
    </tr>
  </table>
</form>
<?php if($totalRows_admin_logs>0){ ?>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td>id</td>
    <td>操作</td>
    <td>管理员</td>
    <td>创建时间</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_admin_logs['id']; ?>&nbsp; </td>
      <td><?php echo $row_admin_logs['message']; ?>&nbsp; </a> </td>
      <td><?php echo $row_admin_logs['admin_id']; ?>&nbsp; </td>
      <td><?php echo $row_admin_logs['create_time']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_admin_logs = mysqli_fetch_assoc($admin_logs)); ?>
</table>

<br>
<table border="0" width="50%" align="right">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_admin_logs > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_admin_logs=%d%s", $currentPage, 0, $queryString_admin_logs); ?>">第一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_admin_logs > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_admin_logs=%d%s", $currentPage, max(0, $pageNum_admin_logs - 1), $queryString_admin_logs); ?>">前一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_admin_logs < $totalPages_admin_logs) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_admin_logs=%d%s", $currentPage, min($totalPages_admin_logs, $pageNum_admin_logs + 1), $queryString_admin_logs); ?>">下一页</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_admin_logs < $totalPages_admin_logs) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_admin_logs=%d%s", $currentPage, $totalPages_admin_logs, $queryString_admin_logs); ?>">最后一页</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
<p>记录 <?php echo ($startRow_admin_logs + 1) ?> 到 <?php echo min($startRow_admin_logs + $maxRows_admin_logs, $totalRows_admin_logs) ?> (总共 <?php echo $totalRows_admin_logs ?>）
</p>
<?php }else{ ?>
<p class="phpshop123_infobox">没有记录！</p>
<?php  } ?>
<?php log_admin("查看管理员操作日志");?>
</body>
</html>