<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="product.html#delete";
$support_email_question="删除权限";
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

// 检查这个id的权限是否存在
$colname_getById = "-1";
if (isset($_GET['id'])) {
  $colname_getById = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_getById = sprintf("SELECT * FROM privilege WHERE id = %s", $colname_getById);
$getById = mysql_query($query_getById, $localhost) or die(mysql_error());
$row_getById = mysql_fetch_assoc($getById);
$totalRows_getById = mysql_num_rows($getById);

//  如果id存在，那么进行删除
if ($totalRows_getById > 0 && (isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("update privilege  set is_delete=1 WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());

  //	如果删除成功，那么进行跳转
  $deleteGoTo = "index.php";
  header(sprintf("Location: %s", $deleteGoTo));
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<div class="phpshop123_infobox">
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
  <?php if($could_delete==0){ ?>
  <p>由于以下原因，您不能删除这个权限，请及时修正，或是联系123phpshop.com的技术支持人员！</p>
  <p>1. 权限不存在，请检查参数之后再试。</p>
  <p>2. 系统错误，无法删除，请稍后再试。 </p>
  <p>您也可以<a href="index.php">点击这里返回</a>。</p>
  <?php } ?>
</div>
</body>
</html>
<?php
mysql_free_result($getById);
?>
