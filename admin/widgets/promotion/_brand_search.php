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
$maxRows_goods = 10;
$pageNum_goods = 0;
if (isset($_GET['pageNum_goods'])) {
  $pageNum_goods = $_GET['pageNum_goods'];
}
$startRow_goods = $pageNum_goods * $maxRows_goods;

$colname_goods = "-1";
if (isset($_GET['name'])) {
  $colname_goods = (get_magic_quotes_gpc()) ? $_GET['name'] : addslashes($_GET['name']);
}
mysql_select_db($database_localhost, $localhost);
$query_goods = "SELECT id,name FROM brands WHERE name like '%".$colname_goods."%' and is_delete=0";
$query_limit_goods = sprintf("%s LIMIT %d, %d", $query_goods, $startRow_goods, $maxRows_goods);
$goods = mysqli_query($localhost,$query_limit_goods);
if(!$goods){$logger->fatal("数据库操作失败:".$query_limit_goods);}
$row_goods = mysqli_fetch_assoc($goods);
$row_goods_num = mysql_num_rows($goods);
if (isset($_GET['totalRows_goods'])) {
  $totalRows_goods = $_GET['totalRows_goods'];
} else {
  $all_goods = mysqli_query($localhost,$query_goods);
  $totalRows_goods = mysql_num_rows($all_goods);
}
$totalPages_goods = ceil($totalRows_goods/$maxRows_goods)-1;
?>
<?php if($row_goods_num>0){ ?>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css">

<table width="960" border="1" class="phpshop123_list_box">
  <?php do { ?> <tr>
       <td height="24"><label>
        <input name="promotion_limit_value[]" type="checkbox" id="promotion_limit_value" value="<?php echo $row_goods['id']; ?>">
          </label></td>
      <td><?php echo $row_goods['name']; ?></td>
      </tr><?php } while ($row_goods = mysqli_fetch_assoc($goods)); ?>
</table>
<?php
}
?>