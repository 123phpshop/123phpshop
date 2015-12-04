<?php require_once('../../../Connections/localhost.php'); ?>
<?php
$colname_goods = "-1";
if (isset($_GET['name'])) {
  $colname_goods = (get_magic_quotes_gpc()) ? $_GET['name'] : addslashes($_GET['name']);
}
if($colname_goods == "-1" || trim($colname_goods)==''){
	return;
}
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,username FROM user WHERE username like '%".$colname_goods."%' and is_delete=0";
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
$row_goods_num=mysql_num_rows($goods);
if($row_goods_num>0){
?>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css">
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  <tr>
      <td width="58"> 
        <input type="radio" name="user_id" value="<?php echo $row_goods['id']; ?>" onclick="get_consignee(<?php echo $row_goods['id']; ?>)">
      </td>
      <td width="886"><?php echo $row_goods['username']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
}
?>
