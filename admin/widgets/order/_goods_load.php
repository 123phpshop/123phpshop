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
 ?><?php
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT id,name,price FROM product WHERE id = %s", $product_id);
$product = mysqli_query($localhost,$query_product);
if(!$product){$logger->fatal("数据库操作失败:".$query_product);}
$row_product = mysqli_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);
?>
<table width="100%" border="1" class="phpshop123_list_box">
  <tr>
    <td> 
      <input type="radio" checked="checked" name="product_id" value="<?php echo $row_product['id']; ?>" />     </td>
    <td><?php echo $row_product['name']; ?></td>
    <td><?php echo $row_product['price']; ?></td>
  </tr>
</table>
