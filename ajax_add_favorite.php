<?php require_once('Connections/localhost.php'); ?>
<?php
// 初始化结果数据结果
$result=array('code'=>'0','message'=>'SUCCEED');

try{
  // 检查用户是否登录，如果没有登录的话，那么不能调用
if(!isset($_SESSION['user_id'])){
	throw new Exception("请登录后重试！");
}


// 检查商品是否存在，如果不存在，那么告知
$colname_product = "-1";
if (isset($_POST['product_id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_POST['product_id'] : addslashes($_POST['product_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT * FROM product WHERE is_delete=0 and id = %s", $colname_product);
$product = mysql_query($query_product, $localhost) or die(mysql_error());
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);
if($totalRows_product==0){
	throw new Exception("商品不存在，请刷新后重试！");
}
// 检查用户是否已经收藏了这个商品，如果已经收藏了，那么告知不能重复收藏
$colname_user_favorite = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_user_favorite = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_user_favorite = sprintf("SELECT * FROM user_favorite WHERE user_id = %s and product_id=%s", $colname_user_favorite,$colname_product);
$user_favorite = mysql_query($query_user_favorite, $localhost) or die(mysql_error());
$row_user_favorite = mysql_fetch_assoc($user_favorite);
$totalRows_user_favorite = mysql_num_rows($user_favorite);
if($totalRows_user_favorite>0){
	throw new Exception("重复收藏！");
}
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

// 插入记录
$insertSQL = sprintf("INSERT INTO user_favorite (user_id, product_id) VALUES (%s, %s)",
			   GetSQLValueString($colname_user_favorite, "int"),
			   GetSQLValueString($_POST['product_id'], "int"));

mysql_select_db($database_localhost, $localhost);
$Result1 = mysql_query($insertSQL, $localhost);
if(!$Result1){
	throw new Exception("系统错误，收藏失败，请稍后重试！");
}
 
	echo json_encode($result);return;
}catch(Exception $ex){
	$result['code']=1;
	$result['message']=$ex->getMessage();
	echo json_encode($result);return;
}
?>