 <?php
if(isset($row_promotion['present_products']) && $row_promotion['present_products']!=""){
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,name,price FROM product WHERE id in (".$row_promotion['present_products'].")";
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
$row_goods_num = mysql_num_rows($goods);
?>
<?php if($row_goods_num>0){ ?>
<link href="../../../css/common_admin.css" rel="stylesheet" type="text/css">
<table width="960" border="1" class="phpshop123_list_box">
 <?php do { ?> <tr>
       <td> 
        <input name="present_products[]" checked type="checkbox"  value="<?php echo $row_goods['id']; ?>">
           </td>
      <td><?php echo $row_goods['name']; ?></td>
	  <td>￥<?php echo $row_goods['price']; ?></td>
      </tr><?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
}

}
?>
