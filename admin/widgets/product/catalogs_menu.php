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
mysql_select_db($database_localhost, $localhost);
$query_catalogs = "SELECT * FROM `catalog`";
$catalogs = mysql_query($query_catalogs, $localhost) or die(mysql_error());
$row_catalogs = mysql_fetch_assoc($catalogs);
$totalRows_catalogs = mysql_num_rows($catalogs);
?>
<select name="catalog_id" id="catalog_id">
  <?php
do {  
?>
  <option value="<?php echo $row_catalogs['id']?>" <?php if(isset($_GET['id']) && isset($row_product['catalog_id']) && $row_catalogs['id']==$row_product['catalog_id']){ ?>selected<?php } ?>><?php echo $row_catalogs['name']?></option>
  <?php
} while ($row_catalogs = mysql_fetch_assoc($catalogs));
  $rows = mysql_num_rows($catalogs);
  if($rows > 0) {
      mysql_data_seek($catalogs, 0);
	  $row_catalogs = mysql_fetch_assoc($catalogs);
  }
?>
</select>