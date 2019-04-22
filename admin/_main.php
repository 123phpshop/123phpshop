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
 ?><?php require_once('../Connections/localhost.php'); ?>
<?php
log_admin("查看控制面板");
$doc_url="dash.html";
$support_email_question="查看控制面板";

$query_orders = "SELECT count(*) as total_order FROM orders";
$orders = mysqli_query($localhost,$query_orders);
if(!$orders){$logger->fatal("数据库操作失败:".$query_orders);}
$row_orders = mysqli_fetch_assoc($orders);
$totalRows_orders = $row_orders['total_order'];


$query_users = "SELECT * FROM `user`";
$users = mysqli_query($localhost,$query_users);
if(!$users){$logger->fatal("数据库操作失败:".$query_users);}
$row_users = mysqli_fetch_assoc($users);
$totalRows_users = mysqli_num_rows($users);



$query_comment = "SELECT count(*)  as total FROM product_comment where is_delete=0";
$comment = mysqli_query($localhost,$query_comment);
if(!$comment){$logger->fatal("数据库操作失败:".$query_comment);}
$row_comment = mysqli_fetch_assoc($comment);
$totalRows_comment = $row_comment['total'];


$query_product_consult = "SELECT count(*)  as total FROM product_consult where is_delete=0";
$product_consult = mysqli_query($localhost,$query_product_consult);
if(!$product_consult){$logger->fatal("数据库操作失败:".$query_product_consult);}
$row_product_consult = mysqli_fetch_assoc($product_consult);
$totalRows_product_consult= $row_product_consult['total'];



$query_unpaied = "SELECT count(*)  as total  FROM orders WHERE order_status = 0";
$unpaied = mysqli_query($localhost,$query_unpaied);
if(!$unpaied){$logger->fatal("数据库操作失败:".$query_unpaied);}
$row_unpaied = mysqli_fetch_assoc($unpaied);
$totalRows_unpaied = $row_unpaied['total'];


$query_finished = "SELECT count(*)  as total  FROM orders   WHERE order_status = 300";
$finished = mysqli_query($localhost,$query_finished);
if(!$finished){$logger->fatal("数据库操作失败:".$query_finished);}
$row_finished = mysqli_fetch_assoc($finished);
$totalRows_finished = $row_finished['total'];


$query_refunded = "SELECT count(*)  as total FROM orders  WHERE order_status = -300";
$refunded = mysqli_query($localhost,$query_refunded);
if(!$refunded){$logger->fatal("数据库操作失败:".$query_refunded);}
$row_refunded = mysqli_fetch_assoc($refunded);
$totalRows_refunded = $row_refunded['total'];


$query_withdrawled = "SELECT count(*)  as total FROM orders  WHERE order_status = -100";
$withdrawled = mysqli_query($localhost,$query_withdrawled);
if(!$withdrawled){$logger->fatal("数据库操作失败:".$query_withdrawled);}
$row_withdrawled = mysqli_fetch_assoc($withdrawled);
$totalRows_withdrawled = $row_withdrawled['total'];


$query_paid = "SELECT count(*)  as total FROM orders   WHERE order_status = 100";
$paid = mysqli_query($localhost,$query_paid);
if(!$paid){$logger->fatal("数据库操作失败:".$query_paid);}
$row_paid = mysqli_fetch_assoc($paid);
$totalRows_paid = $row_paid['total'];


$query_returned = "SELECT count(*)  as total  FROM orders   WHERE order_status = -200";
$returned = mysqli_query($localhost,$query_returned);
if(!$returned){$logger->fatal("数据库操作失败:".$query_returned);}
$row_returned = mysqli_fetch_assoc($returned);
$totalRows_returned = $row_returned['total'];


$query_recent_orders = "SELECT orders.*,user.username FROM orders inner join user on user.id=orders.user_id where orders.is_delete=0 ORDER BY orders.id DESC limit 5";
$recent_orders = mysqli_query($localhost,$query_recent_orders);
if(!$recent_orders){$logger->fatal("数据库操作失败:".$query_recent_orders);}
$row_recent_orders = mysqli_fetch_assoc($recent_orders);
$totalRows_recent_orders = mysqli_num_rows($recent_orders);

$query_total_sales = "SELECT sum('actual_pay') as total FROM orders";
$total_sales = mysqli_query($localhost,$query_total_sales);
if(!$total_sales){$logger->fatal("数据库操作失败:".$query_total_sales);}
$row_total_sales = mysqli_fetch_assoc($total_sales);
$totalRows_total_sales = $row_total_sales['total'];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
<!--
.STYLE1 {font-size: 30px}
.STYLE2 {
	color: #FFFFFF;
	font-size: 12px;
}
a{
	text-decoration:none;
	color:#000000;
}

#new_version_indicator{
	background-color:#f5f5f5;
	border:1px solid #e3e3e3;
	width:100%;
	font-size:12px;
	margin-bottom:20px;
	min-height:20px;
 	padding:10px;
}

#new_version_indicator a{
	color:#000000;
 }
-->
</style>
</head>

<body>
<table width="100%" height="48" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><span class="STYLE1">管理中心</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?></td>
  </tr>
</table>
<div id="new_version_indicator" style="display:none;">123phpshop出新版本啦！新的版本号是：。各位亲，点击这里下载，点击这里查看详细介绍</div>
<table width="100%" height="148" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><div align="center">
      <table width="98%" height="148" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="28" bgcolor="#1E91CF"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;">订单总数</span></td>
          </tr>
          <tr bgcolor="#279FE0">
            <td height="92"><div align="right" style="font-size:42px;color:white;padding-right:10px;"><?php echo $totalRows_orders;?></div></td>
          </tr>
          <tr>
            <td height="28" bgcolor="#3DA9E3"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;"><a href="/admin/order/index.php" style="color:#FFFFFF;">查看更多</a>...</span></td>
          </tr>
        </table>
    </div></td>
    <td align="center"><table width="98%" height="148" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="28" bgcolor="#1E91CF"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;">用户总数</span></td>
      </tr>
      <tr bgcolor="#279FE0">
        <td height="92"><div align="right" style="font-size:42px;color:white;padding-right:10px;"><?php echo $totalRows_users;?></div></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#3DA9E3"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;"><a href="/admin/users/index.php" style="color:#FFFFFF;">查看更多</a>...</span></td>
      </tr>
    </table></td>
    <td align="center"><div align="right">
      <table width="98%" height="148" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="28" bgcolor="#1E91CF"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;">评论总数</span></td>
        </tr>
        <tr bgcolor="#279FE0">
          <td height="92"><div align="right" style="font-size:42px;color:white;padding-right:10px;"><?php echo $totalRows_comment;?></div></td>
        </tr>
        <tr>
          <td height="28" bgcolor="#3DA9E3"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;"><a href="/admin/user_comments/index.php" style="color:#FFFFFF;">查看更多</a>...</span></td>
        </tr>
      </table>
    </div></td>
    <td align="center"><div align="center">
      <table width="98%" height="148" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="28" bgcolor="#1E91CF"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;">咨询总数</span></td>
        </tr>
        <tr bgcolor="#279FE0">
          <td height="92"><div align="right" style="font-size:42px;color:white;padding-right:10px;"><?php echo $totalRows_product_consult;?></div></td>
        </tr>
        <tr>
          <td height="28" bgcolor="#3DA9E3"><span class="STYLE2" style="font-size:12px;color:white;padding-left:8px;"><a href="/admin/user_consult/index.php" style="color:#FFFFFF;">查看更多</a>...</span></td>
        </tr>
      </table>
    </div>
    </td>
  </tr>
</table>
<br />
<?php if ($totalRows_recent_orders > 0) { // Show if recordset not empty ?>
  <table   width="100%" border="1" style="border-collapse:collapse;border-top:2px solid #bfbfbf;" cellpadding="0" cellspacing="0" bordercolor="#e8e8e8">
    <tr >
      <td height="44" style="padding-left:20px;" >最新订单</td>
    </tr>
    <tr style="border-top-width:0px;">
      <td height="33"><table style="padding-left:20px;" width="100%" border="0">
        <tr>
          <td width="23%">订单ID</td>
            <td width="16%">会员</td>
            <td width="30%">创建日期</td>
            <td width="17%">总计</td>
            <td width="14%">操作</td>
          </tr>
      </table></td>
    </tr>
    <?php do { ?>
      <tr style="border-top-width:0px;">
        <td height="52"><table  style="padding-left:20px;"  height="52" width="100%" border="0">
          <tr>
            <td width="23%"><a href="order/detail.php?recordID=<?php echo $row_recent_orders['id']; ?>"><?php echo $row_recent_orders['sn']; ?></a></td>
              <td width="16%"><?php echo $row_recent_orders['username']; ?></td>
              <td width="30%"><?php echo $row_recent_orders['create_time']; ?></td>
              <td width="17%"><?php echo $row_recent_orders['should_paid']; ?></td>
              <td width="14%"><a href="/admin/order/detail.php?recordID=<?php echo $row_recent_orders['id']; ?>">查看</a></td>
            </tr>
        </table></td>
      </tr>
      <?php } while ($row_recent_orders = mysqli_fetch_assoc($recent_orders)); ?>
      </table>
  <?php } // Show if recordset not empty ?><br />
<table  width="100%" border="1" style="border-collapse:collapse;border-top:2px solid #bfbfbf;" cellpadding="0" cellspacing="0" bordercolor="#e8e8e8">
  <tr >
    <td height="44" style="padding-left:20px;">订单统计信息</td>
  </tr>
  <tr style="border-top-width:0px;">
    <td height="52"><table style="padding-left:20px;" width="100%" border="0">
      <tr>
        <td width="25%" height="33"><a href="/admin/order/index.php?status=100">未发货订单:</a></td>
        <td width="25%" height="33"><?php echo $totalRows_paid ?> </td>
        <td width="25%" height="33"><a href="/admin/order/index.php?status=-100">已撤销订单:</a></td>
        <td height="33"><?php echo $totalRows_withdrawled ?></td>
      </tr>
      <tr>
        <td height="33"><a href="/admin/order/index.php?status=0">未支付订单:</a></td>
        <td height="33"><?php echo $totalRows_unpaied ?></td>
        <td height="33"><a href="/admin/order/index.php?status=300">已完成订单:</a></td>
        <td height="33"><?php echo $totalRows_finished ?> </td>
      </tr>
      <tr>
        <td height="33"><a href="/admin/order/index.php?status=-200">已退货订单:</a></td>
        <td height="33"><?php echo $totalRows_returned ?> </td>
        <td height="33"><a href="/admin/order/index.php?status=-300">已退款订单:</a></td>
        <td height="33"><?php echo $totalRows_refunded ?> </td>
      </tr>
    </table></td>
  </tr>
</table>
<br />
<table width="100%" border="1" style="border-collapse:collapse;border-top:2px solid #bfbfbf;" cellpadding="0" cellspacing="0" bordercolor="#e8e8e8">
  <tr >
    <td height="44" style="padding-left:20px;">系统信息</td>
  </tr>
  <tr style="border-top-width:0px;">
    <td height="52"><table style="padding-left:20px;" width="100%" border="0">
      <tr>
        <td width="25%" height="33">服务器操作系统:</td>
        <td width="25%" height="33"><?php echo PHP_OS;?></td>
        <td width="25%" height="33">网站服务器版本:</td>
        <td height="33">&nbsp;</td>
      </tr>
      <tr>
        <td height="33">PHP版本:</td>
        <td height="33"><?php echo phpversion();?></td>
        <td height="33">123PHPSHOP版本:</td>
        <td height="33">1.6</td>
      </tr>
      <tr>
        <td height="33">网站目录:</td>
        <td height="33"><?php echo $_SERVER['DOCUMENT_ROOT'];?></td>
        <td height="33">&nbsp;</td>
        <td height="33">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/json_parse.js"></script>

<script>
$().ready(function(){
	var new_version_string=<?php echo file_get_contents("http://www.123phpshop.com/version.php");?>;
 	var new_version_obj=eval(new_version_string);
	var current_version=1.6;
	if(parseFloat(current_version)<parseFloat(new_version_obj.version)){
		var new_version_indicator_string="123phpshop出新版本啦！新的版本号是:"+new_version_obj.version+". 各位亲,<a target='_blank' href='"+new_version_obj.download_url+"'>请点击这里下载</a>, <a  target='_blank' href='"+new_version_obj.doc_url+"'>点击这里查看详细介绍</a>,<a target='_blank' href='http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes'>点击这里联系123phpshop进行升级</a>";
		$("#new_version_indicator").html(new_version_indicator_string);
		$("#new_version_indicator").show();
	}
});
</script>
</body>
</html>