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
	
//		检查用户是否已经有了收货人记录，如果没有的话，那么自动设置为默认
	$is_default=0;
	$colname_consignees = "-1";
	if (isset($_SESSION['user_id'])) {
	  $colname_consignees = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_consignees = sprintf("SELECT * FROM user_consignee WHERE is_delete=0 and user_id = %s order by is_default desc", $colname_consignees);
	$consignees = mysql_query($query_consignees, $localhost) or die(mysql_error());
 	$totalRows_consignees = mysql_num_rows($consignees);
	if($totalRows_consignees==0){
		$is_default=1;
	}
	
	$update_catalog = sprintf("update `user_consignee` set is_default=0 where user_id=%s and id != %s",$_SESSION['user_id'], $colname_consignee);
	$update_catalog_query = mysql_query($update_catalog, $localhost);
		
	
  $insertSQL = sprintf("INSERT INTO user_consignee (is_default,name, mobile, province, city, district, address, zip, user_id) VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s)",
  					   GetSQLValueString($is_default, "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['mobile'], "text"),
                       GetSQLValueString($_POST['province'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['district'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['zip'], "text"),
                       GetSQLValueString($_SESSION['user_id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

$colname_consignees = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_consignees = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_consignees = sprintf("SELECT * FROM user_consignee WHERE is_delete=0 and user_id = %s order by is_default desc", $colname_consignees);
$consignees = mysql_query($query_consignees, $localhost) or die(mysql_error());
$row_consignees = mysql_fetch_assoc($consignees);
$totalRows_consignees = mysql_num_rows($consignees);
?>