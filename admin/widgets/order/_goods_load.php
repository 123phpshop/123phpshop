<?php
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT * FROM product WHERE id = %s", $product_id);
$product = mysql_query($query_product, $localhost) or die(mysql_error());
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);
?>
<table width="100%" border="1" class="phpshop123_list_box">
  <tr>
    <td> 
      <input type="radio" checked="checked" name="product_id" value="<?php echo $row_product['id']; ?>" />
     </td>
    <td><?php echo $row_product['name']; ?></td>
  </tr>
</table>