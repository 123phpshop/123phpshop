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
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if($could_withdraw==0){ ?>
<div class="phpshop123_infobox">
  <p>由于以下原因，您不能撤销这个订单：</p>
  <p>1. 	订单不存在，请检查参数之后再试。</p>
  <p>2. 	系统错误，请稍后再试。 </p>
  <p>3.	这个订单不属于您</p>
  <p>4.	订单已经发货不能被撤销</p>
  <p>5.	订单已经被删除</p>
  <p>6.	订单已经被撤销或退货</p>
  <p>您也可以<a href="index.php">点击这里返回</a>。</p>
</div>
<p>
  <?php } ?>
</p>
</body>
</html>
<?php
mysql_free_result($order);
?>