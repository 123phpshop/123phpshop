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
 
//如果需要更新的话
if ((isset($_POST["form_op"])) && ($_POST["form_op"] == "update_product_attr")) {
 	foreach($_POST as $key=>$value){
		if($key!='Submit' && $key!='form_op'  ){
			$sql="update product_type_attr_val set product_type_attr_value='".$value."' where product_id='".$colname_product."' and product_type_attr_id='".str_replace("attr_","",$key)."'";
			
 			$query=mysql_query($sql);
			  if(!$query){$logger->fatal("数据库操作失败:".mysql_error().$sql);}
 		}
	}
   $insertGoTo = "index.php";
   header(sprintf("Location: %s", $insertGoTo));
 }

mysql_select_db($database_localhost, $localhost);
$query_product_atts = sprintf("SELECT product_type_attr_val.*,product_type_attr.name  FROM product_type_attr_val inner join product_type_attr on product_type_attr.id=product_type_attr_val.product_type_attr_id  WHERE product_type_attr_val.product_id = %s and product_type_attr.is_delete=0", $colname_product);
$product_atts = mysql_query($query_product_atts, $localhost) ;
	if(!$product_atts){$logger->fatal("数据库操作失败:".mysql_error().$query_product_atts);}
$row_product_atts = mysql_fetch_assoc($product_atts);
$totalRows_product_atts = mysql_num_rows($product_atts);
?>
<?php if ($totalRows_product_atts > 0) { // Show if recordset not empty ?>
   <form method="post" >
    <table width="990" height="31" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><table style="background-color:white;border-top:2px solid red;border-bottom-width:0px" width="105" height="33" border="1" cellpadding="0" cellspacing="0" bordercolor="#DEDFDE">
            <tr>
              <td><div align="center"><a style="text-decoration:none;color:#000000;" href="javascript://" name="attr_list" id="attr_list">规格参数</a></div></td>
            </tr>
        </table></td>
        <td><table  style="border-bottom:1px solid #DEDFDE " width="885" height="31" border="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table>
  <table width="990" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" style="background-color:#F5FAFE;border-color:#CCCCCC;">
    <?php do { ?>
      <tr style="border-color:#CCCCCC;">
        <th width="15%" height="30" scope="row" style="border-color:#CCCCCC;"><?php echo $row_product_atts['name']; ?></th>
        <td width="990" height="30" style="border-color:#CCCCCC;"><?php echo $row_product_atts['product_type_attr_value']; ?></td>
      </tr>
      <?php } while ($row_product_atts = mysql_fetch_assoc($product_atts)); ?>
      </table>
	  <input type="hidden" name="form_op" value="update_product_attr"/>
	  </form>
<?php } // Show if recordset not empty ?>