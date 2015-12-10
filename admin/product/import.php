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
 ?><?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
$doc_url="product.html#import";
$support_email_question="导入产品";
$error="";

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "import_goods_form")) {
	try{
	// 我们这里需要对上传文件进行检查
   include($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/upload.php'); 
  include($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/csv.php'); 
	$up = new fileupload;
    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    $up -> set("path", $_SERVER['DOCUMENT_ROOT']."/uploads/import/");
    $up -> set("maxsize", 2000000);
    $up -> set("allowtype", array("csv"));
    $up -> set("israndname", true);
  
    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
    if($up->upload("csv_file")) {
       $image_path="/uploads/import/".$up->getFileName(); 
	   $insertSQL = sprintf("INSERT INTO product_import (user_id, file_path) VALUES (%s, %s)",
						   GetSQLValueString($_SESSION['admin_id'], "int"),
						   GetSQLValueString($image_path, "text"));
	
	  mysql_select_db($database_localhost, $localhost);
	  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
 	  import_product($image_path);	  
     } else {
         //获取上传失败以后的错误提示
        	$error=$up->getErrorMsg();
     }
	
	}catch(Exception $ex){
		$error=$ex->getMessage();
	}
}
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_import_logs = 50;
$pageNum_import_logs = 0;
if (isset($_GET['pageNum_import_logs'])) {
  $pageNum_import_logs = $_GET['pageNum_import_logs'];
}
$startRow_import_logs = $pageNum_import_logs * $maxRows_import_logs;

$colname_import_logs = "-1";
if (isset($_SESSION['admin_id'])) {
  $colname_import_logs = (get_magic_quotes_gpc()) ? $_SESSION['admin_id'] : addslashes($_SESSION['admin_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_import_logs = sprintf("SELECT * FROM product_import WHERE user_id = %s ORDER BY id DESC", $colname_import_logs);
$query_limit_import_logs = sprintf("%s LIMIT %d, %d", $query_import_logs, $startRow_import_logs, $maxRows_import_logs);
$import_logs = mysql_query($query_limit_import_logs, $localhost) or die(mysql_error());
$row_import_logs = mysql_fetch_assoc($import_logs);

if (isset($_GET['totalRows_import_logs'])) {
  $totalRows_import_logs = $_GET['totalRows_import_logs'];
} else {
  $all_import_logs = mysql_query($query_import_logs);
  $totalRows_import_logs = mysql_num_rows($all_import_logs);
}
$totalPages_import_logs = ceil($totalRows_import_logs/$maxRows_import_logs)-1;

$queryString_import_logs = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_import_logs") == false && 
        stristr($param, "totalRows_import_logs") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_import_logs = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_import_logs = sprintf("&totalRows_import_logs=%d%s", $totalRows_import_logs, $queryString_import_logs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">导入商品</span>
  <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<p>  <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php");?></p>

<form action="" method="post" enctype="multipart/form-data" name="import_goods_form" id="import_goods_form">
	<input type="file" name="csv_file" />
	<input name="导入" type="submit" id="导入" value="提交" />
	<input name="MM_insert" type="hidden"   value="import_goods_form" />
 	<p>提示：请按照123phpshop产品固定格式导入，且只能是csv格式，点<a href="../../uploads/impor_example.csv">击这里下载范本</a>。</p>
</form>
<p class="phpshop123_title">商品导入历史</p>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td>ID</td>
    <td>文件名称</td>
    <td>上传时间</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_import_logs['id']; ?>&nbsp; </td>
      <td><a href="<?php echo $row_import_logs['file_path']; ?>"> <?php echo str_replace("/uploads/import/","",$row_import_logs['file_path']); ?>&nbsp; </a> </td>
      <td><?php echo $row_import_logs['create_time']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_import_logs = mysql_fetch_assoc($import_logs)); ?>
</table>
<br>
<table border="0" width="50%" align="right">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_import_logs > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_import_logs=%d%s", $currentPage, 0, $queryString_import_logs); ?>">第一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_import_logs > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_import_logs=%d%s", $currentPage, max(0, $pageNum_import_logs - 1), $queryString_import_logs); ?>">前一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_import_logs < $totalPages_import_logs) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_import_logs=%d%s", $currentPage, min($totalPages_import_logs, $pageNum_import_logs + 1), $queryString_import_logs); ?>">下一页</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_import_logs < $totalPages_import_logs) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_import_logs=%d%s", $currentPage, $totalPages_import_logs, $queryString_import_logs); ?>">最后一页</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
记录 <?php echo ($startRow_import_logs + 1) ?> 到 <?php echo min($startRow_import_logs + $maxRows_import_logs, $totalRows_import_logs) ?> (总共 <?php echo $totalRows_import_logs ?>
</p> 0)
</body>
</html>
<?php
mysql_free_result($import_logs);
?>