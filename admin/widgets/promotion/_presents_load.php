<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
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
 ?><?php
if(isset($row_promotion['present_products']) && $row_promotion['present_products']!=""){
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,name,price FROM product WHERE id in (".$row_promotion['present_products'].")";
$goods = mysql_query($query_goods, $localhost) ;
if(!$goods){$logger->fatal("数据库操作失败:".$query_goods);}
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