<?php
if($row_promotion['promotion_limit_value']==''){
	return;
}
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,name FROM catalog WHERE id in (".$row_promotion['promotion_limit_value'].")";
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
$row_goods_num = mysql_num_rows($goods);
?>
<?php if($row_goods_num>0){ ?>
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  <tr>
       <td> 
        <input type="checkbox" name="promotion_limit_value[]" checked value="<?php echo $row_goods['id']; ?>">
          </label></td>
      <td><?php echo $row_goods['name']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
}
?>


