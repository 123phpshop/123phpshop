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
$doc_url="payment.html#list";
$support_email_question="查看支付方式列表";log_admin($support_email_question);
mysql_select_db($database_localhost, $localhost);
$query_pay_methods = "SELECT * FROM pay_method ORDER BY is_activated DESC";
$pay_methods = mysqli_query($localhost,$query_pay_methods);
if(!$pay_methods){$logger->fatal("数据库操作失败:".$query_pay_methods);}
$row_pay_methods = mysqli_fetch_assoc($pay_methods);
$totalRows_pay_methods = mysql_num_rows($pay_methods);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" /></head>

<body>
<span class="phpshop123_title">支付方式列表</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php">
<input style="float:right;" type="submit" name="Submit2" value="添加支付方式" />
</a>
<?php if ($totalRows_pay_methods == 0) { // Show if recordset empty ?>
  <p><a href="add.php">添加支付方式</a></p>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_pay_methods > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1" class="phpshop123_list_box">
    <tr>
      <th scope="col">名称</th>
      <th scope="col">官网</th>
      <th scope="col">介绍</th>
      <th scope="col">状态</th>
      <th scope="col">操作</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="<?php echo $row_pay_methods['folder']; ?>"><strong><?php echo $row_pay_methods['name']; ?></strong></a></td>
        <td><a href="<?php echo $row_pay_methods['www']; ?>" target="_blank"><?php echo $row_pay_methods['www']; ?></a></td>
        <td><?php echo $row_pay_methods['intro']; ?></td>
        <td><div align="right"><?php echo $row_pay_methods['is_activated']?"已激活":"未激活"; ?></div></td>
        <td>
		  <div align="right">
		    <?php if($row_pay_methods['is_activated']==0){ ?>
		      <a href="activate.php?id=<?php echo $row_pay_methods['id']; ?>">激活</a>
	        <?php }else{ ?>
		      <a href="deactivate.php?id=<?php echo $row_pay_methods['id']; ?>">停用</a>
	        <?php }?> <a href="update.php?id=<?php echo $row_pay_methods['id']; ?>">编辑</a></div></td>
      </tr>
      <?php } while ($row_pay_methods = mysqli_fetch_assoc($pay_methods)); ?>
	 
	  
	  <tr>
        <td><a href="<?php echo $row_pay_methods['folder']; ?>"><strong>网银支付</strong></a></td>
        <td>http://www.chinabank.com.cn/</td>
        <td>微信支付</td>
        <td><div align="right">[<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1718101117&amp;site=qq&amp;menu=yes" target="_blank">购买</a>]</div></td>
        <td>
		  <div align="right">[点击购买]<a href="update.php?id=<?php echo $row_pay_methods['id']; ?>"></a></div></td>
      </tr>
	   <tr>
        <td><a href="<?php echo $row_pay_methods['folder']; ?>"><strong>微信支付</strong></a></td>
        <td><a href="<?php echo $row_pay_methods['www']; ?>" target="_blank">http://weixin.qq.com/cgi-bin/readtemplate?t=win_weixin</a></td>
        <td>微信支付</td>
        <td><div align="right">[<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1718101117&amp;site=qq&amp;menu=yes" target="_blank">购买</a>]</div></td>
        <td>
		  <div align="right">[点击购买]<a href="update.php?id=<?php echo $row_pay_methods['id']; ?>"></a></div></td>
      </tr>
	   <tr>
        <td><a href="<?php echo $row_pay_methods['folder']; ?>"><strong>贝宝支付</strong></a></td>
        <td>https://www.paypal.com/c2/webapps/mpp/home</td>
        <td>贝宝支付,外贸支付神器</td>
        <td><div align="right">[<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1718101117&amp;site=qq&amp;menu=yes" target="_blank">购买</a>]</div></td>
        <td>
		  <div align="right">[点击购买]<a href="update.php?id=<?php echo $row_pay_methods['id']; ?>"></a></div></td>
      </tr>
  </table>
    <?php } // Show if recordset not empty ?><p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($pay_methods);
?>