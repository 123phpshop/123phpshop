<?php require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);

?>
<?php

function get_logistic_process_info($express_company_id,$express_order_sn){
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
	
}

<?php
mysql_free_result($express_company);
?>