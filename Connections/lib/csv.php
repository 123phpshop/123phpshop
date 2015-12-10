<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
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

function import_product($file_path){
 	$max_fields_length=28;
	
	// 获取这个文件，如果文件不存在，那么告知
	if(!file_exists($_SERVER['DOCUMENT_ROOT'].$file_path)){
		throw new Exception("csv文件不存在");
	}
	
	// 获取文件内容
	$file = file($_SERVER['DOCUMENT_ROOT'].$file_path);
	if(count($file)==0){
		throw new Exception("csv文件为空");
	}
	$_is_first_row=true;
	foreach($file as &$line){
 		$fields_array=explode(",",$line);
		if($_is_first_row==false){
			if(count($fields_array)!=$max_fields_length){
				throw new Exception("csv文件格式不正确".count($fields_array));
				return;
			}
  			$product_name=iconv("GB2312", "UTF-8", $fields_array[0]);
			$fields_array[0]=iconv("GB2312", "UTF-8", $fields_array[0]);
			$fields_array[1]=iconv("GB2312", "UTF-8", $fields_array[1]);
			$fields_array[2]=iconv("GB2312", "UTF-8", $fields_array[2]);
			$fields_array[3]=iconv("GB2312", "UTF-8", $fields_array[3]);
			$fields_array[4]=iconv("GB2312", "UTF-8", $fields_array[4]);
			$fields_array[5]=iconv("GB2312", "UTF-8", $fields_array[5]);
			$fields_array[6]=iconv("GB2312", "UTF-8", $fields_array[6]);
			$fields_array[7]=iconv("GB2312", "UTF-8", $fields_array[7]);
			$fields_array[8]=iconv("GB2312", "UTF-8", $fields_array[8]);
			$fields_array[9]=iconv("GB2312", "UTF-8", $fields_array[9]);
			$fields_array[10]=iconv("GB2312", "UTF-8", $fields_array[10]);
			$fields_array[11]=iconv("GB2312", "UTF-8", $fields_array[11]);
			$fields_array[12]=iconv("GB2312", "UTF-8", $fields_array[12]);
			$fields_array[13]=iconv("GB2312", "UTF-8", $fields_array[13]);
			$fields_array[14]=iconv("GB2312", "UTF-8", $fields_array[14]);
			$fields_array[15]=iconv("GB2312", "UTF-8", $fields_array[15]);
			$fields_array[16]=iconv("GB2312", "UTF-8", $fields_array[16]);
			$fields_array[17]=iconv("GB2312", "UTF-8", $fields_array[17]);
			$fields_array[18]=iconv("GB2312", "UTF-8", $fields_array[18]);
			$fields_array[19]=iconv("GB2312", "UTF-8", $fields_array[19]);
			$fields_array[20]=iconv("GB2312", "UTF-8", $fields_array[20]);
			$fields_array[21]=iconv("GB2312", "UTF-8", $fields_array[21]);
			$fields_array[22]=iconv("GB2312", "UTF-8", $fields_array[22]);
			$fields_array[23]=iconv("GB2312", "UTF-8", $fields_array[23]);
			$fields_array[24]=iconv("GB2312", "UTF-8", $fields_array[24]);
			$fields_array[25]=iconv("GB2312", "UTF-8", $fields_array[25]);
			$fields_array[26]=iconv("GB2312", "UTF-8", $fields_array[26]);
			$fields_array[27]=iconv("GB2312", "UTF-8", $fields_array[27]);
  			
 			// 这里需要检查产品名称是否重复，如果重复，那么告知
			global $db_conn;
 			//mysql_select_db($database_localhost, $db_conn);
			 $query_get_product_by_id = "SELECT id, name FROM product WHERE name = '".trim($product_name)."'";
 			$get_product_by_id = mysql_query($query_get_product_by_id, $db_conn) or die(mysql_error());
			$row_get_product_by_id = mysql_fetch_assoc($get_product_by_id);
			$totalRows_get_product_by_id = mysql_num_rows($get_product_by_id);
			if($totalRows_get_product_by_id >0){
					throw new Exception("商品名称重复:".$product_name);
 			}
			
			$new_product_sql="insert into product(name,ad_text,catalog_id,product_type_id,cata_path,brand_id,weight,unit,is_shipping_free,meta_keywords,meta_desc,is_virtual,intro,price,is_promotion,promotion_price,promotion_start,promotion_end,market_price,pointers,is_on_sheft,is_hot,is_season,is_recommanded,description,tags,store_num,is_delete)values('".$fields_array[0]."','".$fields_array[1]."','".$fields_array[2]."','".$fields_array[3]."','".$fields_array[4]."','".$fields_array[5]."','".$fields_array[6]."','".$fields_array[7]."','".$fields_array[8]."','".$fields_array[9]."','".$fields_array[10]."','".$fields_array[11]."','".$fields_array[12]."','".$fields_array[13]."','".$fields_array[14]."','".$fields_array[15]."','".$fields_array[16]."','".$fields_array[17]."','".$fields_array[18]."','".$fields_array[19]."','".$fields_array[20]."','".$fields_array[21]."','".$fields_array[22]."','".$fields_array[23]."','".$fields_array[24]."','".$fields_array[25]."','".$fields_array[26]."','".$fields_array[27]."')";
			mysql_query($new_product_sql);
  		}
 		$_is_first_row=false;
 	}
 }