<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php require_once('../../../Connections/localhost.php'); ?>
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
$goods = mysql_query($query_goods, $localhost) ;
if(!$goods){$logger->fatal("数据库操作失败:".$query_goods);}
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