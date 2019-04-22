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
?><?php

require_once ($_SERVER ['DOCUMENT_ROOT'] . '/Connections/localhost.php');
?>
<?php

$colname_product = "-1";
if (isset ( $_GET ['id'] )) {
	$colname_product = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
}

$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ( $_SERVER ['QUERY_STRING'] )) {
	$editFormAction .= "?" . htmlentities ( $_SERVER ['QUERY_STRING'] );
}
// 检查是否输入了验证码？如果么有输入,或是输入的验证码是否和SESSION中的验证码不一致，那么不进行任何操作
if ((isset ( $_POST ["MM_insert"] )) && ($_POST ["MM_insert"] == "new_consult") && $colname_product != '-1' && isset ( $_SESSION ['user_id'] ) && isset ( $_POST ['captcha'] ) && (strtolower($_POST ['captcha']) == $_SESSION ['captcha'])) {
	
	$validation->set_rules ( 'content', '咨询', 'required|max_length[100]|min_length[10]' ); // 评论的长度最少要10个字，最长要100个字
	if ($validation->run ()) { // 如果可以通过验证的话
		$insertSQL = sprintf ( "INSERT INTO product_consult (user_id,content, product_id) VALUES (%s, %s, %s)", GetSQLValueString ( $_SESSION ['user_id'], "int" ), GetSQLValueString ( strip_tags ( $_POST ['content'] ), "text" ), GetSQLValueString ( $colname_product, "int" ) );
		
		
		$Result1 = mysqli_query($localhost,$insertSQL);
		if (! $Result1) {
			// 记录进入日志
			$logger->fatal ( "添加商品咨询时数据库操作失败:" . mysqli_error ($localhost) . $insertSQL );
		}
		$update_sql = sprintf ( "update product set consulted_num=consulted_num+1 where id=%s", GetSQLValueString ( $colname_product, "int" ) );
		$Result2 = mysqli_query($localhost ) or die ( mysqli_error ($localhost),$update_sql);
		if (! $Result1) {
			// 记录进入日志
			$logger->fatal ( "更新商品咨询次数时数据库操作失败:" . mysqli_error ($localhost) . $update_sql );
		}
	}
}

$colname_consult = "-1";
if (isset ( $_GET ['id'] )) {
	$colname_consult = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
}

$query_consult = sprintf ( "SELECT product_consult.*,user.username FROM product_consult inner join user on user.id=product_consult.user_id WHERE product_consult.product_id = %s and product_consult.is_delete = 0 ORDER BY product_consult.id DESC", $colname_consult );
$consult = mysqli_query($localhost,$query_consult);
if (! $consult) {
	// 记录进入日志
	$logger->fatal ( "数据库操作失败:" . mysqli_error ($localhost) . $query_consult );
}
$row_consult = mysqli_fetch_assoc ( $consult );
$totalRows_consult = mysqli_num_rows ( $consult );

?>
<style type="text/css">
<!--
.STYLE2 {
	color: #666
}
-->
</style>
<br />
<style>
#consult_list {
	font-size: 12px;
}
</style>
<?php if ($totalRows_consult > 0) { // Show if recordset not empty ?>
<table width="990" height="31" border="0" align="center" cellpadding="0"
	cellspacing="0">
	<tr>
		<td>
			<table
				style="background-color: white; border-top: 2px solid red; border-bottom-width: 0px"
				width="105" height="33" border="1" cellpadding="0" cellspacing="0"
				bordercolor="#DEDFDE">
				<tr>
					<td><div align="center">
							<a style="text-decoration: none; color: #000000;"
								href="javascript://" name="consult" id="consult">咨询列表</a>[<?php echo $totalRows_consult;?>]</div></td>
				</tr>
			</table>
		</td>

		<td><table style="border-bottom: 1px solid #DEDFDE" width="885"
				height="31" border="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table></td>
	</tr>
</table>
<?php } // Show if recordset not empty ?>
            <?php if ($totalRows_consult > 0) { // Show if recordset not empty ?>
<table id="consult_list" width="990" border="0" align="center"
	cellpadding="0" cellspacing="0" bordercolor="#ddd"
	style="margin: 0px auto; border-bottom: 1px dotted grey;">
            <?php do { ?>
                <tr>
		<td height="18" style="padding-top: 5px;"><div align="left">
				<span class="STYLE2">买家：<?php echo $row_consult['username']; ?> <?php echo $row_consult['create_time']; ?></span>
			</div></td>
	</tr>
 				<?php
														
														$query_replay = "SELECT * FROM product_consult WHERE to_question = " . $row_consult ['id'] . " and is_delete=0 order by id desc limit 1";
														$replay = mysqli_query($localhost ) or die ( mysqli_error ($localhost),$query_replay);
														$row_replay = mysqli_fetch_assoc ( $replay );
														$totalRows_replay = mysqli_num_rows ( $replay );
														
														?>
                 <tr>
		<td height="18" style="padding:5px 0px;<?php if($totalRows_replay==0){ ?>border-bottom:1px dotted grey;<?php }?>"><div
				align="left">咨询：<?php echo $row_consult['content']; ?></div></td>
	</tr>
 				<?php
														if ($totalRows_replay > 0) {
															?>
				 <tr>
		<td height="18"
			style="padding-top: 5px 0px; border-bottom: 1px dotted grey; color: #FF6500;"><div
				align="left">回复：<?php echo $row_replay['content']; ?></div>
			<div style="float: right;">
				<div align="left"><?php echo $row_replay['create_time']; ?></div>
			</div></td>
	</tr>
				 <?php } ?>
            <?php } while ($row_consult = mysqli_fetch_assoc($consult)); ?>
          </table>

<?php } // Show if recordset not empty ?>
<?php if(isset($_SESSION['user_id'])){?>
<form action="<?php echo $editFormAction; ?>" method="post"
	name="new_consult_form" id="new_consult_form">
	<table align="center" width="990" style="margin: 0px auto;">
		<tr valign="baseline">
			<td>&nbsp;</td>
		</tr>
		<tr valign="baseline">
			<td><textarea name="content" id="content" cols="120" rows="10"></textarea></td>
		</tr>
		<tr valign="middle">
			<td style="padding-top: 10px;"><label> <input
					style="height: 35px; font-size: 20px; line-height: 34px;"
					name="captcha" type="text" size="4" maxlength="4" />
			</label><img height="37"
				style="cursor: pointer; float: left; margin-right: 5px;"
				title="点击刷新" src="/kcaptcha/index.php" align="absbottom"
				onclick="this.src='/kcaptcha/index.php?'+Math.random();"><input
				style="height: 35px; margin-left: 5px;" name="submit" type="submit"
				value="马上咨询" /></td>
		</tr>
	</table>
	<input type="hidden" name="MM_insert" value="new_consult" />
</form>
<?php } ?>

<script language="JavaScript" type="text/javascript"
	src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript"
	src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){

	$("#new_consult_form").validate({
        rules: {
        	content: {
                required: true,
				maxlength:100
            },
            captcha: {
                required: true,
				minlength:4
             }
        },
        messages: {
        	content: {
                required: "必填" ,
				maxlength:"最多只能输入100个汉字哦"
            },
            captcha: {
                required: "必填",
				minlength:"至少要输入4个字符哦"
            }
        }
    });
	
});</script>