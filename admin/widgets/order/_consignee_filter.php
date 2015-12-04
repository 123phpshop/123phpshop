<?php require_once('../../../Connections/localhost.php'); ?>
<?php
$colname_goods = "-1";
if (isset($_GET['user_id'])) {
  $colname_goods = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}

if($colname_goods == "-1" || trim($colname_goods)==''){
	return;
}

mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT * FROM user_consignee WHERE is_delete=0 and  user_id = ".$colname_goods;
$goods = mysql_query($query_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);
$row_goods_hidden=$row_goods;
$row_goods_num=mysql_num_rows($goods);
?>
 <?php if($row_goods_num>0){ ?>
<table width="960" border="1" class="phpshop123_list_box">
   <?php do { ?>
  <tr>
       <td width="54"> 
       <input type="radio" onclick="set_consignee(this)" consignee_name="<?php echo $row_goods['name']; ?>" consignee_province="<?php echo $row_goods['province']; ?>" consignee_city="<?php echo $row_goods['city']; ?>" consignee_district="<?php echo $row_goods['district']; ?>"   consignee_address="<?php echo $row_goods['address']; ?>" consignee_zip="<?php echo $row_goods['zip']; ?>" consignee_mobile="<?php echo $row_goods['mobile']; ?>" <?php if($row_goods_num==1 ||$row_goods['is_default']==1 ){ ?>checked<?php } ?> name="consingee_id" value="<?php echo $row_goods['id']; ?>">
       </td>
       <td><?php echo $row_goods['name']; ?></td>
       <td><?php echo $row_goods['mobile']; ?></td>
       <td><?php echo $row_goods['province']; ?></td>
       <td><?php echo $row_goods['city']; ?></td>
       <td><?php echo $row_goods['district']; ?></td>
	   <td><?php echo $row_goods['address']; ?></td>
	   <td><?php echo $row_goods['zip']; ?></td>
    </tr>
	  <?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
}
?>
<?php if($row_goods_num==1 ||$row_goods_hidden['is_default']==1 ){ ?>
<input name="consignee_name" id="consignee_name" type="hidden" value="<?php echo $row_goods_hidden['name']; ?>" />
<input name="consignee_mobile" id="consignee_mobile" type="hidden" value="<?php echo $row_goods_hidden['mobile']; ?>" />
<input name="consignee_province" id="consignee_province" type="hidden" value="<?php echo $row_goods_hidden['province']; ?>" />
<input name="consignee_city" id="consignee_city" type="hidden" value="<?php echo $row_goods_hidden['city']; ?>" />
<input name="consignee_district" id="consignee_district" type="hidden" value="<?php echo $row_goods_hidden['district']; ?>" />
<input name="consignee_address" id="consignee_address" type="hidden" value="<?php echo $row_goods_hidden['address']; ?>" />
<input name="consignee_zip" id="consignee_zip" type="hidden" value="<?php echo $row_goods_hidden['zip']; ?>" />
<?php }elseif($row_goods_num>1){
	foreach($row_goods_hidden as $row_goods_item){
 	if($row_goods_item['is_default']==0){
		continue;
	}
?>
<input name="consignee_name" id="consignee_name" type="hidden" value="<?php echo $row_goods_hidden['name']; ?>" />
<input name="consignee_mobile"  id="consignee_mobile" type="hidden" value="<?php echo $row_goods_hidden['mobile']; ?>" />
<input name="consignee_province" id="consignee_province" type="hidden" value="<?php echo $row_goods_hidden['province']; ?>" />
<input name="consignee_city"  id="consignee_city" type="hidden" value="<?php echo $row_goods_hidden['city']; ?>" />
<input name="consignee_district"  id="consignee_district" type="hidden" value="<?php echo $row_goods_hidden['district']; ?>" />
<input name="consignee_address"  id="consignee_address" type="hidden" value="<?php echo $row_goods_hidden['address']; ?>" />
<input name="consignee_zip"  id="consignee_zip" type="hidden" value="<?php echo $row_goods_hidden['zip']; ?>" />
<?php } ?>
<?php }?>
