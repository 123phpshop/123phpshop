<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<style>
.attr_value_item{
 	color:#000000;
	border:1px solid #CCCCCC;
	margin-right:2px;
	padding:1px 5px;
	text-align:center;
 }
 
 .attr_value_item_active{
 	color:#000000;
	border:1px solid #FF0000;
	margin-right:2px;
	padding:1px 5px;
	text-align:center;
 }
 
 .attr_value_item:hover{
 	cursor:pointer;
 }
 
</style>
<?php

$attr_value="";// 准备产品的单选属性的初始值
// 产品的id
$colname_product_atts = "-1";
if (isset($_GET['id'])) {
  $colname_product_atts = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

mysql_select_db($database_localhost, $localhost);
$query_product_atts = sprintf("SELECT * FROM product_type_attr where is_delete=0 and input_method=2 and product_type_id=".$row_product['product_type_id']);
$product_atts = mysql_query($query_product_atts, $localhost) or die(mysql_error());
$row_product_atts = mysql_fetch_assoc($product_atts);
$totalRows_product_atts = mysql_num_rows($product_atts);

?>
<?php if ($totalRows_product_atts > 0) { // Show if recordset not empty ?>
      <?php do { ?>
      <tr style="border-color:#CCCCCC;">
        <td  height="38" scope="row" style="padding-left:12px;"><?php echo $row_product_atts['name']; ?></td>
        <td width="990" height="38" style="border-color:#CCCCCC;">
 		<?php  $attr_value_array=explode("\n",$row_product_atts['selectable_value']);$is_first_attr=true;
foreach($attr_value_array as $attr_value_item){ ?><span <?php if($is_first_attr==true){ ?>class="attr_value_item_active"<?php ;$attr_value.=$row_product_atts['name'].":".trim($attr_value_item).";";$is_first_attr=false; }else{ ?>class="attr_value_item"<?php  }?> onclick="select_attr(<?php echo $row_product_atts['id']; ?>,'<?php echo trim($attr_value_item);?>')" attr_value=<?php echo trim($attr_value_item);?>  attr_name=<?php echo $row_product_atts['name']; ?> attr_value_id="<?php echo $row_product_atts['id']; ?>"><?php echo trim($attr_value_item);?></span><?php } ?>
  		</td>
      </tr>
      <?php } while ($row_product_atts = mysql_fetch_assoc($product_atts)); ?>
<?php } // Show if recordset not empty ?>

<script>
function select_attr(id,name){
 	//		这里需要对ui进行标记
	$("span[attr_value_id="+id+"]").removeClass("attr_value_item_active");
	$("span[attr_value_id="+id+"]").addClass("attr_value_item");
	
	$("span[attr_value_id="+id+"][attr_value="+name+"]").removeClass("attr_value_item");
 	$("span[attr_value_id="+id+"][attr_value="+name+"]").addClass("attr_value_item_active");
	
	
	//	获取所有被选中的属性id和值进行记录
	 var attr_value="";
	$(".attr_value_item_active").each(function(){
		attr_value+=$(this).attr("attr_name")+":"+$(this).attr("attr_value")+";";
	});
	$("#attr_value").val(attr_value); 
}
</script>