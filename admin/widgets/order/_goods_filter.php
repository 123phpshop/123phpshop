<?php require_once('../../../Connections/localhost.php'); ?>
<?php
$colname_goods = "-1";
if (isset($_GET['product_name'])) {
  $colname_goods = (get_magic_quotes_gpc()) ? $_GET['product_name'] : addslashes($_GET['product_name']);
}
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,name FROM product WHERE is_delete=0 and name like '%".$colname_goods."%'";
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$goods_num = mysql_num_rows($goods);
//$row_goods = mysql_fetch_assoc($goods);
?>
<?php if($goods_num>0){ ?>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css">
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  <tr>
       <td> 
        <input type="radio" name="product_id" value="<?php echo $row_goods['id']; ?>">
           </td>
      <td><?php echo $row_goods['name']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
}
?>
