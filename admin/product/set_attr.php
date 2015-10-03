<?php require_once('../../Connections/localhost.php'); ?>
<?php
$colname_product = "-1";
if (isset($_GET['product_id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_GET['product_id'] : addslashes($_GET['product_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT id, name, product_type_id FROM product WHERE id = %s", $colname_product);
$product = mysql_query($query_product, $localhost) or die(mysql_error());
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);

mysql_select_db($database_localhost, $localhost);
$query_product_type_attrs = "SELECT * FROM product_type_attr WHERE product_type_id = ".$row_product['product_type_id']." and is_selectable=1";
$product_type_attrs = mysql_query($query_product_type_attrs, $localhost) or die(mysql_error());
$row_product_type_attrs = mysql_fetch_assoc($product_type_attrs);
$totalRows_product_type_attrs = mysql_num_rows($product_type_attrs);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<p><?php echo $row_product['name']; ?>:产品属性设置</p>
<form id="form1" name="form1" method="post" action="">
  <p>&nbsp;</p>
    <table width="960" border="1">
	  <?php do { ?>

      <tr>
        <th scope="row"><?php echo $row_product_type_attrs['name']; ?></th>
        <td><label>
          <input type="text" name="<?php echo $row_product_type_attrs['name']; ?>" />
        </label></td>
      </tr>
	      <?php } while ($row_product_type_attrs = mysql_fetch_assoc($product_type_attrs)); ?><p>

          </table>
    <label>
    <input type="submit" name="Submit" value="提交" />
    </label>
  </p>
</form>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($product);

mysql_free_result($product_type_attrs);
?>
