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

?>
<?php require_once('../../Connections/lib/order.php'); ?>
<?php

$doc_url = "order.html#merge";
$support_email_question = "订单合并";
$error = "";
if ((isset ( $_POST ["phpshop_db_op"] )) && ($_POST ["phpshop_db_op"] == "merge_order")) {
	try {
		
		// 检查子订单是否存在
		$colname_from_order = "-1";
		if (isset($_POST['from_order_sn'])) {
		  $colname_from_order = (get_magic_quotes_gpc()) ? $_POST['from_order_sn'] : addslashes($_POST['from_order_sn']);
		}
		mysql_select_db($database_localhost, $localhost);
		$query_from_order = sprintf("SELECT * FROM orders WHERE sn = '%s' and is_delete=0", trim($colname_from_order));
		$from_order = mysql_query($query_from_order, $localhost) or die(mysql_error());
		$row_from_order = mysql_fetch_assoc($from_order);
		$totalRows_from_order = mysql_num_rows($from_order);
		if($totalRows_from_order==0){
			throw new Exception("订单序列号为：".trim($colname_from_order)."订单不存在");
		}
		
		// 检查主订单是否存在
		$colname_to_order = "-1";
		if (isset($_POST['to_order_sn'])) {
		  $colname_to_order = (get_magic_quotes_gpc()) ? $_POST['to_order_sn'] : addslashes($_POST['to_order_sn']);
		}
		mysql_select_db($database_localhost, $localhost);
		$query_to_order = sprintf("SELECT * FROM orders WHERE sn = '%s' and is_delete=0", trim($colname_to_order));
		$to_order = mysql_query($query_to_order, $localhost) or die(mysql_error());
		$row_to_order = mysql_fetch_assoc($to_order);
		$totalRows_to_order = mysql_num_rows($to_order);
	
		if($totalRows_to_order==0){
			throw new Exception("订单序列号为：".trim($colname_to_order)."订单不存在");
		}
		
		// 正式开始合并
		$from_order_sn 	= trim($_POST ['from_order_sn']);
		$to_order_sn 	= trim($_POST ['to_order_sn']);
		phpshop123_order_merge ( $from_order_sn, $to_order_sn );
	} catch ( Exception $ex ) {
		$error = $ex->getMessage ();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<span class="phpshop123_title">订单合并</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php");?>
<a href="index.php">
<input style="float:right;" type="submit" name="Submit2" value="订单列表" />
</a>
<form id="order_merge_form" name="order_merge_form" method="post"
		action="">
		<table width="967" border="0" class="phpshop123_form_box">
			<tr>
				<th width="233" scope="row">将 <label>订单:</label></th>
				<td width="718"><input name="from_order_sn" type="text"
					id="from_order_sn" placeholder="订单序列号" /></td>
			</tr>
			<tr>
				<th scope="row">合并进入:</th>
				<td><input name="to_order_sn" type="text" id="to_order_sn"
					placeholder="订单序列号" /></td>
			</tr>
			<tr>
				<th scope="row">&nbsp;</th>
				<td><input type="hidden" name="phpshop_db_op" value="merge_order" />
					<input type="submit" name="Submit" value="提交" /></td>
			</tr>
		</table>
</form>
	<script language="JavaScript" type="text/javascript"
		src="/js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript"
		src="/js/jquery.validate.min.js"></script>
	<script>
$().ready(function(){
 	$("#order_merge_form").validate({
        rules: {
        	from_order_sn: {
                required: true,
				minlength: 22,
				maxlength: 22,
				digits:true
             },
            to_order_sn: {
                required: true,
                minlength: 22,
				maxlength: 22,
				digits:true
            }
        } 
    });
});</script>
</body>
</html>
<?php
mysql_free_result($from_order);

mysql_free_result($to_order);
?>