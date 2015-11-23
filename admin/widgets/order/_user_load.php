<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
$colname_goods = "-1";
if (isset($row_order['user_id'])) {
  $colname_goods = (get_magic_quotes_gpc()) ? $row_order['user_id'] : addslashes($row_order['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,username FROM user WHERE id= ".$colname_goods." and is_delete=0";
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
$row_goods_num=mysql_num_rows($goods);
if($row_goods_num==1){
?>
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  <tr>
      <td width="58"> 
        <input type="radio" checked name="user_id" value="<?php echo $row_goods['id']; ?>" onclick="get_consignee(<?php echo $row_goods['id']; ?>)">
      </td>
      <td width="886"><?php echo $row_goods['username']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table> 
<?php } ?>