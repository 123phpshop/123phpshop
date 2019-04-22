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
 ?><?php require_once('../../Connections/localhost.php');
$doc_url="product.html#search";
$support_email_question="搜索商品"; log_admin($support_email_question);

// 处理批量操作
 if ((isset($_POST["form_op"])) && ($_POST["form_op"] == "batch_op")) {
	if(count($_POST['product_id'])>0){
			$sql="";
			switch($_POST['op_id']){
			
				case "100":// 删除
					$sql="update `product` set is_delete=1 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "200": // 上架
					$on_sheft_time=date('Y-m-d H:i:s');
					$sql="update `product` set is_on_sheft=1 , on_sheft_time='".$on_sheft_time."' where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "300": // 下架
					$sql="update `product` set is_on_sheft=0 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "400":	//设置为热销
					$sql="update `product` set is_hot=1 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "500": //取消热销
					$sql="update `product` set is_hot=0 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "600":	//设置为推荐
					$sql="update `product` set is_recommanded=1 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "700": //取消推荐
					$sql="update `product` set is_recommanded=0 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "800":	//设置为当季商品
					$sql="update `product` set is_season=1 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				case "900": //取消当季商品
					$sql="update `product` set is_season=0 where id in (".implode(",",$_POST['product_id']).")";
				break;
				
				
				
 			}	
			
			if($sql!=""){
				
				$Result1=mysqli_query($localhost,$sql);
				if(!$Result1){$logger->fatal("数据库操作失败:".$sql);}
			}
  	}
}
$currentPage = $_SERVER["PHP_SELF"];
$where="";
$maxRows_products = 50;
$pageNum_products = 0;
if (isset($_GET['pageNum_products'])) {
  $pageNum_products = $_GET['pageNum_products'];
}
$startRow_products = $pageNum_products * $maxRows_products;

$colname_products = "-1";
$where=_get_product_where($_GET);
if (isset($_GET['catalog_id'])) {
  $colname_products = (get_magic_quotes_gpc()) ? $_GET['catalog_id'] : addslashes($_GET['catalog_id']);
  $where.=" and catalog_id = ".$colname_products;
}

// 选择所有未被删除的商品

$query_products = "SELECT * FROM product WHERE is_delete=0 $where order by id desc";
$query_limit_products = sprintf("%s LIMIT %d, %d", $query_products, $startRow_products, $maxRows_products);
$products = mysqli_query($localhost,$query_limit_products);
if(!$products){$logger->fatal("数据库操作失败:".$query_limit_products);}
$row_products = mysqli_fetch_assoc($products);

if (isset($_GET['totalRows_products'])) {
  $totalRows_products = $_GET['totalRows_products'];
} else {
  $all_products = mysqli_query($localhost,$query_products);
  $totalRows_products = mysqli_num_rows($all_products);
}
$totalPages_products = ceil($totalRows_products/$maxRows_products)-1;

$queryString_products = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_products") == false && 
        stristr($param, "totalRows_products") == false) {
      	array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_products = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_products = sprintf("&totalRows_products=%d%s", $totalRows_products, $queryString_products);


function _get_product_where($get){
	
	 
	$_should_and=1;	
	$where_string='';
	
	
	if(isset($get['name']) && trim($get['name'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
			
		$where_string.=" name like'%".trim($get['name'])."%'";
	}
	
	if(isset($get['is_on_sheft']) && trim($get['is_on_sheft'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		
		$where_string.=" is_on_sheft='".trim($get['is_on_sheft'])."'";
	}
	
	if(isset($get['is_hot']) && trim($get['is_hot'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		
		$where_string.=" is_hot='".trim($get['is_hot'])."'";
	}
	
	if(isset($get['is_recommanded']) && trim($get['is_recommanded'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		
		$where_string.=" is_recommanded='".trim($get['is_recommanded'])."'";
	}
	
	
	if( isset($get['on_sheft_from']) && trim($get['on_sheft_from'])!='' && isset($get['on_sheft_end']) && trim($get['on_sheft_end'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		
		$where_string.=" on_sheft_time between '".trim($get['on_sheft_from']). "' and '" .trim($get['on_sheft_end']) ."  23:59:59'";
	}
	
 	
	if(isset($get['price_from']) && trim($get['price_from'])!='' && isset($get['price_end']) && trim($get['price_end'])!=''){
		
  		if($_should_and==1){
			
			$where_string.=" and ";
		}
		
		$where_string.=" price between '".trim($get['price_from']). "' and '" .trim($get['price_end'])."'" ;
	}
 	return $where_string;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>123phpshop-商品列表</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">商品搜索</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<label>
<a href="add.php"><input style="float:right;" type="submit" name="Submit2" value="添加商品" /></a>
</label>
<form id="product_search" name="product_search" method="get" action="">
  <table width="100%" border="0" class="phpshop123_search_box">
    <tr>
      <td width="5%" scope="col">名称：</td>
      <td width="27%" scope="col"><input value="<?php echo isset($_GET['name'])?$_GET['name']:''; ?>" name="name" type="text" id="name" maxlength="50" /></td>
      <td width="3%" scope="col">热销：</td>
      <td width="26%" scope="col"><input name="is_hot"  type="radio" value="1" checked="checked" <?php if(isset($_GET['is_hot']) && $_GET['is_hot']=='1'){ ?>checked<?php } ?> />
        是
          <input type="radio" name="is_hot" value="0"  <?php if(isset($_GET['is_hot']) && $_GET['is_hot']=='0'){ ?>checked<?php } ?> />
      否</td>
      <td width="8%" scope="col">价格区间</td>
      <td width="12%" scope="col"><input value="<?php echo isset($_GET['price_from'])?$_GET['price_from']:''; ?>" name="price_from" type="text" id="price_from" /></td>
      <td width="19%" scope="col"><input value="<?php echo isset($_GET['price_end'])?$_GET['price_end']:''; ?>" name="price_end" type="text" id="price_end" /></td>
    </tr>
    <tr>
      <td><label> 上架 ： </label></td>
      <td><input name="is_on_sheft" type="radio" id="is_on_sheft" value="1" checked="checked" <?php if(isset($_GET['is_on_sheft']) && $_GET['is_on_sheft']=='1'){ ?>checked<?php } ?> />
      是<input type="radio" name="is_on_sheft"  id="is_on_sheft"  value="0" <?php if(isset($_GET['is_on_sheft']) && $_GET['is_on_sheft']=='0'){ ?>checked<?php } ?> />
      否</td>
      <td>推荐:</td>
      <td><input name="is_recommanded" type="radio" value="1" checked="checked" <?php if(isset($_GET['is_recommanded']) && $_GET['is_recommanded']=='1'){ ?>checked<?php } ?> />
        是
          <input type="radio" name="is_recommanded" value="0" <?php if(isset($_GET['is_recommanded']) && $_GET['is_recommanded']=='0'){ ?>checked<?php } ?> />
      否</td>
      <td>上架时间</td>
      <td><input type="text" name="on_sheft_from" value="<?php echo isset($_GET['on_sheft_from'])?$_GET['on_sheft_from']:''; ?>" id="on_sheft_from" /></td>
      <td><input name="on_sheft_end" type="text" value="<?php echo isset($_GET['on_sheft_end'])?$_GET['on_sheft_end']:''; ?>" id="on_sheft_end" />
      <input type="submit" name="Submit" value="搜索" /></td>
    </tr>
    
  </table>
</form>
<?php 
$doc_url="product.html#list";
$support_email_question="查看商品列表"; 

if ($totalRows_products > 0) { // Show if recordset not empty ?>
       <form id="batch_op_form" name="batch_op_form" method="post" action="">

    <span class="phpshop123_title">商品列表</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div><br />
  <br />
    <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" class="phpshop123_list_box">
    <tr>
      <th><label>
        <input type="checkbox" id="select_all" onclick="select_all_item()" value="" />
      </label></th>
      <th>商品名称</th>
      <th>价格</th>
      <th>是否上架</th>
      <th>热销商品</th>
      <th>推荐商品</th>
      <th>库存数量</th>
      <th>操作</th>
      </tr>
    <?php do { ?>
      <tr>
        <td><div align="center">
          <label>
          <input name="product_id[]" type="checkbox" class="item_checkbox" id="product_id[]" value="<?php echo $row_products['id']; ?>" />
          </label>
          &nbsp;  </div></td>
        <td><a href="update.php?id=<?php echo $row_products['id']; ?>"><?php echo $row_products['name']; ?>&nbsp;</a> </td>
        <td>￥<?php echo $row_products['price']; ?>&nbsp; </td>
        <td><div align="center"><?php echo $row_products['is_on_sheft']=='1'?"√":""; ?>&nbsp; </div></td>
        <td><div align="center"><?php echo $row_products['is_hot']=='1'?"√":""; ?>&nbsp; </div></td>
        <td><div align="center"><?php echo $row_products['is_recommanded']=='1'?"√":""; ?>&nbsp; </div></td>
        <td><?php echo $row_products['store_num']; ?></td>
        <td><div align="right"><a onclick="return confirm('您确认要删除这条记录吗？')" href="remove.php?id=<?php echo $row_products['id']; ?>">删除 </a><a href="update.php?id=<?php echo $row_products['id']; ?>">编辑  </a> <a href="/product.php?id=<?php echo $row_products['id']; ?>" target="_blank">前台浏览</a></div></td>
      </tr>
      <?php } while ($row_products = mysqli_fetch_assoc($products)); ?>
  </table>
    <br />
      <table width="200" border="0" class="phpshop123_infobox">
        <tr>
          <td width="5%"><label>
            <select name="op_id" id="op_id">
              <option value="0">请选择操作..</option>
              <option value="100">放入回收站</option>
              <option value="200">上架</option>
              <option value="300">下架</option>
              <option value="400">设为热销</option>
              <option value="500">取消热销</option>
			  <option value="600">设为推荐</option>
              <option value="700">取消推荐</option>
			  <option value="800">设为当季</option>
              <option value="900">取消当季</option>
            </select>
          </label></td>
          <td width="95%"><label>
            <input type="submit" name="Submit3" value="确定" />
			<input type="hidden" value="batch_op" name="form_op"  />
          </label></td>
        </tr>
      </table>
  </form>
  <br>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_products > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, 0, $queryString_products); ?>" class="phpshop123_paging">第一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_products > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, max(0, $pageNum_products - 1), $queryString_products); ?>" class="phpshop123_paging">前一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_products < $totalPages_products) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, min($totalPages_products, $pageNum_products + 1), $queryString_products); ?>" class="phpshop123_paging">下一页</a>
            <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_products < $totalPages_products) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, $totalPages_products, $queryString_products); ?>" class="phpshop123_paging">最后一页</a>
            <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  记录 <?php echo ($startRow_products + 1) ?> 到 <?php echo min($startRow_products + $maxRows_products, $totalRows_products) ?> (总共 <?php echo $totalRows_products ?> )
  <?php } // Show if recordset not empty ?>	


  <p>&nbsp;</p>
  <?php if ($totalRows_products == 0) { // Show if recordset empty ?>
  <p class="phpshop123_infobox">没有记录，欢迎添加。</p>
  <?php } // Show if recordset empty ?>
</body>
	<link rel="stylesheet" href="../../js/jquery-ui-1.11.4.custom/jquery-ui.min.css">
	<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
	<script>
	 $(function() {
		$( "#on_sheft_from" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$( "#on_sheft_end" ).datepicker({ dateFormat: 'yy-mm-dd' });
   });
   
	function select_all_item(){
     	if($("#select_all").attr("checked")=="checked"){
			$(".item_checkbox").attr("checked","checked");
			return;
		}
		$(".item_checkbox").removeAttr("checked");
   }
	</script>
</html>