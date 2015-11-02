<?php 
function export_goods(){
	export_table_csv("product");die;
}
function export_table_csv($table_name){
	
	$result = mysql_query("select * from ".$table_name);   
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
 
	
	
