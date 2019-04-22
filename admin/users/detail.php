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
 ?><?php require_once('../../Connections/localhost.php'); ?><?php
$doc_url="user.html#detail";
$support_email_question="查看用户详情";log_admin($support_email_question);
$maxRows_DetailRS1 = 50;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;


$recordID = $_GET['recordID'];
$query_DetailRS1 = "SELECT * FROM `user` WHERE id = $recordID";
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysqli_query($localhost,$query_limit_DetailRS1);
if(!$DetailRS1){$logger->fatal("数据库操作失败:".$query_limit_DetailRS1);}
$row_DetailRS1 = mysqli_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysqli_query($localhost,$query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
		
<span class="phpshop123_title">用户详细</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php">
<input style="float:right;" type="submit" name="Submit2" value="订单列表" />
</a>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="phpshop123_form_box">
  
  <tr>
    <td>ID</td>
    <td><?php echo $row_DetailRS1['id']; ?> </td>
  </tr>
  <tr>
    <td>账户</td>
    <td><?php echo $row_DetailRS1['username']; ?> </td>
  </tr>
  <tr>
    <td>Email</td>
    <td><?php echo $row_DetailRS1['email']; ?> </td>
  </tr>
  <tr>
    <td>手机号码</td>
    <td><?php echo $row_DetailRS1['mobile']; ?> </td>
  </tr>
  <tr>
    <td>手机是否验证</td>
    <td><?php echo "未"; ?> </td>
  </tr>
  <tr>
    <td>短信验证码</td>
    <td>无</td>
  </tr>
  <tr>
    <td>注册时间</td>
    <td><?php echo $row_DetailRS1['register_at']; ?> </td>
  </tr>
  <tr>
    <td>最后一次登录时间</td>
    <td><?php echo $row_DetailRS1['last_login_at']; ?> </td>
  </tr>
</table>

</body>
</html><?php
mysql_free_result($DetailRS1);
?>