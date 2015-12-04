<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once('../../Connections/lib/order.php'); ?>
<?php
$doc_url="order.html#merge";
$support_email_question="订单合并";
$error="";
if ((isset($_POST["phpshop_db_op"])) && ($_POST["phpshop_db_op"] == "merge_order")) {
	try{
			$from_order_sn	=$_POST['from_order_sn'];
			$to_order_sn	=$_POST['to_order_sn'];
			phpshop123_order_merge($from_order_sn,$to_order_sn);
 	}catch(Exception $ex){
			$error=$ex->getMessage();
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body >
<span class="phpshop123_title">订单合并</span><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<?php if($error!=''){ ?>
<p class="phpshop123_infobox"><?php echo $error;?></p>
<?php } ?>
<form id="order_merge_form" name="order_merge_form" method="post" action="">
  <p>&nbsp;</p>
  <table width="967" border="0" class="phpshop123_form_box">
    <tr>
      <th width="233" scope="row">将
        <label>订单:</label></th>
      <td width="718"><input name="from_order_sn" type="text" id="child_order_sn" placeholder="订单序列号"/>
      </td>
    </tr>
    <tr>
      <th scope="row">合并进入:</th>
      <td><input name="to_order_sn" type="text" id="main_order_sn" placeholder="订单序列号"/>
</td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td><label>
	  <input type="hidden" name="phpshop_db_op" value="merge_order" />
        <input type="submit" name="Submit" value="提交" />
      </label></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#new_consignee_form").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
             },
            mobile: {
                required: true,
                minlength: 11,
				digits:true   
            },
            address: {
                required: true,
                minlength: 3   
            },
 			zip: {
                required: true,
                minlength: 6,
				digits:true
            }
        } 
    });
});</script>
</body>
</html>
