<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?><?php

$editFormAction = $_SERVER['PHP_SELF'];
$error="";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_product_images = "-1";
if (isset($_GET['id'])) {
  $colname_product_images = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "product_image_form")) {
	 
 	
	// 我们这里需要对上传文件进行检查
  include($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/upload.php'); 
  
	$up = new fileupload;
	
    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    $up -> set("path", $_SERVER['DOCUMENT_ROOT']."/uploads/product/");
    $up -> set("maxsize", 2000000);
    $up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
    $up -> set("israndname", true);
  
    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
    if($up->upload("image_files")) {
       	$image_files="/uploads/product/".$up->getFileName(); 
	   
		$insertSQL = sprintf("INSERT INTO product_images (product_id, image_files) VALUES (%s, %s)",
					   GetSQLValueString($colname_product_images, "int"),
					   GetSQLValueString($image_files, "text"));
		
		  mysql_select_db($database_localhost, $localhost);
		  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
  	}else {
          //获取上传失败以后的错误提示
        $error=$up->getErrorMsg();
     }
}


mysql_select_db($database_localhost, $localhost);
$query_product_images = sprintf("SELECT * FROM product_images WHERE product_id = %s and is_delete=0 ", $colname_product_images);
$product_images = mysql_query($query_product_images, $localhost) or die(mysql_error());
$row_product_images = mysql_fetch_assoc($product_images);
$totalRows_product_images = mysql_num_rows($product_images);

$maxRows_DetailRS1 = 50;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['catalog_id'])) {
  $colname_DetailRS1 = (get_magic_quotes_gpc()) ? $_GET['catalog_id'] : addslashes($_GET['catalog_id']);
}
mysql_select_db($database_localhost, $localhost);
$recordID = $_GET['id'];
$query_DetailRS1 = sprintf("SELECT product.*,product_type.name as product_type_name, brands.name as brand_name FROM product left join brands on product.brand_id=brands.id  left join product_type on product.product_type_id=product_type.id WHERE product.id = $recordID", $recordID);
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
//	如果找不到这个产品的话，那么直接跳转到index。php
if($totalRows_DetailRS1==0){
	 $updateGoTo = "index.php";
   	 header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
?>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php");?>
<form  method="post" enctype="multipart/form-data" name="product_image_form" class="phpshop123_search_box" id="product_image_form">
  <table width="100%" align="center">
    <tr valign="baseline">
      <td nowrap align="right">图片上传:</td>
      <td><input type="file" name="image_files" value="" size="32">
      <input name="submit" type="submit" value="上传" /></td>
    </tr>
  </table>
  <input type="hidden" name="product_id" value="<?php echo $_GET['recordID']; ?>">
  <input type="hidden" name="MM_insert" value="product_image_form">
</form>

<?php if ($totalRows_product_images > 0) { // Show if recordset not empty ?>
  <p class="phpshop123_title">图片列表</p>
  <table width="100%" border="1" cellpadding="0" cellspacing="0" class="phpshop123_list_box">
    <tr>
      <th scope="col">图片</th>
      <th scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><img src="<?php echo $row_product_images['image_files']; ?>" width="65" height="65" /></td>
        <td> <a href="../product_images/remove.php?id=<?php echo $row_product_images['id']; ?>">删除</a> </td>
      </tr>
      <?php } while ($row_product_images = mysql_fetch_assoc($product_images)); ?>
  </table>
  <?php } // Show if recordset not empty ?> 