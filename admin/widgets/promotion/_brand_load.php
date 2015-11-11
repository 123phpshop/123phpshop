<?php //require_once('../../../Connections/localhost.php'); ?>
<?php
$maxRows_goods = 10;
$pageNum_goods = 0;
if (isset($_GET['pageNum_goods'])) {
  $pageNum_goods = $_GET['pageNum_goods'];
}
$startRow_goods = $pageNum_goods * $maxRows_goods;

mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,name FROM brands WHERE id in (".$row_promotion['promotion_limit_value'].")";
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
?>
<?php echo var_export($row_goods['name']); ?>
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  	<tr>
       	<td> 
        	<input type="checkbox" name="promotion_limit_value[]" checked value="<?php echo $row_goods['id']; ?>">
        </td>
      <td><?php echo $row_goods['name']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
mysql_free_result($goods);
?>

