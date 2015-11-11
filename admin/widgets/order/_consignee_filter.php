<?php require_once('../../../Connections/localhost.php'); ?>
<?php
$colname_goods = "-1";
if (isset($_GET['user_id'])) {
  $colname_goods = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT * FROM user_consignee WHERE is_delete=0 and  user_id = ".$colname_goods;
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
$row_goods_num=mysql_num_rows($goods);
?>
<link href="../../../css/common_admin.css" rel="stylesheet" type="text/css">
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  <tr>
       <td width="54"><label>
        <input type="radio"  <?php if($row_goods_num==1 ||$row_goods['is_default']==1 ){ ?>checked<?php } ?> name="consingee_id" value="<?php echo $row_goods['id']; ?>">
      </label></td>
       <td><?php echo $row_goods['name']; ?></td>
       <td><?php echo $row_goods['mobile']; ?></td>
       <td><?php echo $row_goods['province']; ?></td>
       <td><?php echo $row_goods['city']; ?></td>
      <td><?php echo $row_goods['district']; ?></td>
	   <td><?php echo $row_goods['address']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
mysql_free_result($goods);
?>
