<?php require_once('../../Connections/localhost.php'); 
$currentPage = $_SERVER["PHP_SELF"];

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO product_type (name, pid) VALUES (%s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['pid'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "index.php?pid=" . isset($_GET['pid'])?$_GET['pid']:"0";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_product_type = 50;
$pageNum_product_type = 0;
if (isset($_GET['pageNum_product_type'])) {
  $pageNum_product_type = $_GET['pageNum_product_type'];
}
$startRow_product_type = $pageNum_product_type * $maxRows_product_type;

$colname_product_type = "0";
if (isset($_GET['pid'])) {
  $colname_product_type = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
mysql_select_db($database_localhost, $localhost);
$query_product_type = sprintf("SELECT * FROM product_type WHERE pid = %s", $colname_product_type);
$query_limit_product_type = sprintf("%s LIMIT %d, %d", $query_product_type, $startRow_product_type, $maxRows_product_type);
$product_type = mysql_query($query_limit_product_type, $localhost) or die(mysql_error());
$row_product_type = mysql_fetch_assoc($product_type);

if (isset($_GET['totalRows_product_type'])) {
  $totalRows_product_type = $_GET['totalRows_product_type'];
} else {
  $all_product_type = mysql_query($query_product_type);
  $totalRows_product_type = mysql_num_rows($all_product_type);
}
$totalPages_product_type = ceil($totalRows_product_type/$maxRows_product_type)-1;

$queryString_product_type = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_product_type") == false && 
        stristr($param, "totalRows_product_type") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_product_type = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_product_type = sprintf("&totalRows_product_type=%d%s", $totalRows_product_type, $queryString_product_type);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">添加产品类型</p>
<p>&nbsp; </p>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_search_box">
    <tr valign="baseline">
      <td nowrap align="right">产品类型:</td>
      <td><input type="text" name="name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </table>
  <input type="hidden" name="pid" value="<?php echo isset($_GET['pid'])?$_GET['pid']:"0"; ?>">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<table width="50%" border="0" align="right">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_product_type > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, 0, $queryString_product_type); ?>">第一页</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_product_type > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, max(0, $pageNum_product_type - 1), $queryString_product_type); ?>">前一页</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_product_type < $totalPages_product_type) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, min($totalPages_product_type, $pageNum_product_type + 1), $queryString_product_type); ?>">下一页</a>
        <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_product_type < $totalPages_product_type) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, $totalPages_product_type, $queryString_product_type); ?>">最后一页</a>
        <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
<p>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td width="3%"><div align="center">选择</div></td>
    <td width="46%">名称</td>
    <td width="51%"><div align="right">操作</div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><label>
        <div align="center">
          <input type="checkbox" name="checkbox" value="checkbox" />
          </div>
      </label> </td>
      <td><a href="detail.php?recordID=<?php echo $row_product_type['id']; ?>"> <?php echo $row_product_type['name']; ?>&nbsp; </a> </td>
      <td><div align="right"><a href="remove.php?id=<?php echo $row_product_type['id']; ?>">删除</a> <a href="update.php?id=<?php echo $row_product_type['id']; ?>">更新</a> <a href="../attr_group/add.php?product_type_id=<?php echo $row_product_type['id']; ?>">添加属性组</a> <a href="../attr_group/index.php?product_type_id=<?php echo $row_product_type['id']; ?>">属性列表</a></div></td>
    </tr>
    <?php } while ($row_product_type = mysql_fetch_assoc($product_type)); ?>
</table>
<br>
<table border="0" width="50%" align="right">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_product_type > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, 0, $queryString_product_type); ?>">第一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_product_type > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, max(0, $pageNum_product_type - 1), $queryString_product_type); ?>">前一页</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_product_type < $totalPages_product_type) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, min($totalPages_product_type, $pageNum_product_type + 1), $queryString_product_type); ?>">下一页</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_product_type < $totalPages_product_type) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_product_type=%d%s", $currentPage, $totalPages_product_type, $queryString_product_type); ?>">最后一页</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
记录 <?php echo ($startRow_product_type + 1) ?> 到 <?php echo min($startRow_product_type + $maxRows_product_type, $totalRows_product_type) ?> (总共 <?php echo $totalRows_product_type ?>
</p>
</body>
</html>
<?php
mysql_free_result($product_type);
?>
