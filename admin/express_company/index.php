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
$doc_url="logistics.html#list";
$support_email_question="查看快递公司列表";
log_admin($support_email_question);

$query_expresses = "SELECT * FROM express_company";
$expresses = mysqli_query($localhost,$query_expresses);
if(!$expresses){$logger->fatal("数据库操作失败:".$query_expresses);}
$row_expresses = mysqli_fetch_assoc($expresses);
$totalRows_expresses = mysqli_num_rows($expresses);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">快递公司</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php"></a>
<table width="100%" border="1" align="center" class="phpshop123_list_box">
  <tr>
    <td><div align="center">ID</div></td>
    <td>代码</td>
    <td>名称</td>
    <td>激活</td>
    <td>顺序</td>
    <td>网站</td>
    <td>操作</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><div align="center"><?php echo $row_expresses['id']; ?>&nbsp; </div></td>
      <td><?php echo $row_expresses['code']; ?>&nbsp; </td>
      <td><a href="detail.php?recordID=<?php echo $row_expresses['id']; ?>"> <?php echo $row_expresses['name']; ?>&nbsp; </a> </td>
      <td><?php echo $row_expresses['disabled']=="false"?"√":""; ?>&nbsp; </td>
      <td><?php echo $row_expresses['ordernum']; ?>&nbsp; </td>
      <td><?php echo $row_expresses['website']; ?>&nbsp; </td>
      <td><?php if($row_expresses['disabled']=="false"){?><a href="deactivate.php?id=<?php echo $row_expresses['id']; ?>">停用</a><?php  } ?> <?php if($row_expresses['disabled']=="true"){?><a href="activate.php?id=<?php echo $row_expresses['id']; ?>">激活</a><?php  } ?> <a href="update.php?id=<?php echo $row_expresses['id']; ?>"> 更新</a></td>
    </tr>
    <?php } while ($row_expresses = mysqli_fetch_assoc($expresses)); ?>
</table>
<br>
记录总数 ：<?php echo $totalRows_expresses ?>
</body>
</html>