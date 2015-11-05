<?php require_once('../../../Connections/localhost.php'); ?>
<?php
$maxRows_goods = 10;
$pageNum_goods = 0;
if (isset($_GET['pageNum_goods'])) {
  $pageNum_goods = $_GET['pageNum_goods'];
}
$startRow_goods = $pageNum_goods * $maxRows_goods;

$colname_goods = "-1";
if (isset($_GET['ids'])) {
  $colname_goods = (get_magic_quotes_gpc()) ? $_GET['ids'] : addslashes($_GET['ids']);
}
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,name FROM product WHERE id in ('".$colname_goods."')";
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
?>
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  <tr>
   
      <td><label>
        <input type="checkbox" name="checkbox" value="checkbox">
          </label></td>
      <td><?php echo $row_goods['name']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
mysql_free_result($goods);
?>
