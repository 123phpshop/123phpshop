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
 ?><?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
$doc_url="ad.html#list";
$support_email_question="设置产品属性";
//	准备参数
$colname_product = "-1";
if (isset($_GET['product_id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_GET['product_id'] : addslashes($_GET['product_id']);
}

//	获取这个产品的类型id
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT id, name, product_type_id FROM product WHERE id = %s", $colname_product);
$product = mysqli_query($localhost,$query_product);
if(!$product){$logger->fatal("数据库操作失败:".$query_product);}
$row_product = mysqli_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);


// 根据类型的id获取相关的属性
mysql_select_db($database_localhost, $localhost);
$query_product_type_attrs = "SELECT * FROM product_type_attr WHERE product_type_id = ".$row_product['product_type_id']." and is_delete=0 and input_method!=2";
$product_type_attrs = mysqli_query($localhost,$query_product_type_attrs);
if(!$product_type_attrs){$logger->fatal("数据库操作失败:".$query_product_type_attrs);}
$row_product_type_attrs = mysqli_fetch_assoc($product_type_attrs);
$totalRows_product_type_attrs = mysql_num_rows($product_type_attrs);

if($totalRows_product_type_attrs>0){
	// 获取这个产品的所有的属性值，如果可以获取相关记录的话，那么进行更新，如果没有记录的话，那么直接插入。
	$colname_get_product_attr_val = "-1";
	if (isset($_GET['product_id'])) {
	  $colname_get_product_attr_val = (get_magic_quotes_gpc()) ? $_GET['product_id'] : addslashes($_GET['product_id']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_get_product_attr_val = sprintf("SELECT * FROM product_type_attr_val WHERE product_id = %s and product_type_attr_id=%s", $colname_get_product_attr_val,$row_product_type_attrs['id']);
	$get_product_attr_val = mysqli_query($localhost,$query_get_product_attr_val);
	if(!$get_product_attr_val){$logger->fatal("数据库操作失败:".$query_get_product_attr_val);}
	$row_get_product_attr_val = mysqli_fetch_assoc($get_product_attr_val);
	$totalRows_get_product_attr_val = mysql_num_rows($get_product_attr_val);
}
?> 

<?php if($totalRows_product_type_attrs>0){ ?>
<table width="960" border="0" class="phpshop123_form_box">
<?php do { ?>
<?php
if($row_product_type_attrs['input_method']==1 && $row_product_type_attrs['is_selectable']==2){ ?>
<tr>
<td width="10%" scope="row"><?php echo $row_product_type_attrs['name']; ?></td>
<td width="90%"> 
<?php 
// 获取这个产品的所有的属性值
$colname_get_product_attr_val = "-1";
if (isset($_GET['product_id'])) {
$colname_get_product_attr_val = (get_magic_quotes_gpc()) ? $_GET['product_id'] : addslashes($_GET['product_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_get_product_attr_val = sprintf("SELECT * FROM product_type_attr_val WHERE product_id = %s and product_type_attr_id=%s", $colname_get_product_attr_val,$row_product_type_attrs['id']);
$get_product_attr_val = mysqli_query($localhost,$query_get_product_attr_val);
if(!$get_product_attr_val){$logger->fatal("数据库操作失败:".$query_get_product_attr_val);}
$row_get_product_attr_val = mysqli_fetch_assoc($get_product_attr_val);
$totalRows_get_product_attr_val = mysql_num_rows($get_product_attr_val);
?>
<?php
if($row_product_type_attrs['input_method']==1 && $row_product_type_attrs['is_selectable']==2){ ?> 
<?php $attr_item=explode("\r",$row_get_product_attr_val['product_type_attr_value']);?>
<?php foreach($attr_item as $attr){  ?>
	<input type="radio" onClick="set_attr_val(this)" attr_name="<?php echo $row_product_type_attrs['name']; ?>" name="attr_<?php echo $row_product_type_attrs['id']; ?>"  value="<?php echo $attr;?>"/><?php echo $attr;?>
<?php  } }?>
</td>
</tr>
<?php } ?>
<?php } while ($row_product_type_attrs = mysqli_fetch_assoc($product_type_attrs)); ?>
</table>
<?php } ?>