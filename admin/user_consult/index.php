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
<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="ad.html#list";
$support_email_question="广告列表";
$currentPage = $_SERVER["PHP_SELF"];
$where_query_string=_get_consult_where_query_string();
$maxRows_consult = 50;
$pageNum_consult = 0;
if (isset($_GET['pageNum_consult'])) {
  $pageNum_consult = $_GET['pageNum_consult'];
}

$startRow_consult = $pageNum_consult * $maxRows_consult;

mysql_select_db($database_localhost, $localhost);
$query_consult = "SELECT * FROM product_consult where is_delete=0 and to_question=0 $where_query_string ORDER BY id DESC";
$query_limit_consult = sprintf("%s LIMIT %d, %d", $query_consult, $startRow_consult, $maxRows_consult);
$consult = mysql_query($query_limit_consult, $localhost) or die(mysql_error());
$row_consult = mysql_fetch_assoc($consult);

if (isset($_GET['totalRows_consult'])) {
  $totalRows_consult = $_GET['totalRows_consult'];
} else {
  $all_consult = mysql_query($query_consult);
  $totalRows_consult = mysql_num_rows($all_consult);
}
$totalPages_consult = ceil($totalRows_consult/$maxRows_consult)-1;

$queryString_consult = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_consult") == false && 
        stristr($param, "totalRows_consult") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_consult = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_consult = sprintf("&totalRows_consult=%d%s", $totalRows_consult, $queryString_consult);

function _get_consult_where_query_string(){
	
	$result="";
	
	if(isset($_GET['content']) && trim($_GET['content'])!=''){
		$result.=" and content like '%".$_GET['content']."%'";
	}
	
	if(isset($_GET['is_replied']) && trim($_GET['is_replied'])!='' ){
	 $result.=" and is_replied = '".$_GET['is_replied']."'";
	}
	
	if(isset($_GET['create_from']) && trim($_GET['create_from'])!='' && isset($_GET['create_end']) && trim($_GET['create_end'])!=''  ){
		$result.=" and create_time between '".$_GET['create_from']."' and '".$_GET['create_end']."'";
	}
		
	return $result;
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">搜索咨询</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<form id="consult_search" name="consult_search" method="get" action="">
  <table width="100%" border="0" class="phpshop123_search_box">
    <tr>
      <td>咨询内容</td>
      <td><input name="content" type="text" id="content" value="<?php if(isset($_GET['content']) && trim($_GET['content'])!=''){
		echo $_GET['content'];} ?>"/></td>
      <td><label>创建时间</label></td>
      <td><input name="create_from" type="text" id="create_from" value="<?php if(isset($_GET['create_from']) && trim($_GET['create_from'])!=''){
		echo $_GET['create_from'];} ?>"/>
        <input name="create_end" type="text" id="create_end" value="<?php if(isset($_GET['create_end']) && trim($_GET['create_end'])!=''){
		echo $_GET['create_end'];} ?>" />        <label></label></td>
      <td>咨询状态</td>
      <td><select name="is_replied" id="is_replied">
        <option value="0"   <?php if(isset($_GET['is_replied']) && trim($_GET['is_replied'])=='0'){
		echo 'selected';} ?>>未回答</option>
        <option value="1"  <?php if(isset($_GET['is_replied']) && trim($_GET['is_replied'])=='1'){
		echo 'selected';} ?>>已回答</option>
      </select></td>
      <td><input type="submit" name="Submit" value="搜索" /></td>
    </tr>
  </table>
</form>
<p class="phpshop123_title">咨询列表</p>
<?php if ($totalRows_consult > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1" align="center" class="phpshop123_list_box">
    <tr>
      <th>ID</th>
      <th>用户</th>
      <th>内容</th>
      <th>是否回复</th>
      <th>创建时间</th>
      <th>操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="detail.php?recordID=<?php echo $row_consult['content']; ?>"> <?php echo $row_consult['id']; ?>&nbsp; </a> </td>
        <td><?php echo $row_consult['user_id']; ?>&nbsp; </td>
        <td><?php echo $row_consult['content']; ?>&nbsp; </td>
        <td><?php echo $row_consult['is_replied']=="0"?"<span style='color:red'>未</span>":"√"; ?></td>
        <td><?php echo $row_consult['create_time']; ?>&nbsp; </td>
        <td><div align="right"><a onClick="return confirm('你确认要删除这条记录吗？');" href="remove.php?id=<?php echo $row_consult['id']; ?>">删除</a>  <a href="replay.php?id=<?php echo $row_consult['id']; ?>">回答</a></div></td>
      </tr>
      <?php } while ($row_consult = mysql_fetch_assoc($consult)); ?>
  </table>
  <br>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_consult > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_consult=%d%s", $currentPage, 0, $queryString_consult); ?>" class="phpshop123_paging">第一页</a>
            <?php } // Show if not first page ?>
      </td>
      <td width="31%" align="center"><?php if ($pageNum_consult > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_consult=%d%s", $currentPage, max(0, $pageNum_consult - 1), $queryString_consult); ?>" class="phpshop123_paging">前一页</a>
            <?php } // Show if not first page ?>
      </td>
      <td width="23%" align="center"><?php if ($pageNum_consult < $totalPages_consult) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_consult=%d%s", $currentPage, min($totalPages_consult, $pageNum_consult + 1), $queryString_consult); ?>" class="phpshop123_paging">下一页</a>
            <?php } // Show if not last page ?>
      </td>
      <td width="23%" align="center"><?php if ($pageNum_consult < $totalPages_consult) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_consult=%d%s", $currentPage, $totalPages_consult, $queryString_consult); ?>" class="phpshop123_paging">最后一页</a>
            <?php } // Show if not last page ?>
      </td>
    </tr>
  </table>
  记录 <?php echo ($startRow_consult + 1) ?> 到 <?php echo min($startRow_consult + $maxRows_consult, $totalRows_consult) ?> (总共 <?php echo $totalRows_consult ?>)
  </p>
  
  <?php } // Show if recordset not empty ?>	


<?php if ($totalRows_consult == 0) { // Show if recordset empty ?>
  <p class="phpshop123_infobox">暂无咨询！</p>
  <?php } // Show if recordset empty ?>
	<link rel="stylesheet" href="../../js/jquery-ui-1.11.4.custom/jquery-ui.min.css">
	<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
	<script>
	 $(function() {
		$( "#create_from" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$( "#create_end" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	</script>
   </body>
</html>
<?php
mysql_free_result($consult);
?>
