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
$currentPage = $_SERVER["PHP_SELF"];
$doc_url="news.html#list";
$support_email_question="查看文章列表";
$where_string="where is_delete=0 ";


// 处理批量操作
 if ((isset($_POST["form_op"])) && ($_POST["form_op"] == "batch_op") && count($_POST['news_id'])>0 ) {
	$sql="";
	
	switch($_POST['op_id']){
		case "100":
			$sql="update `news` set is_delete=1 where id in (".implode(",",$_POST['news_id']).")";
		break;
		
		case "200":
			$sql="update `news` set is_published=1 where id in (".implode(",",$_POST['news_id']).")";
		break;
		
		case "300":
			$sql="update `news` set is_published=0 where id in (".implode(",",$_POST['news_id']).")";
		break;
	}
  	 
	if($sql!=""){
		mysql_select_db($database_localhost, $localhost);
 		$query=mysql_query($sql, $localhost) ;
		if(!$query){$logger->fatal("数据库操作失败:".$sql);}
 	}			
}
 $colname_catalog = "-1";
if (isset($_GET['catalog_id'])) {
  $colname_catalog = (get_magic_quotes_gpc()) ? $_GET['catalog_id'] : addslashes($_GET['catalog_id']);
  $where_string.="and catalog_id = ".$colname_catalog;
}
$maxRows_catalog = 1;
$pageNum_catalog = 0;
if (isset($_GET['pageNum_catalog'])) {
  $pageNum_catalog = $_GET['pageNum_catalog'];
}
$startRow_catalog = $pageNum_catalog * $maxRows_catalog;

mysql_select_db($database_localhost, $localhost);
$query_catalog = "SELECT * FROM news_catalog WHERE id = $colname_catalog";
$query_limit_catalog = sprintf("%s LIMIT %d, %d", $query_catalog, $startRow_catalog, $maxRows_catalog);
$catalog = mysql_query($query_limit_catalog, $localhost) ;
if(!$catalog){$logger->fatal("数据库操作失败:".$query_limit_catalog);}
$row_catalog = mysql_fetch_assoc($catalog);

if (isset($_GET['totalRows_catalog'])) {
  $totalRows_catalog = $_GET['totalRows_catalog'];
} else {
  $all_catalog = mysql_query($query_catalog);
  $totalRows_catalog = mysql_num_rows($all_catalog);
}
$totalPages_catalog = ceil($totalRows_catalog/$maxRows_catalog)-1;

$queryString_catalog = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_catalog") == false && 
        stristr($param, "totalRows_catalog") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_catalog = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_catalog = sprintf("&totalRows_catalog=%d%s", $totalRows_catalog, $queryString_catalog);
  
 $colname_news = "-1";
if (isset($_GET['catalog_id'])) {
  $colname_news = (get_magic_quotes_gpc()) ? $_GET['catalog_id'] : addslashes($_GET['catalog_id']);
  
  
}
 
$maxRows_news = 50;
$pageNum_news = 0;
if (isset($_GET['pageNum_news'])) {
  $pageNum_news = $_GET['pageNum_news'];
}
$startRow_news = $pageNum_news * $maxRows_news;
  
mysql_select_db($database_localhost, $localhost);	
$query_news = "SELECT * FROM news $where_string order by id desc";
 $query_limit_news = sprintf("%s LIMIT %d, %d", $query_news, $startRow_news, $maxRows_news);
$news = mysql_query($query_limit_news, $localhost) ;
if(!$news){$logger->fatal("数据库操作失败:".$query_limit_news);}
$row_news = mysql_fetch_assoc($news);

if (isset($_GET['totalRows_news'])) {
  $totalRows_news = $_GET['totalRows_news'];
} else {
  $all_news = mysql_query($query_news);
  $totalRows_news = mysql_num_rows($all_news);
}
$totalPages_news = ceil($totalRows_news/$maxRows_news)-1;

$queryString_news = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_news") == false && 
        stristr($param, "totalRows_news") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_news = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_news = sprintf("&totalRows_news=%d%s", $totalRows_news, $queryString_news);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">
  <?php if ($totalRows_catalog > 0) { // Show if recordset not empty ?>
    <?php echo $row_catalog['name']; ?>-&gt;
<?php } // Show if recordset not empty ?> 文章列表</span>

<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="add.php">
<input style="float:right;" type="submit" name="Submit2" value="添加文章" />
</a>
<?php if ($totalRows_news == 0) { // Show if recordset empty ?>
  <p><span class="phpshop123_infobox">现在还没有文章！ 
    <?php if ($totalRows_catalog > 0) { // Show if recordset empty ?>
    <a href="add.php?catalog_id=<?php echo $row_catalog['id']; ?>">欢迎添加！</a></span> 
  <?php } // Show if recordset empty ?>	</p>
  <?php } // Show if recordset empty ?>
   
<?php if ($totalRows_news > 0) { // Show if recordset not empty ?>
  <form id="batch_op_form" name="batch_op_form" method="post" action="">

  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th width="3%" scope="col"><label>
           <input type="checkbox" id="select_all" onClick="select_all_item()" />
      </label></th>
      <th width="59%" scope="col">标题</th>
      <th width="27%" scope="col">发布</th>
      <th width="11%" scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><label>
            <div align="center">
              <input name="news_id[]" type="checkbox"  class="item_checkbox" id="news_id[]" value="<?php echo $row_news['id']; ?>" />
          </div>
        </label></td>
        <td><?php echo $row_news['title']; ?></td>
        <td><div align="center"><?php echo $row_news['is_published']==1?"√":""; ?></div></td>
        <td><div align="center"><a onClick="return confirm('您确定要删除这条记录吗？')" href="remove.php?id=<?php echo $row_news['id']; ?>">删除</a> <a href="update.php?id=<?php echo $row_news['id']; ?>">更新</a></div></td>
      </tr>
      <?php } while ($row_news = mysql_fetch_assoc($news)); ?>
  </table>
  <p>&nbsp;</p>
     <table width="200" border="0" class="phpshop123_infobox">
      <tr>
        <td width="5%"><label>
          <select name="op_id" id="op_id">
            <option value="0">请选择操作..</option>
            <option value="100">放入回收站</option>
			<option value="200">发布文章</option>
			<option value="300">取消发布</option>
          </select>
        </label></td>
        <td width="95%"><label>
          <input type="submit" name="Submit3" value="确定" />
          <input type="hidden" value="batch_op" name="form_op" />
        </label></td>
      </tr>
    </table>
  </form>
  <p>&nbsp;</p>
<p align="right">
 	<?php if ($pageNum_news > 0) { // Show if not first page ?>
	  <a href="<?php printf("%s?pageNum_news=%d%s", $currentPage, 0, $queryString_news); ?>" class="phpshop123_paging">第一页</a> 
      <a href="<?php printf("%s?pageNum_news=%d%s", $currentPage, max(0, $pageNum_news - 1), $queryString_news); ?>" class="phpshop123_paging">前一页</a>
	  <?php } // Show if not first page ?>
    
    <?php if ($pageNum_news < $totalPages_news) { // Show if not last page ?>
    <a href="<?php printf("%s?pageNum_news=%d%s", $currentPage, min($totalPages_news, $pageNum_news + 1), $queryString_news); ?>" class="phpshop123_paging">下一页</a>
	<a  class="phpshop123_paging" href="<?php printf("%s?pageNum_news=%d%s", $currentPage, $totalPages_news, $queryString_news); ?>">最后一页</a>
      <?php } // Show if not last page ?></p>
 	    <?php } // Show if recordset not empty ?>
<script language="JavaScript" type="text/javascript"
	src="/js/jquery-1.7.2.min.js"></script>
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