<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
global $db_conn;


/*function get_logistic_process_info($express_company_id,$express_order_sn){
	$result=array();
	
	//		获取快递公司的拼音名称
	
	$query_express_company = "SELECT code FROM express_company WHERE id = '".$express_company_id."'";
	$express_company = mysql_query($query_express_company) or die(mysql_error());
	$row_express_company = mysql_fetch_assoc($express_company);
	$totalRows_express_company = mysql_num_rows($express_company);
	if($totalRows_express_company==0){
		return $result;
	}
	
	require_once($_SERVER['DOCUMENT_ROOT']."/Connnections/lib/simple_html_dom.php");
	$url="http://m.kuaidi100.com/index_all.html?type=".$row_express_company['code']."&postid=".$express_order_sn;
	
}*/

/**
获取购物车中产品的运费
**/ 
function get_shipping_fee(){

	// 获取这个订单的所有商品的总重量和总数量
	$weight=0.00;
	$quantity=1;
	$shipping_fee=0.00;
 	$shipping_fee_plan=1;
	$weight=_get_order_weight();
	$is_first_shipping_fee =true;
	$quantity=_get_order_quantity();
	$shipping_methods=_get_shipping_methods();
 
  
	// 	如果找不到相应的配送方式的话
  	if(count($shipping_methods)==0){
		//throw new Exception("不能运送");
	}
	
 	
	// 获取所有激活的没有被删除的快递方式
 	foreach($shipping_methods as $shipping_methods_item){
				
				//	 如果产品是货到付款的
				if($shipping_methods_item['is_cod']==1){
					$shipping_fee=0.00;
					$shipping_fee_plan=$shipping_methods_item['shipping_methods_id'];
					break;
 				}
				
				//	如果这个运送方法是通过产品数量进行计算的话，那么计算出运费
				if($shipping_methods_item['shipping_by_quantity']==1){
					$shipping_fee_now=_calc_shipping_fee_by_quantity($quantity,$shipping_methods_item);
				}else{
					//	如果这个运费送方式是通过产品重量来计算的话，那么计算出运费
					$shipping_fee_now=_calc_shipping_fee_by_weight($weight,$shipping_methods_item);
				}
				
				//	如果这里是第一个运费方案的话，那么直接设置为这里的运费
		if($is_first_shipping_fee==true || $shipping_fee_now<$shipping_fee){
			$is_first_shipping_fee=false;
			$shipping_fee=$shipping_fee_now;
 			$shipping_fee_plan=$shipping_methods_item['shipping_method_id'];
  		} 
 	}
	
	 $result['shipping_fee']=$shipping_fee;
	 $result['shipping_fee_plan']=$shipping_fee_plan;
	 return $result;
}	

/**
	获取购物车中的产品的重量
**/
function _get_order_weight(){
	$result=0;
	foreach($_SESSION['cart']['products'] as $product){
 		if($product['is_shipping_free']=='0'){//   如果不是免运费的话
			$product_obj=_get_product_by_id($product['product_id']);
			if(false==$product_obj){
				throw new Excaption("产品不存在或者已经删除");
			}
			$result+=$product_obj['weight']*$product['quantity']; // 商品总重=商品数量×商品重量
		}
		
	}
	return $result;
}

/**
	获取购物车中产品的数量
**/
function _get_order_quantity(){
	$result=0;
 	foreach($_SESSION['cart']['products'] as $product){
 		if($product['is_shipping_free']=='0'){//   如果不是免运费的话
			$result+=$product['quantity'];
		}
	}
 	return $result;
}

/**
	获取可以用的配送方式
**/
function _get_shipping_methods(){
	$result=array();
 	if($_SESSION['cart']['products'][0]['province']!='' && !isset($_SESSION['user']['province']) && !isset($_SESSION['user']['city']) && !isset($_SESSION['user']['district'])){
		$province	=$_SESSION['cart']['products'][0]['province'];
		$city		=$_SESSION['cart']['products'][0]['city'];
		$district	=$_SESSION['cart']['products'][0]['district'];
	}else{
		$province	=$_SESSION['user']['province'];
		$city		=$_SESSION['user']['city'];
		$district	=$_SESSION['user']['district'];
	}
	$location[]=$province."_*_*";
	$location[]=$province."_".$city."_*";
	$location[]=$province."_".$city."_".$district;
	
	// 这里需要根据用户的收货地址来选择可以供货的配送方式
   	return	_could_devliver_shipping_methods($location);
 }
/**
通过数量来计算运费
**/
function _calc_shipping_fee_by_quantity($quantity,$shipping_methods_item){
	return $shipping_methods_item['basic_fee']+$shipping_methods_item['cod_fee']+$shipping_methods_item['single_product_fee']*$quantity;
}

/**
通过重量来计算运费
**/
function _calc_shipping_fee_by_weight($weight,$shipping_methods_item){
	
 //		获取购物车中商品的总重量
 	$shipping_fee=0.00;
 	
 	// 如果配送方式是按照kg来计算的话
	if($shipping_methods_item['first_kg_fee']!=null && $shipping_methods_item['continue_kg_fee']!=null){
		//	如果重量>=1kg的话
		if($weight>=1000){
			$shipping_fee=ceil($weight/1000-1)*floatval($shipping_methods_item['continue_kg_fee'])+$shipping_methods_item['first_kg_fee'];
		}else{
			//如果重量<1kg的话
			$shipping_fee=$shipping_methods_item['first_kg_fee'];
		}
 	}
	
	// 如果配送方式是按照500g来计算的话
	if($shipping_methods_item['half_kg_fee']!=null && $shipping_methods_item['continue_half_kg_fee']!=null){
		//	如果重量>=1kg的话
		if($weight>=500){
			$shipping_fee=ceil($weight/500-1)*floatval($shipping_methods_item['continue_half_kg_fee'])+$shipping_methods_item['half_kg_fee'];
		}else{
			//如果重量<1kg的话
			$shipping_fee=$shipping_methods_item['half_kg_fee'];
		}
 	}
  	
 	return $shipping_methods_item['basic_fee']+$shipping_methods_item['cod_fee']+$shipping_fee;
}

function _get_product_by_id($product_id){
	$query_express_company = "SELECT * FROM product WHERE id = '".$product_id."'";
	global $db_conn;
	$express_company = mysql_query($query_express_company,$db_conn) or die(mysql_error());
	$row_express_company = mysql_fetch_assoc($express_company);
	$totalRows_express_company = mysql_num_rows($express_company);
	if($totalRows_express_company==0){
		return false;
	}
	return 	$row_express_company;
  }
  
  /**
	检查是否在运送范围之内
**/
function _could_devliver_shipping_methods($areas){
		
		if(!is_array($areas)){
			return false;
		}
 		$result=array();
		
 		// 获取所有已经激活的且没有被删除的配送方式的未删除配送区域
		$query_area = "SELECT shipping_method_area.*,shipping_method.is_free,shipping_method.is_delete as shipping_method_is_delete,shipping_method.is_activated as shipping_method_is_activated,shipping_method.is_cod,shipping_method.name as shipping_method_name from shipping_method_area left join shipping_method on shipping_method_area.shipping_method_id=shipping_method.id where shipping_method_area.is_delete=0";
		global $db_conn;
		$area = mysql_query ( $query_area,$db_conn) or die ( mysql_error () );
		
		while($order_area=mysql_fetch_assoc($area)){
 			foreach($areas as $area_item){
 				if($order_area['shipping_method_is_delete']=='0' && $order_area['shipping_method_is_activated']=='1' && strpos($order_area['area'],$area_item)>-1){
 					$result[]=$order_area;
				}	
			}
		}
  		return $result;
}

// 订单合并
function order_merge($from_order_sn,$to_order_sn){
	
		// 检查主订单和从订单是否存在，如果不存在，那么抛出错误
		$from_order_obj=_get_order_by_sn($from_order_sn);
 		$to_order_obj=_get_order_by_sn($to_order_sn);
  		if(!$from_order_obj){
			throw new Exception("订单号为：".$from_order_sn."的订单不存在");
		}
		
		if(!$to_order_obj){
			throw new Exception("订单号为".$to_order_sn."的订单不存在");
		}
		
		// 如果存在的话，那么检查是否已经被删除，如果有删除的话，那么抛出错误
		if($from_order_obj['is_delete']==1){
			throw new Exception("订单号为：$from_order_sn的订单已被删除,不能合并");
		}	
		
		if($to_order_obj['is_delete']==1){
			throw new Exception("订单号为：$to_order_sn的订单已被删除,不能合并");
		}
		
		//检查订单是否已经合并
		 if($from_order_obj['merge_to']==$to_order_obj['id']){
			return true;
		}
			
		if($from_order_obj['merge_to']!="0"){
			throw new Exception("订单号为：".$from_order_sn."的订单订单已经被合并，不能再次进行操作");
		}
					

 		if($to_order_obj['merge_to']!="0"){
			throw new Exception("订单号为：".$to_order_sn."的订单订单已经被合并，不能再次行操作");
		}
   		
		 
		// 检查订单状态是否相同。是否在退货和发货之间，如果已经发货，那么告知
		if($from_order_obj['order_status']!=$to_order_obj['order_status']){
			throw new Exception("只有状态相同的订单才可以合并哦");
		}
		
		if($to_order_obj['order_status']!=ORDER_STATUS_UNPAID && $to_order_obj['order_status']!=ORDER_STATUS_PAID){
			throw new Exception("订单号为：".$to_order_sn."的订单的状态既不是创建也不是已经付款，所以不能进行合并");
		}
		
		if($from_order_obj['order_status']!=ORDER_STATUS_UNPAID && $from_order_obj['order_status']!=ORDER_STATUS_PAID){
			throw new Exception("订单号为：".$from_order_obj."的订单的状态既不是创建也不是已经付款，所以不能进行合并");
		}
		
		
	// 将从订单的订单sn修改为订单的订单sn
		if(!_update_merge_to($from_order_obj['id'],$to_order_obj['id'])){
 			throw new Exception("更新从订单是否为合并订单的数据库操作失败");
		}
		
	// 将从订单的产品所属的订单id修改为主订单的id
		if(!_update_child_order_product_order_id($from_order_obj['id'],$to_order_obj['id'])){
 			throw new Exception("更新从订单产品的数据库操作失败");
		}
		
	// 更新主订单的价格参数
		if(!_update_to_order_price_para($from_order_obj,$to_order_obj)){
			throw new Exception("更新主订单价格的数据库操作失败");
		}
		
		if(!_log_order_merge($from_order_obj['id'],$to_order_obj['id'])){
			throw new Exception("更新主订单价格的数据库操作失败");
		}
		
		
}

// 通过订单序列号来获取订单记录
function _get_order_by_sn($from_order_sn){

 	$query_form_order = sprintf("SELECT * FROM orders WHERE sn = '%s'", $from_order_sn);
	global $db_conn;
	$form_order = mysql_query($query_form_order,$db_conn) or die(mysql_error());
	$row_form_order = mysql_fetch_assoc($form_order);
	$totalRows_form_order = mysql_num_rows($form_order);
	if($totalRows_form_order>0){
		return $row_form_order;
	}
	return false;
}

// 更新主订单的费用
function _update_to_order_price_para($from_order_obj,$to_order_obj){
 	$shipping_fee	=	$from_order_obj['shipping_fee']		+	$to_order_obj['shipping_fee'];
	$products_total	=	$from_order_obj['products_total']	+	$to_order_obj['products_total'];
	$should_paid	=	$from_order_obj['should_paid']		+	$to_order_obj['should_paid'];
	$actual_paid	=	$from_order_obj['actual_paid']		+	$to_order_obj['actual_paid'];
	
	// 更新主订单的运费
	  $query_form_order = sprintf("update orders set shipping_fee='%s',products_total='%s',should_paid='%s',actual_paid='%s' WHERE id = '%s'",$shipping_fee,$products_total,$should_paid,$actual_paid, $to_order_obj['id']);
	  global $db_conn;
	return  mysql_query($query_form_order,$db_conn) or die(mysql_error());
}

function _update_child_order_product_order_id($from_order_id,$to_order_id){
 	$query_form_order = sprintf("update order_item set order_id='%s' WHERE order_id = '%s'",$to_order_id, $from_order_id);
	global $db_conn;
	return  mysql_query($query_form_order,$db_conn);
}

// 更新merge_to字段
function _update_merge_to($from_order_id,$to_order_id){
 	$query_form_order = sprintf("update orders set merge_to='%s' WHERE id = '%s'",$to_order_id, $from_order_id);
	global $db_conn;
	return  mysql_query($query_form_order,$db_conn) or die(mysql_error());
}

// 通过id获取订单记录
function _get_order_by_id($order_id){
 	$query_form_order = sprintf("SELECT * FROM orders WHERE id = '%s'", $order_id);
	global $db_conn;
	$form_order = mysql_query($query_form_order,$db_conn) or die(mysql_error());
	$row_form_order = mysql_fetch_assoc($form_order);
	$totalRows_form_order = mysql_num_rows($form_order);
	if($totalRows_form_order>0){
		return $row_form_order;
	}
	return false;
}

function _log_order_merge($from_order_obj,$to_order_obj){
	global $db_conn;
	$order_log_sql="insert into order_log(order_id,message)values('".$to_order_obj['id']."','成功将订单号为：'".$from_order_obj['sn']."'的订单合并到:".$to_order_obj['sn'].")";
	return mysql_query($order_log_sql, $db_conn);
}
?>