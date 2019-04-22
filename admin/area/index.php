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
$doc_url="areas.html";
$support_email_question="查看区域列表";
log_admin($support_email_question);
$colname_areas = "0";
if (isset($_GET['pid'])) {
  $colname_areas = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}

$query_areas = sprintf("SELECT * FROM area WHERE pid = %s", $colname_areas);
$areas = mysqli_query($localhost,$query_areas);
if(!$areas){$logger->fatal("数据库操作失败:".$query_areas);}
$row_areas = mysqli_fetch_assoc($areas);
$totalRows_areas = mysql_num_rows($areas);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">区域列表</span>
<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td><p>ID</p>    </td>
    <td>名称</td>
    <td>深度</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_areas['id']; ?>&nbsp; </td>
      <td><a href="index.php?pid=<?php echo $row_areas['id']; ?>"> <?php echo $row_areas['name']; ?>&nbsp; </a> </td>
      <td><?php echo $row_areas['level_depth']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_areas = mysqli_fetch_assoc($areas)); ?>
</table>
<br>
<?php echo $totalRows_areas ?> 记录 总数
</p>
</body>
</html>