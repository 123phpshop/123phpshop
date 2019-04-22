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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="product.html#recycled";
$support_email_question="浏览产品回收站";log_admin($support_email_question);

// 处理批量操作
 if ((isset($_POST["form_op"])) && ($_POST["form_op"] == "batch_op")) {

  // 调整参数
	if(count($_POST['product_id'])>0 && $_POST['op_id']=="100"){	
			mysql_select_db($database_localhost, $localhost);
			$sql="update `product` set is_delete=0 where id in (".implode(",",$_POST['product_id']).")";
			$Result1=mysqli_query($localhost,$sql);
			if(!$Result1){$logger->fatal("数据库操作失败:".$sql);}
 	}

}


$maxRows_products = 50;
$pageNum_products = 0;
if (isset($_GET['pageNum_products'])) {
  $pageNum_products = $_GET['pageNum_products'];
}
$startRow_products = $pageNum_products * $maxRows_products;

mysql_select_db($database_localhost, $localhost);
$query_products = "SELECT * FROM product WHERE is_delete = 1";
$query_limit_products = sprintf("%s LIMIT %d, %d", $query_products, $startRow_products, $maxRows_products);
$products = mysqli_query($localhost,$query_limit_products);
if(!$products){$logger->fatal("数据库操作失败:".$query_limit_products);}
$row_products = mysqli_fetch_assoc($products);

if (isset($_GET['totalRows_products'])) {
  $totalRows_products = $_GET['totalRows_products'];
} else {
  $all_products = mysqli_query($localhost,$query_products);
  $totalRows_products = mysql_num_rows($all_products);
}
$totalPages_products = ceil($totalRows_products/$maxRows_products)-1;


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">商品回收站</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div><a href="index.php"><input style="float:right;" type="submit" name="Submit2" value="商品列表" /></a>

<?php if ($totalRows_products == 0) { // Show if recordset empty ?>
    <div class="phpshop123_infobox">回收站中空空如也。</div>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_products > 0) { // Show if recordset not empty ?>
  <form id="batch_op_form" name="batch_op_form" method="post" action="">
   <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th scope="col"><label>
        <input type="checkbox" id="select_all" onclick="select_all_item()"  value="checkbox" />
      </label></th>
      <th scope="col">名称</th>
      <th scope="col">价格</th>
      <th scope="col">市场价</th>
      <th scope="col">库存</th>
      <th scope="col">创建时间</th>
      <th scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><label>
          <div align="center">
            <input name="product_id[]" type="checkbox" class="item_checkbox" id="product_id[]" value="<?php echo $row_products['id']; ?>" />
          </div>
        </label></td>
        <td><?php echo $row_products['name']; ?></td>
        <td><?php echo $row_products['price']; ?></td>
        <td><?php echo $row_products['market_price']; ?></td>
        <td><?php echo $row_products['store_num']; ?></td>
        <td><?php echo $row_products['create_time']; ?></td>
        <td><div align="right"><a onClick="return confirm('您确定要恢复这个产品吗？')" href="unrecycled.php?id=<?php echo $row_products['id']; ?>">恢复</a></div></td>
      </tr>
      <?php } while ($row_products = mysqli_fetch_assoc($products)); ?>
  </table>
  
  
  <br />
    <table width="200" border="0" class="phpshop123_infobox">
      <tr>
        <td width="5%"><label>
          <select name="op_id">
            <option value="0">请选择操作..</option>
            <option value="100">恢复商品</option>
            
          </select>
        </label></td>
        <td width="95%"><label>
          <input type="submit" name="Submit3" value="确定" />
          <input type="hidden" value="batch_op" name="form_op" />
        </label></td>
      </tr>
    </table>
  </form>
  <?php } // Show if recordset not empty ?>
  <script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
 	<script>
     function select_all_item(){
     	if($("#select_all").attr("checked")=="checked"){
			$(".item_checkbox").attr("checked","checked");
			return;
		}
		$(".item_checkbox").removeAttr("checked");
   }
   
	</script>
	
</body>
</html>
<?php
mysql_free_result($products);
?>