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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>123phpshop-购物车</title>
<style type="text/css">
<!--
table {
	border-collapse: collapse;
}

a {
	text-decoration: none;
	color: black;
}

a:hover {
	color: red;
}

.STYLE1 {
	font-size: 12px
}

.STYLE3 {
	color: #999;
	font-size: 12px;
}

.STYLE4 {
	color: #FF0000;
	font-size: 16px;
	font-weight: bold;
}

.STYLE5 {
	color: #FF0000;
	font-weight: bold;
}

#empty_cart {
	text-align: center;
	width: 990px;
	margin: 0 auto;
	font-size: 25px;
}

.cart_red_tip {
	color: #FFFFFF;
	background: #FF0000;
	padding: 0px 5px;
}
-->
</style>
<link href="css/common_admin.css" rel="stylesheet" type="text/css" />
</head>
<body style="margin: 0px;">
<?php
include_once ('widget/top_full_nav.php');
?>
<?php
include_once ('widget/logo_search.php');
?>
<p>
<?php
if (empty ( $cart_products )) {
	?>
</p>
	<div id="empty_cart">
		<div align="center">
			<p>
				<a href="index.php"><img src="uploads/default_product.png"
					alt="123phpshop.com" width="350" height="350" border="0" /></a>
			</p>
			<p>
				<a href="index.php">购物车里面空空如也，赶紧把他填满吧。</a>
			</p>
		</div>
	</div>
<?php
} else {
	?>
 <form id="cart_form" name="cart_form" method="post"
		action="confirm.php">
		<table width="990" height="37" border="0" align="center">
			<tr>
				<td height="37"><span class="STYLE5">全部商品</span></td>
			</tr>
		</table>
		<table width="990" border="0" align="center" cellpadding="0"
			cellspacing="0" bordercolor="#666666">
			<tr>
				<td width="133" height="43" bgcolor="#f3f3f3" scope="col">&nbsp;</td>
				<td width="340" bgcolor="#f3f3f3" scope="col"><div align="center">
						<span class="STYLE1">商品</span>
					</div></td>
				<td width="105" height="43" bgcolor="#f3f3f3" scope="col"><div
						align="center">
						<span class="STYLE1">单价（元）</span>
					</div></td>
				<td width="86" height="43" bgcolor="#f3f3f3" scope="col"><div
						align="center">
						<span class="STYLE1">数量</span>
					</div></td>
				<td width="271" height="43" bgcolor="#f3f3f3" scope="col"><div
						align="center">
						<span class="STYLE1">小计（元）</span>
					</div></td>
				<td width="55" height="43" bgcolor="#f3f3f3" scope="col"><span
					class="STYLE1">操作</span></td>
			</tr>
		</table>
		<table style="font-size: 12px;" width="990" style="border:none" align="center"
			cellpadding="0" cellspacing="0">
    <?php
	foreach ( $cart_products as $cart_products_item ) {
		if (isset ( $cart_products_item ['product_id'] )) {
			?>
    <tr
				<?php if(isset($cart_products_item ['is_present'])  && $cart_products_item ['is_present']==1){?>
				is_present=1 <?php };?> bgcolor="#fff4e8"
				id="product_<?php
			echo $cart_products_item ['product_id'];
			?>">
				<td width="133" height="107">
					<div align="center">
						<a style="border: 0px;"
							href="product.php?id=<?php
			echo $cart_products_item ['product_id'];
			?>"><img style="border: 0px;"
							src="<?php
			echo $cart_products_item ['product_image'] != null ? $cart_products_item ['product_image'] : "/uploads/default_product.png";
			?>"
							width="80" height="80" /></a>
					</div>
				</td>
				<td width="336" valign="middle"><a
					href="product.php?id=<?php
			echo $cart_products_item ['product_id'];
			?>">
	    <?php
			if (isset ( $cart_products_item ['is_present'] ) && $cart_products_item ['is_present'] == 1) {
				echo $cart_products_item ['name'];
			} else {
				echo $cart_products_item ['product_name'];
			}
			?> <br />
	    <?php
			echo str_replace ( ";", " ", $cart_products_item ['attr_value'] );
			?>	    
      &nbsp;</a><?php if(isset($cart_products_item ['is_present'])  && $cart_products_item ['is_present']==1){?><span
					class="cart_red_tip">赠品</span><?php } ?><?php if(isset($cart_products_item ['is_promotion_price'])  && $cart_products_item ['is_promotion_price']==1){?><span
					class="cart_red_tip">优惠</span><?php } ?></td>
				<td width="104" height="107"><div align="center">
						<span
							class="product_price_<?php
			echo $cart_products_item ['product_id'];
			?>"
							attr_value="<?php
			echo $cart_products_item ['attr_value'];
			?>">
			    <?php
			$product_price = $cart_products_item ['product_price'];
			if (isset($cart_products_item ['is_present']) && $cart_products_item ['is_present'] == 1) {
				$product_price = "0.00";
			}
			echo $product_price;
			?>
			    </span>
					</div></td>
				<td width="93" height="107">
					<div align="center">
					  <?php if(isset($cart_products_item ['is_present'])  && $cart_products_item ['is_present']==1){ echo "1";}else{?>
				  </div>
					<div name="increase_quantity"
						style="cursor: pointer; float: left; height: 20px; line-height: 20px; width: 20px; border: 1px solid #e54346; background-color: red; color: #FFFFFF; text-align: center;"
						onclick="return change_quantity(<?php
				echo $cart_products_item ['product_id'];
				?>,1,'<?php
				echo $cart_products_item ['attr_value'];
				?>')"
						id="increase_quantity_product_quantity_<?php
				echo $cart_products_item ['product_id'];
				?>">
						<div align="center">+</div>
					</div>
					<div align="center">
						<input readOnly="true"
							style="float: left; text-align: center; height: 18px; line-height: 18px; border: 1px solid #e54346; border-left: 0px; border-right: 0px; margin-top: 0px;"
							class="product_quantity_<?php
				echo $cart_products_item ['product_id'];
				?>"
							value="<?php
				echo $cart_products_item ['quantity'];
				?>"
							size="2" maxlength="10"
							attr_value="<?php
				echo $cart_products_item ['attr_value'];
				?>" />
					</div>
					<div height="15" width="15" name="decrease_quantity"
						style="cursor: pointer; line-height: 20px; border: 1px solid #e54346; float: left; height: 20px; width: 20px; background-color: red; color: #FFFFFF; backgroun-color: red; color: #FFFFFF; text-align: center;"
						onclick="return change_quantity(<?php
				echo $cart_products_item ['product_id'];
				?>,-1,'<?php
				echo $cart_products_item ['attr_value'];
				?>')"
						id="decrease_quantity_product_quantity_<?php
				echo $cart_products_item ['product_id'];
				?>">
						<div align="center">-</div>
					</div>
					<div align="center">
                      <?php } ?>
                    </div>
				</td>
				<td width="258" height="107"><div align="center">
						<strong
							class="sub_total_<?php
			echo $cart_products_item ['product_id'];
			?>"
							attr_value="<?php
			echo $cart_products_item ['attr_value'];
			?>">
			    <?php
			
			$sub_total = floatval ( $cart_products_item ['quantity'] * $cart_products_item ['product_price'] );
			if (isset($cart_products_item ['is_present']) && $cart_products_item ['is_present'] == 1) {
				$sub_total = "0.00";
			}
			echo $sub_total;
			?>
			    </strong>
					</div></td>
				<td width="52" height="107">
				<?php if(!isset($cart_products_item ['is_present']) || $cart_products_item ['is_present']==0){ ?>
				<a href="javascript://"
					onClick="delete_cart_product(<?php
				echo $cart_products_item ['product_id'];
				?>,'<?php
				echo $cart_products_item ['attr_value'];
				?>');">删除</a>
					<?php } ?>
					</td>
			</tr>
    <?php
		}
	}
	?>
  </table>
		<table width="990" height="50" style="border:1px solid #dddddd" align="center"
			cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table border="0" align="right">
						<tr>
							<td valign="bottom" class="STYLE3"><div align="left"
									style="padding-right: 10px;">
									<span class="STYLE3">商品总价:￥ <span id="products_total">
					      <?php
	echo $cart ['products_total'];
	?></span>
									</span>
								</div></td>
							<td valign="bottom" class="STYLE3"><div align="left"
									style="padding-right: 10px;">
									<span class="STYLE3">运费:￥ <span id="shipping_fee">			    
					      <?php
	echo $cart ['shipping_fee'];
	?></span>
									</span>
								</div></td>
							<td valign="bottom" class="STYLE3"><div align="left"
									style="padding-right: 10px;">
									促销:<span class="STYLE3">￥ <span id="promotion_fee">      <?php
	echo $cart ['promotion_fee'];
	?></span>
									</span>
								</div></td>
							<td><span class="STYLE3"
								style="font-size: 14px; font-weight: bold; padding-right: 10px;">总价:<span
									class="STYLE4">￥<span id="cart_total_price"><?php
	echo $cart ['order_total'];
	?></span></span></span></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</td>
				<td width="96" bgcolor="#e54346"><input
					style="font-weight: bold; border: 1px solid #e54346; font-size: 18px; background-color: #e54346; color: white; height: 48px; width: 98px;"
					type="submit" name="Submit" value="去结算" /></td>
			</tr>
		</table>
	</form>

<?php
}
?>
<?php

include_once ('widget/footer.php');
?>
<script language="JavaScript" type="text/javascript"
		src="/js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript"
		src="/js/jquery.validate.min.js"></script>
	<script>
function delete_cart_product(product_id,attr_value){
	
	if(!confirm("您确实要删除此商品么？")){
		return false;
	}
	
	var url="/ajax_remove_cart_product.php";
	$.post(url,{product_id:product_id,attr_value:attr_value},function(data){
		if(data.code=='0'){
			location.href="/cart.php"
			return true;
		}
		alert(data.message);return false;
	},'json');
}

 var change_quantity=function(product_id,quantity,attr_value){
 	var now_quantity=$(".product_quantity_"+product_id+"[attr_value='"+attr_value+"']").val();
 //			获取box中的产品数量的值。如果产品数量为1，但是需要减去一个话，那么告知需要最少要有一件产品
	if(now_quantity==1 && quantity==-1){
		alert("至少需要留1件商品,如果需要删除这个商品，请点击旁边的删除按钮");return false;
	}

//	更新这个产品的数量
	var final_quantity=parseInt($(".product_quantity_"+product_id+"[attr_value='"+attr_value+"']").val())+parseInt(quantity);
	$(".product_quantity_"+product_id+"[attr_value='"+attr_value+"']").val(final_quantity);

//	调用ajax文件进行更新
 	$.post('/ajax_adjust_cart_quantity.php',{product_id:product_id,quantity:final_quantity,attr_value:attr_value},function(data){
	if(data.code!="0"){
		alert(data.message);return false;
	}

//		更新总价
	_update_total_price(data.data.total_price);
	_update_sub_total(product_id,attr_value);
	_update_fee(data.data);
	_123phpshop_remove_presents(data.data.presents_ids);// 删除收回的赠品
	_123phpshop_please_refresh(data.data.presents_ids);// 如果有新的赠品产生，那么显示刷新提示link
		return true;
 	},'json');
 		return false;
}

function _123phpshop_please_refresh(presents_ids){
	var present_ids_num=presents_ids.split(",").length;
	var present_num=$("tr[is_present=1]").length;
	// 当当前的赠品数量《购物车中的赠品数量时，说明有新的赠品出现
 	if(present_ids_num>present_num){
		window.location="/cart.php";
		return;
	} 
 	 $("#please_refresh_presents_link").hide();
}

/**
 * 删除赠品
 */
 function _123phpshop_remove_presents(presents_ids){
	 // 如果赠品的ids为空的话，那么清楚购物车界面中所有的赠品
	if(presents_ids==""){
		_123phpshop_clear_presents();
	}
	
	// 如果不为空的话，那么获取这数组，
	var present_ids_array=presents_ids.split(",");
 	// 获取界面上所有的赠品，循环这些赠品
	$("tr[is_present=1]").each(function(){
		// 获取这个商品的id
		var product_id=$(this).attr("id").replace("product_","");
		// 如果返回的赠品中没有这个商品的话，那么就将其删除
		if(present_ids_array.indexOf(product_id)<0){
 			$(this).remove();
		}
	});
	// 如果当前的这个赠品不在数组中的话，那么立即移除
 }	
 
function _123phpshop_clear_presents(){
	 $("tr[is_present=1]").each(function(){
		 $(this).remove();
		});
}	
/**
 * 更新费用信息
 */
function _update_fee(data){
	$("#shipping_fee").html(data.shipping_fee);
	$("#promotion_fee").html(data.promotion_fee);
	$("#products_total").html(data.products_total);
}

/**
 * 更新总价
 */
function _update_total_price(total_price){
	$("#cart_total_price").html(total_price);
}

/**
 * 更新小计
 */
 
function _update_sub_total(product_id,attr_value){
	//获取产品的id
	var quantity=parseInt($(".product_quantity_"+product_id+"[attr_value='"+attr_value+"']").val());
	var price=parseFloat($(".product_price_"+product_id+"[attr_value='"+attr_value+"']").html()).toFixed(2);
 	var sub_total=parseFloat(quantity*price).toFixed(2);
 	$(".sub_total_"+product_id+"[attr_value='"+attr_value+"']").html(sub_total);
}

</script>

</body>
</html>