<?php require_once('../../../Connections/localhost.php'); ?>
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
$goods = mysql_query($query_limit_goods, $localhost) or die(mysql_error());
$row_goods = mysql_fetch_assoc($goods);

if (isset($_GET['totalRows_goods'])) {
  $totalRows_goods = $_GET['totalRows_goods'];
} else {
  $all_goods = mysql_query($query_goods);
  $totalRows_goods = mysql_num_rows($all_goods);
}
$totalPages_goods = ceil($totalRows_goods/$maxRows_goods)-1;
?>
<link href="../../../css/common_admin.css" rel="stylesheet" type="text/css">

<table width="960" border="1" class="phpshop123_list_box">
  <?php do { ?> <tr>
   
      <td height="24"><label>
        <input type="checkbox" name="checkbox" value="checkbox">
          </label></td>
      <td><?php echo $row_goods['name']; ?></td>
      </tr><?php } while ($row_goods = mysql_fetch_assoc($goods)); ?>
</table>
<?php
mysql_free_result($goods);
?>