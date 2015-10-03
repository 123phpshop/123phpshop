<?php require_once('../../Connections/localhost.php'); 
$colname_DetailRS1 = "-1";
if (isset($_GET['shipping_method_id'])) {
  $colname_DetailRS1 = (get_magic_quotes_gpc()) ? $_GET['shipping_method_id'] : addslashes($_GET['shipping_method_id']);
}
mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['recordID'];
$query_DetailRS1 = sprintf("SELECT * FROM shipping_method_area  WHERE id = $recordID", $colname_shipping_method);
$DetailRS1 = mysql_query($query_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);

$colname_DetailRS1 = "-1";
if (isset($_GET['shipping_method_id'])) {
  $colname_DetailRS1 = (get_magic_quotes_gpc()) ? $_GET['shipping_method_id'] : addslashes($_GET['shipping_method_id']);
}
mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['recordID'];
$query_DetailRS1 = sprintf("SELECT * FROM shipping_method_area  WHERE name = '$recordID'", $colname_shipping_method);
$DetailRS1 = mysql_query($query_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
		
<table border="1" align="center">
  
  <tr>
    <td>id</td>
    <td><?php echo $row_DetailRS1['id']; ?> </td>
  </tr>
  <tr>
    <td>shipping_method_id</td>
    <td><?php echo $row_DetailRS1['shipping_method_id']; ?> </td>
  </tr>
  <tr>
    <td>area</td>
    <td><?php echo $row_DetailRS1['area']; ?> </td>
  </tr>
  <tr>
    <td>shipping_by_quantity</td>
    <td><?php echo $row_DetailRS1['shipping_by_quantity']; ?> </td>
  </tr>
  <tr>
    <td>basic_fee</td>
    <td><?php echo $row_DetailRS1['basic_fee']; ?> </td>
  </tr>
  <tr>
    <td>first_kg_fee</td>
    <td><?php echo $row_DetailRS1['first_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>continue_kg_fee</td>
    <td><?php echo $row_DetailRS1['continue_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>free_quota</td>
    <td><?php echo $row_DetailRS1['free_quota']; ?> </td>
  </tr>
  <tr>
    <td>name</td>
    <td><?php echo $row_DetailRS1['name']; ?> </td>
  </tr>
  <tr>
    <td>cod_fee</td>
    <td><?php echo $row_DetailRS1['cod_fee']; ?> </td>
  </tr>
  <tr>
    <td>single_product_fee</td>
    <td><?php echo $row_DetailRS1['single_product_fee']; ?> </td>
  </tr>
  <tr>
    <td>half_kg_fee</td>
    <td><?php echo $row_DetailRS1['half_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>continue_half_kg_fee</td>
    <td><?php echo $row_DetailRS1['continue_half_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>first_weight_fee</td>
    <td><?php echo $row_DetailRS1['first_weight_fee']; ?> </td>
  </tr>
  
  
</table>


		
<table border="1" align="center">
  
  <tr>
    <td>id</td>
    <td><?php echo $row_DetailRS1['id']; ?> </td>
  </tr>
  <tr>
    <td>shipping_method_id</td>
    <td><?php echo $row_DetailRS1['shipping_method_id']; ?> </td>
  </tr>
  <tr>
    <td>area</td>
    <td><?php echo $row_DetailRS1['area']; ?> </td>
  </tr>
  <tr>
    <td>shipping_by_quantity</td>
    <td><?php echo $row_DetailRS1['shipping_by_quantity']; ?> </td>
  </tr>
  <tr>
    <td>basic_fee</td>
    <td><?php echo $row_DetailRS1['basic_fee']; ?> </td>
  </tr>
  <tr>
    <td>first_kg_fee</td>
    <td><?php echo $row_DetailRS1['first_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>continue_kg_fee</td>
    <td><?php echo $row_DetailRS1['continue_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>free_quota</td>
    <td><?php echo $row_DetailRS1['free_quota']; ?> </td>
  </tr>
  <tr>
    <td>name</td>
    <td><?php echo $row_DetailRS1['name']; ?> </td>
  </tr>
  <tr>
    <td>cod_fee</td>
    <td><?php echo $row_DetailRS1['cod_fee']; ?> </td>
  </tr>
  <tr>
    <td>single_product_fee</td>
    <td><?php echo $row_DetailRS1['single_product_fee']; ?> </td>
  </tr>
  <tr>
    <td>half_kg_fee</td>
    <td><?php echo $row_DetailRS1['half_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>continue_half_kg_fee</td>
    <td><?php echo $row_DetailRS1['continue_half_kg_fee']; ?> </td>
  </tr>
  <tr>
    <td>first_weight_fee</td>
    <td><?php echo $row_DetailRS1['first_weight_fee']; ?> </td>
  </tr>
  
  
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>
