<?php require_once('../../Connections/localhost.php'); ?>
<?php
$json_result=array('code'=>'0','message'=>'SUCCEED');

try{
	$colname_ed = "-1";
	if (isset($_POST['magazine_id'])) {
	  $colname_ed = (get_magic_quotes_gpc()) ? $_POST['magazine_id'] : addslashes($_POST['magazine_id']);
	}
	
	$query_ed = sprintf("SELECT * FROM edm_news WHERE id = %s and is_delete=0 ", $colname_ed);
	$ed = mysqli_query($localhost,$query_ed)or die(mysqli_error($localhost).$query_ed);
	$row_ed = mysqli_fetch_assoc($ed);
	$totalRows_ed = mysqli_num_rows($ed);
	
	if($totalRows_ed==0){
		$logger->fatal("添加邮件营销列队的时候发生错误,杂志的id是：".$colname_ed );
		throw new Exception("杂志不存在，或被删除");
	}
	
	
	$query_subscribe_emails = "insert into edm_list(email,magzine_id) SELECT email,".$colname_ed." FROM email_subscribe WHERE is_delete = 0"; 
	$subscribe_emails = mysqli_query($localhost,$query_subscribe_emails);
	if(!$subscribe_emails){
		$logger->fatal("添加邮件营销列队的时候发生错误,杂志的id是：".$colname_ed." 错误：".mysqli_error($localhost) );
		throw new Exception("系统错误，请联系管理员或是123phpshop进行咨询");
	}
 }catch(Exception $ex){
	$json_result['code']='1';
  	$json_result['message']=$ex->getMessage();
}
	die( json_encode($json_result));

?>