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
$doc_url="user.html#list";
$support_email_question="查看用户列表";
$currentPage = $_SERVER["PHP_SELF"];

// 处理批量操作
 if ((isset($_POST["form_op"])) && ($_POST["form_op"] == "batch_op")) {
	if(count($_POST['user_id'])>0 && $_POST['op_id']=="100"){	
			mysql_select_db($database_localhost, $localhost);
			$sql="update `user` set is_delete=1 where id in (".implode(",",$_POST['user_id']).")";
			mysql_query($sql, $localhost) or die(mysql_error());
 	}
}

$maxRows_users = 50;
$pageNum_users = 0;
if (isset($_GET['pageNum_users'])) {
  $pageNum_users = $_GET['pageNum_users'];
}
$startRow_users = $pageNum_users * $maxRows_users;
$where=_get_user_where($_GET);

mysql_select_db($database_localhost, $localhost);
$query_users = "SELECT * FROM `user` where is_delete=0 $where";
$query_limit_users = sprintf("%s LIMIT %d, %d", $query_users, $startRow_users, $maxRows_users);
$users = mysql_query($query_limit_users, $localhost) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);

if (isset($_GET['totalRows_users'])) {
  $totalRows_users = $_GET['totalRows_users'];
} else {
  $all_users = mysql_query($query_users);
  $totalRows_users = mysql_num_rows($all_users);
}
$totalPages_users = ceil($totalRows_users/$maxRows_users)-1;

$queryString_users = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_users") == false && 
        stristr($param, "totalRows_users") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_users = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_users = sprintf("&totalRows_users=%d%s", $totalRows_users, $queryString_users);

function _get_user_where($get){
	
	 
	$_should_and=1;	
	$where_string='';
	
	if(isset($get['username']) && trim($get['username'])!=''){
		 
		$_should_and=1;	
 		$where_string.=" and username like '%".$get['username']."%'";
	}
	
	if(isset($get['mobile']) && trim($get['mobile'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		
		$where_string.=" mobile like '%".$get['mobile']."%'";
	}
	
	if(isset($get['email']) && trim($get['email'])!=''){
		 
		if($_should_and==1){
			$where_string.=" and ";
		}
		
		$where_string.=" email like '%".$get['email']."%'";
	}
	  
 	return $where_string;
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户列表</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_users > 0) { // Show if recordset not empty ?>
  <span class="phpshop123_title">用户搜索</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php"><input style="float:right;" type="submit" name="Submit2" value="添加用户" />
    </a>

  <form id="user_search_form" name="user_search_form" method="get" action="">
    <table width="100%" class="phpshop123_search_box">
      <tr>
        <td>用户名</td>
        <td><input name="username"  value="<?php echo isset($_GET['username'])?$_GET['username']:'';?>"  type="text" id="username" /></td>
        <td>手机</td>
        <td><input name="mobile"  id="mobile" type="text"   value="<?php echo isset($_GET['mobile'])?$_GET['mobile']:'';?>"/></td>
        <td>邮件</td>
        <td><input name="email"  type="text" id="email" value="<?php echo isset($_GET['email'])?$_GET['email']:'';?>"/></td>
        <td><div align="right">
            <input type="submit" name="Submit" value="搜素" />
        </div></td>
      </tr>
    </table>
  </form>
    <form id="form1" name="form1" method="post" action="">
   <span class="phpshop123_title">用户列表</span>
   <div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;">
   <a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<table width="100%" border="1" align="center" class="phpshop123_list_box">
    <tr>
      <th width="4%"><label>
           <input type="checkbox" id="select_all" onClick="select_all_item()" />
        </label></th>
      <th width="34%">账号</th>
      <th width="21%">邮箱</th>
      <th width="23%">手机</th>
      <th width="18%">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><label>
          <div align="center">
            <input name="user_id[]" type="checkbox" class="item_checkbox" id="user_id[]" value="<?php echo $row_users['id']; ?>" />
          </div>
        </label></td>
        <td><a href="detail.php?recordID=<?php echo $row_users['id']; ?>"> <?php echo $row_users['username']; ?> </a> </td>
        <td><?php echo $row_users['email']; ?></td>
        <td><?php echo $row_users['mobile']; ?></td>
        <td><div align="right"><a onclick="return confirm('您确实要删除这条记录吗？')" href="remove.php?id=<?php echo $row_users['id']; ?>">删除</a> <a href="update.php?id=<?php echo $row_users['id']; ?>">更新</a></div></td>
      </tr>
      <?php } while ($row_users = mysql_fetch_assoc($users)); ?>
  </table>
  <br />
  <table width="200" border="0" class="phpshop123_infobox">
    <tr>
      <td width="5%"><label>
        <select name="op_id" id="op_id">
          <option value="0">请选择操作..</option>
          <option value="100">删除用户</option>
                </select>
      </label></td>
      <td width="95%"><label>
        <input type="submit" name="Submit3" value="确定" />
        <input type="hidden" value="batch_op" name="form_op" />
      </label></td>
    </tr>
  </table>
  <br>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_users > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, 0, $queryString_users); ?>" class="phpshop123_paging">第一页</a>
      <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_users > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, max(0, $pageNum_users - 1), $queryString_users); ?>" class="phpshop123_paging">前一页</a>
      <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_users < $totalPages_users) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, min($totalPages_users, $pageNum_users + 1), $queryString_users); ?>" class="phpshop123_paging">下一页</a>
      <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_users < $totalPages_users) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, $totalPages_users, $queryString_users); ?>" class="phpshop123_paging">最后一页</a>
      <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  <br />
  记录 <?php echo ($startRow_users + 1) ?> 到 <?php echo min($startRow_users + $maxRows_users, $totalRows_users) ?> (总共 <?php echo $totalRows_users ?> )
  
    </form>

  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_users == 0) { // Show if recordset not empty ?>
    <p>&nbsp;</p>
    <div class="phpshop123_infobox">没有记录，马上<a href="add.php">添加用户</a>吧！</div>
    <?php } // Show if recordset not empty ?>
	 <script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
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
