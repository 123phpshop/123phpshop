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
$doc_url="role.html#list";
$support_email_question="查看角色列表";log_admin($support_email_question);
mysql_select_db($database_localhost, $localhost);
$query_getRoles = "SELECT * FROM `role` where is_delete=0 ORDER BY id DESC";
$getRoles = mysql_query($query_getRoles, $localhost) ;
if(!$getRoles){$logger->fatal("数据库操作失败:".$query_getRoles);}
$row_getRoles = mysql_fetch_assoc($getRoles);
$totalRows_getRoles = mysql_num_rows($getRoles);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">角色列表</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<?php if ($totalRows_getRoles > 0) { // Show if recordset not empty ?>
  <a href="add.php">
  <input style="float:right;" type="submit" name="Submit2" value="添加角色" />
  </a>
  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">角色名称</th>
      <th scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_getRoles['id']; ?></td>
        <td><?php echo $row_getRoles['name']; ?></td>
        <td><a href="remove.php?id=<?php echo $row_getRoles['id']; ?>" onclick="return confirm('您确认要删除这个角色吗？');">删除</a> <a href="edit.php?id=<?php echo $row_getRoles['id']; ?>">更新</a> <a href="assign.php?id=<?php echo $row_getRoles['id']; ?>">权限</a></td>
      </tr>
      <?php } while ($row_getRoles = mysql_fetch_assoc($getRoles)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_getRoles == 0) { // Show if recordset empty ?>
  <p class="phpshop123_infobox">还没有角色，<a href="add.php">点击这里</a>添加 ! </p>
  <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($getRoles);
?>