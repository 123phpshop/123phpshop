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
$doc_url="news.html#recycled";
$support_email_question="查看文章回收站";

// 处理批量操作
 if ((isset($_POST["form_op"])) && ($_POST["form_op"] == "batch_op")) {
	if(count($_POST['news_id'])>0 && $_POST['op_id']=="100"){	
			mysql_select_db($database_localhost, $localhost);
			$sql="update `news` set is_delete=0 where id in (".implode(",",$_POST['news_id']).")";
			mysql_query($sql, $localhost) or die(mysql_error());
 	}

}


$maxRows_news = 50;
$pageNum_news = 0;
if (isset($_GET['pageNum_news'])) {
  $pageNum_news = $_GET['pageNum_news'];
}
$startRow_news = $pageNum_news * $maxRows_news;

mysql_select_db($database_localhost, $localhost);
$query_news = "SELECT * FROM news WHERE is_delete = 1";
$query_limit_news = sprintf("%s LIMIT %d, %d", $query_news, $startRow_news, $maxRows_news);
$news = mysql_query($query_limit_news, $localhost) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);

if (isset($_GET['totalRows_news'])) {
  $totalRows_news = $_GET['totalRows_news'];
} else {
  $all_news = mysql_query($query_news);
  $totalRows_news = mysql_num_rows($all_news);
}
$totalPages_news = ceil($totalRows_news/$maxRows_news)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">文章回收站</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
  <a href="index.php"><input style="float:right;" type="submit" name="Submit2" value="文章列表" />
  </a>

  <?php if ($totalRows_news == 0) { // Show if recordset empty ?>
    <div class="phpshop123_infobox"><p>回收站中空空如也！</p></div>
    <?php } // Show if recordset empty ?>

<?php if ($totalRows_news > 0) { // Show if recordset not empty ?>
  <form id="batch_op_form" name="batch_op_form" method="post" action="">

  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th width="4%" scope="col"><label>
           <input type="checkbox" id="select_all" onClick="select_all_item()" />
         </label></th>
      <th width="79%" scope="col">标题</th>
      <th width="7%" scope="col">创建时间</th>
      <th width="10%" scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td scope="col"><label>
            <div align="center">
              <input name="news_id[]" type="checkbox"  class="item_checkbox" id="news_id[]" value="<?php echo $row_news['id']; ?>" />
          </div>
        </label></td>
        <td scope="col"><?php echo $row_news['title']; ?></td>
        <td scope="col"><?php echo $row_news['create_time']; ?></td>
        <td scope="col"><div align="right"><a onclick="return confirm('您确实要恢复这条记录吗？')" href="unrecycle.php?id=<?php echo $row_news['id']; ?>">恢复</a></div></td>
      </tr>
      <?php } while ($row_news = mysql_fetch_assoc($news)); ?>
  </table>
  <br />
  <table width="200" border="0" class="phpshop123_infobox">
    <tr>
      <td width="5%"><label>
        <select name="op_id" id="op_id">
          <option value="0">请选择操作..</option>
          <option value="100">恢复文章</option>
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
