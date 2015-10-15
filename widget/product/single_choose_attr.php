<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php

// 产品的id
$colname_product_atts = "-1";
if (isset($_GET['id'])) {
  $colname_product_atts = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}


mysql_select_db($database_localhost, $localhost);
$query_product_atts = sprintf("SELECT * FROM product_type_attr where is_delete=0 and input_method=2 and product_type_id=".$row_product['product_type_id']);
$product_atts = mysql_query($query_product_atts, $localhost) or die(mysql_error());
$row_product_atts = mysql_fetch_assoc($product_atts);
$totalRows_product_atts = mysql_num_rows($product_atts);
?>
<?php if ($totalRows_product_atts > 0) { // Show if recordset not empty ?>
      <?php do { ?>
      <tr style="border-color:#CCCCCC;">
        <td  height="38" scope="row" style="padding-left:12px;"><?php echo $row_product_atts['name']; ?></td>
        <td width="990" height="38" style="border-color:#CCCCCC;">
 		<?php echo $row_product_atts['selectable_value']; ?>
 		</td>
      </tr>
      <?php } while ($row_product_atts = mysql_fetch_assoc($product_atts)); ?>
<?php } // Show if recordset not empty ?>