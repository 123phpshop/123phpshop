<?php 
function export_goods(){
	$hostname_localhost = "localhost";
	$database_localhost = "123phpshopv14";
	$username_localhost = "root";
	$password_localhost = "root";
	$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db($database_localhost, $localhost);
 	$result = mysql_query("select * from product;",$localhost);   
		
	//  获取所有字段的名称
     $str = "id,商品名称,广告语,分类id,产品类型id,分类路径,品牌的id,重量,单位,是否免运费,meta关键词,meta描述,是否为虚拟物品,价格,是否优惠,优惠价格,优惠起始时间,优惠结束时间,市场价,返点的倍数,销量,评价数量,评论数量,咨询数量,上架时间,是否上架,是否为热销产品,是否当季产品,是否为推荐产品,商家备注,标签,库存,添加时间,是否已被删除"."\n";   
    $str = iconv('utf-8','gb2312',$str);   
    while($row=mysql_fetch_array($result))   
   {   
 		$str .=   iconv('utf-8','gb2312',$row['id']).","; 
		$str .=   iconv('utf-8','gb2312',$row['name']).","; 
		$str .=   iconv('utf-8','gb2312',$row['ad_text']).","; 
		$str .=   iconv('utf-8','gb2312',$row['catalog_id']).","; 
		$str .=   iconv('utf-8','gb2312',$row['product_type_id']).","; 
		$str .=   iconv('utf-8','gb2312',$row['cata_path']).","; 
		$str .=   iconv('utf-8','gb2312',$row['brand_id']).","; 
		$str .=   iconv('utf-8','gb2312',$row['weight']).","; 
		$str .=   iconv('utf-8','gb2312',$row['unit']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_shipping_free']).","; 
		$str .=   iconv('utf-8','gb2312',$row['meta_keywords']).","; 
		$str .=   iconv('utf-8','gb2312',$row['meta_desc']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_virtual']).","; 
		//$str .=   iconv('utf-8','gb2312',$row['intro']).","; 
		$str .=   iconv('utf-8','gb2312',$row['price']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_promotion']).","; 
		$str .=   iconv('utf-8','gb2312',$row['promotion_price']).","; 
		$str .=   iconv('utf-8','gb2312',$row['promotion_start']).","; 
		$str .=   iconv('utf-8','gb2312',$row['promotion_end']).","; 
		$str .=   iconv('utf-8','gb2312',$row['market_price']).","; 
		$str .=   iconv('utf-8','gb2312',$row['pointers']).","; 
		$str .=   iconv('utf-8','gb2312',$row['sold_num']).","; 
		$str .=   iconv('utf-8','gb2312',$row['rated_num']).","; 
		$str .=   iconv('utf-8','gb2312',$row['commented_num']).","; 
		$str .=   iconv('utf-8','gb2312',$row['consulted_num']).","; 
		$str .=   iconv('utf-8','gb2312',$row['on_sheft_time']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_on_sheft']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_hot']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_season']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_recommanded']).","; 
		$str .=   iconv('utf-8','gb2312',$row['description']).","; 
		$str .=   iconv('utf-8','gb2312',$row['tags']).","; 
		$str .=   iconv('utf-8','gb2312',$row['store_num']).","; 
		$str .=   iconv('utf-8','gb2312',$row['create_time']).","; 
		$str .=   iconv('utf-8','gb2312',$row['is_delete'])."\n"; 
    }   
    $filename = "商品导出".date('YmdHis').'.csv'; //设置文件名   
    export_csv($filename,$str); //导出 
}	

function export_table_csv($table_name){
	
	$result = mysql_query("select * from ".$table_name);   
	
	//  获取所有字段的名称
	
    $str = "名称,价格,返点,广告词";   
    $str = iconv('utf-8','gb2312',$str);   
    while($row=mysql_fetch_array($result))   
    {   
        $name = iconv('utf-8','gb2312',$row['name']); //中文转码   
        $sex = iconv('utf-8','gb2312',$row['price']);   
        $str .= $name.",".$sex.",".$row['pointers']."\n"; //用引文逗号分开   
    }   
    $filename = "商品导出".date('YmdHis').'.csv'; //设置文件名   
    export_csv($filename,$str); //导出 
}

function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');   
    echo $data;  
	 
}  

function impor_csv(){
	
	// 解析
	
}
 
	
	
