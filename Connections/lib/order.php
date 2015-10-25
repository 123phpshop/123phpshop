<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);

?>
<?php

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
	$quantity=_get_order_quantity();
	
	$shipping_methods=_get_shipping_methods();
 
  	if(count($shipping_methods)==0){
		throw new Exception("不能运送");
	}
	
	// 获取所有激活的没有被删除的快递方式
	//	 循环这些快递方式，获取相应的运费
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
					$shipping_fee_now=_calc_shipping_fee_by_weight($weight,$shipping_methods_item);
				}
				
				//	如果这里是第一个运费方案的话，那么直接设置为这里的运费
		if($is_first_shipping_fee==true || $shipping_fee_now>$shipping_fee){
			$is_first_shipping_fee=false;
			$shipping_fee=$shipping_fee_now;
			$shipping_fee_plan=$shipping_methods_item['shipping_methods_id'];
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
	foreach($_SESSION['cart']['product'] as $product){
		$weight=_get_product_by_id($product['product_id']);
		if(false==$weight){
			throw new Excaption("产品不存在或者已经删除");
		}
		$result+=$weight*(int)$product['quantity'];
	}
	return $result;
}

/**
	获取购物车中产品的数量
**/
function _get_order_quantity(){
	$result=0;
	foreach($_SESSION['cart']['product'] as $product){
		$result+=$product['quantity'];
	}
 	return $result;
}

/**
	获取可以用的配送方式
**/
function _get_shipping_methods(){
	$result=array();
	$province	=$_SESSION[0]['province'];
	$city		=$_SESSION[0]['city'];
	$district	=$_SESSION[0]['district'];
	
	$location[]=$province."_*_*";
	$location[]=$province."_".$city."_*";
	$location[]=$province."_".$city."_".$district;
	
	// 这里需要根据用户的收货地址来选择可以供货的配送方式
   	return	$could_devliver_shipping_methods=_could_devliver_shipping_methods($location);
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
			$shipping_fee=($weight-1000)*$shipping_methods_item['continue_kg_fee']+$shipping_methods_item['first_kg_fee'];
		}else{
			//如果重量<1kg的话
			$shipping_fee=$shipping_methods_item['first_kg_fee'];
		}
 	}
	
	// 如果配送方式是按照500g来计算的话
	if($shipping_methods_item['half_kg_fee']!=null && $shipping_methods_item['continue_half_kg_fee']!=null){
		//	如果重量>=1kg的话
		if($weight>=500){
			$shipping_fee=($weight-500)*$shipping_methods_item['continue_kg_fee']+$shipping_methods_item['first_kg_fee'];
		}else{
			//如果重量<1kg的话
			$shipping_fee=$shipping_methods_item['half_kg_fee'];
		}
 	}
  	
 	return $shipping_methods_item['basic_fee']+$shipping_methods_item['cod_fee']+$shipping_fee;
}

function _get_product_by_id($product_id){
	$query_express_company = "SELECT * FROM products WHERE id = '".$product_id."'";
	$express_company = mysql_query($query_express_company) or die(mysql_error());
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
		$query_area = "SELECT shipping_method_area.*,shipping_method.is_free,shipping_method.is_cod,shipping_method.name as shipping_method_name from shipping_method_area left join shipping_method on shipping_method_area.shipping_method_id=shipping_method_area.id where shipping_method_area.is_delete=0 and shipping_method.is_delete=0 and shipping_method.is_activated=1";
		$area = mysql_query ( $query_area ) or die ( mysql_error () );
		while($order_area=mysql_fetch_assoc($area)){
			foreach($areas as $area_item){
				if(strpos($order_area['area'],$area_item)>-1){
 					$result[]=$order_area;
				}	
			}
		}
		
		return $result;
}
?>