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
<style type="text/css">
<!--
body {
	background-color: #f5f5f5;
	font-family:Arial,Verdana,"\5b8b\4f53";
}
th{
 	color:#666666;
	font-size:12px;
	font-weight:bold;
	border:1px solid #f2f2f2;
}
tr{
	border:1px solid #f2f2f2;
}
td{
	border:1px solid #f2f2f2;
}

div{
	color:#000000;
}
-->
</style>
<link href="../../css/common_user.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
-->
</style>
</head>

<body>
<table width="100%" border="0" style="background-color:#ffffff;border:none;">
  <tr>
    <th height="38" scope="row"><div align="left" style="padding-left:20px;">订单产品列表</div></th>
  </tr>
  <tr>
    <td scope="row"><table width="97%" border="1" align="left" cellpadding="0" cellspacing="0" style="margin-left:20px;border:1px solid #f2f2f2;border-collapse:collapse;margin-bottom:20px;">
      <tr style="background-color:#f5f5f5;border:1px solid #f2f2f2;">
        <td width="430" height="31" scope="row"><div align="left" style="padding-left:20px;">商品名称</div></td>
        <td width="306" height="31"><div align="center">本店价格</div></td>
        <td width="576" height="31"><div align="center">商品数量</div></td>
        <td width="46">赠品</td>
        <td width="224" height="31"><div align="center">操作</div></td>
      </tr>
      <?php do { ?>
	  
	  <?php 
 	  	mysql_select_db($database_localhost, $localhost);
		$query_order_product = "SELECT * FROM product WHERE id =".$row_order_items['product_id'];
		$order_product = mysqli_query($localhost);if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL,$query_order_product);}
		$row_order_product = mysqli_fetch_assoc($order_product);
		$totalRows_order_product = mysql_num_rows($order_product);
  	  ?>
        <tr>
          <td height="31" scope="row"><div align="left" style="padding-left:20px;"><a style="text-decoration:none;color:#005ea7;" href="/product.php?id=<?php echo $row_order_product['id']; ?>" target="_blank"><?php echo $row_order_product['name']; ?></a> <span style="color:#CCCCCC"><?php echo str_replace(";"," ",$row_order_items['attr_value']); ?></span></div></td>
          <td><div align="center"><strong class="STYLE1">￥<?php echo $row_order_items['should_pay_price']; ?></strong></div></td>
          <td><div align="center"><?php echo $row_order_items['quantity']; ?></div></td>
          <td><?php if($row_order_items['is_present']==1){ ?><span style="background:#FF0000;color:#FFFFFF;padding:0px 5px;">赠品</span><?php } ?></td>
          <td><form id="form1" name="form1" method="post" action="/cart.php" target="_parent">
            <label>
             <div align="center">
			<input name="product_name" type="hidden"  value="<?php echo $row_order_product['name']; ?>">
			<?php 
				mysql_select_db($database_localhost, $localhost);
				$query_product_image = sprintf("SELECT * FROM product_images WHERE is_delete=0 and  product_id = %s", $row_order_product['id']);
				$product_image = mysqli_query($localhost);if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL,$query_product_image);}
				$row_product_image = mysqli_fetch_assoc($product_image);
   			?>
   
			<input name="product_image" type="hidden"  value="<?php echo $row_product_image['image_files'];?>">
			<input name="attr_value" type="hidden"  value="<?php echo isset($row_order_items['attr_value'])?str_replace(";"," ",$row_order_items['attr_value']):""; ?>">
			<input name="ad_text" type="hidden" id="ad_text" value="<?php echo $row_order_product['ad_text']; ?>">
			<input name="product_price" type="hidden"  value="<?php echo $row_order_items['actual_pay_price']; ?>">
			<input name="product_id" type="hidden"  value="<?php echo $row_order_product['id']; ?>">
			<input name="quantity" type="hidden"  value="<?php echo $row_order_items['quantity']; ?>">
			<input type="submit" name="Submit" value="放入购物车" />
            </div>
            </label>
                    </form>          </td>
        </tr>
        <?php } while ($row_order_items = mysqli_fetch_assoc($order_items)); ?>
    </table></td>
  </tr>
</table>
 <p>&nbsp;</p>
 <table width="100%" border="0" style="background-color:#ffffff;border:none;">
    <tr>
      <th height="38" scope="row"><div align="left" style="padding-left:20px;">订单信息</div></th>
    </tr>
    <tr>
      <td scope="row"> <table width="97%" height="408" border="1" align="left" cellpadding="0" cellspacing="0" style="margin-left:20px;border:1px solid #f2f2f2;border-collapse:collapse;margin-bottom:20px;">
          <tr style="border:1px solid #f2f2f2;">
            <td width="110" height="29" scope="row" ><div align="left" style="padding-left:20px;">订单编号</div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $row_order['sn']; ?></div></td>
          </tr>
          <tr>
            <td height="29" scope="row"><div align="left" style="padding-left:20px;">支付方式</div></td>
            <td><div align="left" style="padding-left:20px;"><?php echo $pay_methomd[$row_order['payment_method']]; ?></div></td>
          </tr>
         
        
          <tr>
            <td height="29" scope="row"><div align="left" style="padding-left:20px;">应付</div></td>
            <td height="29"><div align="left" style="padding-left:20px;color:#FF0000;font-weight:bold;">￥<?php echo $row_order['should_paid']; ?></div></td>
          </tr>
		   <tr>
		     <td height="29" scope="row"><div align="left" style="padding-left:20px;">商品金额</div></td>
		     <td height="29" style="padding-left:20px;color:#FF0000;font-weight:bold;">￥<?php echo $row_order['products_total']; ?></td>
        </tr>
		   <tr>
            <td height="29" scope="row"><div align="left" style="padding-left:20px;">
               运费
            </div></td>
            <td height="29"><div align="left" style="padding-left:20px;color:#FF0000;font-weight:bold;">￥<?php echo $row_order['shipping_fee']; ?></div></td>
          </tr>
           <tr>
             <td height="29" scope="row"><div align="left"  style="padding-left:20px;">促销折减</div></td>
             <td height="29" style="padding-left:20px;color:#FF0000;font-weight:bold;">￥<?php echo $row_order['promotion_fee']; ?></td>
           </tr>
          <tr>
            <td height="29" scope="row"><div align="left" style="padding-left:20px;">状态</div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $order_status[$row_order['order_status']]; ?></div></td>
          </tr>
		  <?php if($row_order['delivery_at']!="0000-00-00 00:00:00" && $row_order['delivery_at']!=''){ ?>
          <tr class=" ">
            <td height="29" scope="row"> <div align="left" style="padding-left:20px;">发货时间 </div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $row_order['delivery_at']; ?></div></td>
          </tr>
		  <?php } ?>
		   <?php if($row_order['pay_at']!="0000-00-00 00:00:00" ){ ?>
          <tr class=" ">
            <td height="29" scope="row"> <div align="left" style="padding-left:20px;">支付时间 </div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $row_order['pay_at']; ?></div></td>
          </tr>
		   <?php } ?>
		     <?php if($row_order['refund_at']!="0000-00-00 00:00:00"  ){ ?>
          <tr class=" ">
            <td height="29" scope="row"> <div align="left" style="padding-left:20px;">退货时间 </div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $row_order['refund_at']; ?></div></td>
          </tr>
		   <?php } ?>
          <tr>
            <td height="29" scope="row"> <div align="left" style="padding-left:20px;">收货时间</div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $please_deliver_at[$row_order['please_delivery_at']]; ?></div></td>
          </tr>
          <tr>
            <td height="29" scope="row"><div align="left" style="padding-left:20px;">下单时间</div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $row_order['create_time']; ?></div></td>
          </tr>
		   
		   <?php if(!is_null($row_order['express_company_name'])){ ?>
          <tr>
            <td height="29" scope="row"><div align="left"style="padding-left:20px;">物流公司</div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $row_order['express_company_name']; ?></div></td>
          </tr>
 		   <?php } ?>
		    <?php if(!is_null($row_order['express_sn']) && $row_order['express_sn']!='' ){ ?>
          <tr>
            <td height="29" scope="row"><div align="left"style="padding-left:20px;">物流订单号</div></td>
            <td height="29"><div align="left" style="padding-left:20px;"><?php echo $row_order['express_sn']; ?></div></td>
          </tr>
		   <?php } ?>
      </table></td>
    </tr>
</table>
  <p>&nbsp;</p>
  <table width="100%" border="0" style="background-color:#ffffff;border:none;">
   <tr>
     <th height="38" scope="row"><div align="left" style="padding-left:20px;">订单处理过程</div></th>
   </tr>

   <tr>
     <td scope="row">
	 <table width="97%"  border="0" align="left" cellpadding="0" cellspacing="0" style="margin-left:20px;border:1px solid #f2f2f2;border-collapse:collapse;margin-bottom:20px;">
        <?php while ($row_log_DetailRS1 = mysqli_fetch_assoc($log_DetailRS1)){ ?>
			<tr>
			 <td width="10%" scope="row"><div align="left" style="padding-left:20px;"><?php echo $row_log_DetailRS1['create_time'];?></div></td>
			 <td width="90%"><div align="left" style="padding-left:20px;"><?php echo $row_log_DetailRS1['message'];?></div></td>
		   </tr>
	   <?php  } ?>
     </table></td>
   </tr>
</table>
  <p>&nbsp;</p>
  <table width="100%" border="0" style="background-color:#ffffff;border:none;">
   <tr>
     <th height="38" scope="row"><div align="left" style="padding-left:20px;">发票信息</div></th>
   </tr>

   <tr>
     <td scope="row">
	 <table width="97%" height="116" border="1" align="left" cellpadding="0" cellspacing="0" style="margin-left:20px;border:1px solid #f2f2f2;border-collapse:collapse;margin-bottom:20px;">
        <tr>
         <td width="110" scope="row"><div align="left" style="padding-left:20px;">需要发票</div></td>
         <td><div align="left" style="padding-left:20px;"><?php echo $row_order['invoice_is_needed']=='0'?'否':'√'; ?></div></td>
       </tr>
       <tr>
         <td scope="row"><div align="left" style="padding-left:20px;">发票抬头</div></td>
         <td><div align="left" style="padding-left:20px;"><?php echo $row_order['invoice_title']; ?></div></td>
       </tr>
       <tr>
         <td scope="row"><div align="left" style="padding-left:20px;">发票信息</div></td>
         <td><div align="left" style="padding-left:20px;"><?php echo $row_order['invoice_message']; ?></div></td>
       </tr>
     </table></td>
   </tr>
 </table>
 
  <p>&nbsp;</p>
  <table width="100%" border="0" style="background-color:#ffffff;border:none;">
    <tr>
      <th height="38" scope="row"><div align="left" style="padding-left:20px;">收货人信息</div></th>
    </tr>
    <tr>
      <td scope="row"> <table width="97%" height="116" border="1" align="left" cellpadding="0" cellspacing="0" style="margin-left:20px;border:1px solid #f2f2f2;border-collapse:collapse;margin-bottom:20px;">
          <tr>
            <td width="110" scope="row"><div align="left" style="padding-left:20px;">收货人姓名</div></td>
            <td><div align="left" style="padding-left:20px;"><?php echo $row_order['consignee_name']; ?></div></td>
          </tr>
          <tr>
            <td scope="row"><div align="left" style="padding-left:20px;">地址</div></td>
            <td><div align="left" style="padding-left:20px;"><?php echo $row_order['consignee_province']; ?><?php echo $row_order['consignee_city']; ?><?php echo $row_order['consignee_district']; ?><?php echo $row_order['consignee_address']; ?></div></td>
          </tr>
          <tr>
            <td scope="row"><div align="left" style="padding-left:20px;">电话</div></td>
            <td><div align="left"  style="padding-left:20px;"><?php echo $row_order['consignee_mobile']; ?></div></td>
          </tr>
          <tr>
            <td scope="row"><div align="left"  style="padding-left:20px;">邮编</div></td>
            <td><div align="left"  style="padding-left:20px;"><?php echo $row_order['consignee_zip']; ?></div></td>
          </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="100%" border="0" style="background-color:#ffffff;border:none;">
    <tr>
      <th height="38" scope="row"><div align="left" style="padding-left:20px;">结算信息</div></th>
    </tr>
    <tr>
      <td scope="row"> <table width="97%" height="31" border="1" align="left" cellpadding="0" cellspacing="0" style="margin-left:20px;border:1px solid #f2f2f2;border-collapse:collapse;margin-bottom:20px;">
          <tr>
            <td width="110" scope="row"><div align="left" style="padding-left:20px;">订单金额</div></td>
            <td><div align="left" style="padding-left:20px;"><strong style="color:#FF0000">￥<?php echo $row_order['should_paid']; ?></strong></div></td>
          </tr>
      </table></td>
    </tr>
  </table>
</body>
</html>