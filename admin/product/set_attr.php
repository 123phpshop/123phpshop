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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="product.html#set_attr";
$support_email_question="设置商品属性";log_admin($support_email_question);
//	准备参数
$colname_product = "-1";
if (isset($_GET['product_id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_GET['product_id'] : addslashes($_GET['product_id']);
}


//	如果需要插入的话
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	foreach($_POST as $key=>$value){
		if($key!='Submit' && $key!='MM_insert'  ){
			$sql="insert into product_type_attr_val(product_id,product_type_attr_id,product_type_attr_value)values('".$colname_product."','".str_replace("attr_","",$key)."','".$value."')";
 			$query=mysql_query($sql);
			if(!$query){$logger->fatal("数据库操作失败:".$sql);}

 		}
	}
}

//如果需要更新的话
if ((isset($_POST["MM_Update"])) && ($_POST["MM_Update"] == "form1")) {
	foreach($_POST as $key=>$value){
		if($key!='Submit' && $key!='MM_insert'  ){
			$sql="update product_type_attr_val set product_type_attr_value='".$value."' where product_id='".$colname_product."' and product_type_attr_id='".str_replace("attr_","",$key)."'";
 			$query=mysql_query($sql);
			if(!$query){$logger->fatal("数据库操作失败:".$sql);}
 		}
	}
	
	$insertGoTo = "index.php";
   header(sprintf("Location: %s", $insertGoTo));
  
}



//	获取这个产品的类型id
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT id, name, product_type_id FROM product WHERE id = %s", $colname_product);
$product = mysql_query($query_product, $localhost) ;
if(!$product){$logger->fatal("数据库操作失败:".$query_product);}
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);


// 根据类型的id获取相关的属性
mysql_select_db($database_localhost, $localhost);
$query_product_type_attrs = "SELECT * FROM product_type_attr WHERE product_type_id = ".$row_product['product_type_id']." and is_delete=0 and input_method!=2";
$product_type_attrs = mysql_query($query_product_type_attrs, $localhost) ;
if(!$product_type_attrs){$logger->fatal("数据库操作失败:".$query_product_type_attrs);}
$row_product_type_attrs = mysql_fetch_assoc($product_type_attrs);
$totalRows_product_type_attrs = mysql_num_rows($product_type_attrs);

if($totalRows_product_type_attrs>0){
	// 获取这个产品的所有的属性值，如果可以获取相关记录的话，那么进行更新，如果没有记录的话，那么直接插入。
	$colname_get_product_attr_val = "-1";
	if (isset($_GET['product_id'])) {
	  $colname_get_product_attr_val = (get_magic_quotes_gpc()) ? $_GET['product_id'] : addslashes($_GET['product_id']);
	}
	mysql_select_db($database_localhost, $localhost);
	$query_get_product_attr_val = sprintf("SELECT * FROM product_type_attr_val WHERE product_id = %s and product_type_attr_id=%s", $colname_get_product_attr_val,$row_product_type_attrs['id']);
	$get_product_attr_val = mysql_query($query_get_product_attr_val, $localhost) ;
	if(!$get_product_attr_val){$logger->fatal("数据库操作失败:".$query_get_product_attr_val);}
	$row_get_product_attr_val = mysql_fetch_assoc($get_product_attr_val);
	$totalRows_get_product_attr_val = mysql_num_rows($get_product_attr_val);
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title"><?php echo $row_product['name']; ?>:商品属性设置</span>
<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<?php if($totalRows_product_type_attrs==0){ ?>
<a href="index.php">
<input style="float:right;" type="submit" name="Submit2" value="商品详细" />
</a>
<p class="phpshop123_infobox">还没有设置属性，请到相关产品类型页面设置属性</p><?php } else{?>
<form id="form1" name="form1" method="post" action="">
     <table width="960" border="0" class="phpshop123_form_box">
	  <?php do { ?>
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
			$get_product_attr_val = mysql_query($query_get_product_attr_val, $localhost) ;
			if(!$get_product_attr_val){$logger->fatal("数据库操作失败:".$query_get_product_attr_val);}
			$row_get_product_attr_val = mysql_fetch_assoc($get_product_attr_val);
			$totalRows_get_product_attr_val = mysql_num_rows($get_product_attr_val);
			
			?>
		<?php if($row_product_type_attrs['input_method']==1 && $row_product_type_attrs['is_selectable']==1){ ?>
          <input type="text" name="attr_<?php echo $row_product_type_attrs['id']; ?>" value="<?php echo $row_get_product_attr_val['product_type_attr_value'];?>"/>
		  <?php }elseif($row_product_type_attrs['input_method']==1 && $row_product_type_attrs['is_selectable']==2){ ?> <textarea type="text" cols="50" rows="5" name="attr_<?php echo $row_product_type_attrs['id']; ?>" /><?php echo $row_get_product_attr_val['product_type_attr_value'];?></textarea>
		  	
		  <?php  } ?>
         </td>
      </tr>
	      <?php } while ($row_product_type_attrs = mysql_fetch_assoc($product_type_attrs)); ?>
   </table>
     
  <div align="left">
  	
    <input type="submit" name="Submit" value="设置" />
  </div>
	 <?php if($totalRows_get_product_attr_val>0){ ?>
	 <input value="form1" name="MM_Update" type="hidden" />
 		<?php }else{ ?>
  	<input value="form1" name="MM_insert" type="hidden" />
 <?php } ?>
</form>
 <?php } ?>
</body>
</html>