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
$doc_url="shipping.html#list";
$support_email_question="查看送货方式列表";log_admin($support_email_question);

$query_shipping_methods = "SELECT * FROM shipping_method where is_delete=0";
$shipping_methods = mysqli_query($localhost,$query_shipping_methods);
if(!$shipping_methods){$logger->fatal("数据库操作失败:".$query_shipping_methods);}
$row_shipping_methods = mysqli_fetch_assoc($shipping_methods);
$totalRows_shipping_methods = mysqli_num_rows($shipping_methods);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">配送方式列表</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="add.php">
<input style="float:right;" type="submit" name="Submit2" value="添加配送方式" />
</a>
<table width="100%" border="0" align="center" class="phpshop123_list_box">
  <tr>
    <td>名称</td>
    <td>介绍</td>
    <td><div align="center">是否被激活</div></td>
    <td>操作</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><label></label>
      <a href="detail.php?recordID=<?php echo $row_shipping_methods['id']; ?>"> <?php echo $row_shipping_methods['name']; ?>&nbsp; </a> </td>
      <td><?php echo $row_shipping_methods['desc']; ?>&nbsp; </td>
      <td><div align="center"><?php echo $row_shipping_methods['is_activated']==1?"√":""; ?>&nbsp; </div></td>
      <td><?php if( $row_shipping_methods['is_activated']==0){ ?><a href="activate.php?id=<?php echo $row_shipping_methods['id']; ?>">激活</a><?php }else{ ?><a href="deactivate.php?id=<?php echo $row_shipping_methods['id']; ?>" onclick="return confirm('您确实要卸载这中配送方式吗？')">卸载</a><?php } ?> <a href="/admin/shipping_method_area/<?php echo $row_shipping_methods['config_file_path']; ?>/add.php">添加配送区域</a> <a href="../shipping_method_area/index.php?shipping_method_id=<?php echo $row_shipping_methods['id']; ?>">配送区域</a> <a href="update.php?id=<?php echo $row_shipping_methods['id']; ?>">编辑</a> <a href="remove.php?id=<?php echo $row_shipping_methods['id']; ?>" onclick="return confirm('您确实要删除这条记录吗？')">删除</a></td>
    </tr>
    <?php } while ($row_shipping_methods = mysqli_fetch_assoc($shipping_methods)); ?>
</table>
<div align="right"><br>
  记录总数：<?php echo $totalRows_shipping_methods ?></p>
</div>
</body>
</html>