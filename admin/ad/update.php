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

require_once ('../../Connections/localhost.php');
$doc_url = "ad.html#udpate";
$support_email_question = "更新广告";
log_admin ( "更新广告" );
$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ( $_SERVER ['QUERY_STRING'] )) {
	$editFormAction .= "?" . htmlentities ( $_SERVER ['QUERY_STRING'] );
}
try {


	// 处理表单
	if ((isset ( $_POST ["MM_update"] )) && ($_POST ["MM_update"] == "form1")) {

		// 验证参数

		// 检查ad是否存在，如果不存在那么告知

		// 如果存在，那么正开始更新

		$updateSQL = sprintf ( "UPDATE ad SET name=%s, image_width=%s, image_height=%s, intro=%s, start_date=%s, end_date=%s WHERE id=%s", GetSQLValueString ( $_POST ['name'], "text" ), GetSQLValueString ( $_POST ['image_width'], "int" ), GetSQLValueString ( $_POST ['image_height'], "int" ), GetSQLValueString ( $_POST ['intro'], "text" ), GetSQLValueString ( $_POST ['start_date'], "date" ), GetSQLValueString ( $_POST ['end_date'], "date" ), GetSQLValueString ( $_POST ['id'], "int" ) );
		
		mysql_select_db ( $database_localhost, $localhost );
		$Result1 = mysql_query ( $updateSQL, $localhost );
		if (! $Result1) {
			$logger->fatal ( "删除广告操作失败:" . $updateSQL );
			throw new Exception ( COMMON_LANG_SYSTEM_ERROR_PLEASE_TRY_AGAIN_LATER );
		}
		$updateGoTo = "index.php";
		header ( sprintf ( "Location: %s", $updateGoTo ) );
		exit();
	}
	
	$colname_ad = "-1";
	if (isset ( $_GET ['id'] )) {
		$colname_ad = (get_magic_quotes_gpc ()) ? $_GET ['id'] : addslashes ( $_GET ['id'] );
	}
	mysql_select_db ( $database_localhost, $localhost );
	$query_ad = sprintf ( "SELECT * FROM ad WHERE id = %s", $colname_ad );
	$ad = mysql_query ( $query_ad, $localhost );
	if (! $ad) {
		$logger->fatal ( "数据库操作失败:" . $query_ad );
		throw new Exception ( COMMON_LANG_SYSTEM_ERROR_PLEASE_TRY_AGAIN_LATER );
	}
	$row_ad = mysql_fetch_assoc ( $ad );
	$totalRows_ad = mysql_num_rows ( $ad );

	// 检查id是否存在，如果不存在，那么告知
} catch ( Exception $ex ) {
	$error = $ex->getMessage ();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<form method="post" name="form1" id="form1"
		action="<?php echo $editFormAction; ?>">
		<span class="phpshop123_title">广告类型更新</span>
		<?php include_once '/admin/widgets/_error.php';?>
		<div id="doc_help"
			style="display: inline; height: 40px; line-height: 50px; color: #CCCCCC;">
			<a style="color: #CCCCCC; margin-left: 3px;" target="_blank"
				href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a
				style="color: #CCCCCC; margin-left: 3px;" target="_blank"
				href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a
				href=mailto:service@123phpshop.com?subject=我在
				<?php echo $support_email_question;?> 的时候遇到了问题，请支持
				style="color: #CCCCCC; margin-left: 3px;">[邮件支持]</a>
		</div>
		<a href="index.php"> <input style="float: right;" type="submit"
			name="Submit2" value="广告列表" />
		</a>
		<table align="center" class="phpshop123_form_box">
			<tr valign="baseline">
				<td nowrap align="right">名称:</td>
				<td><input name="name" type="text" id="name"
					value="<?php echo $row_ad['name']; ?>" size="32" maxlength="32"> *</td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">宽度:</td>
				<td><input name="image_width" type="text" id="image_width"
					value="<?php echo $row_ad['image_width']; ?>" size="32"
					maxlength="5"> *[像素]</td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">高度:</td>
				<td><input name="image_height" type="text" id="image_height"
					value="<?php echo $row_ad['image_height']; ?>" size="32"
					maxlength="5"> *[像素]</td>
			</tr>
			<tr valign="baseline">
				<td nowrap="nowrap" align="right" valign="top">起始：</td>
				<td><label> <input name="start_date" type="text" id="start_date"
						value="<?php echo $row_ad['start_date']; ?>" />
				</label></td>
			</tr>
			<tr valign="baseline">
				<td nowrap="nowrap" align="right" valign="top">终止：</td>
				<td><input name="end_date" type="text" id="end_date"
					value="<?php echo $row_ad['end_date']; ?>" /></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right" valign="top">介绍:</td>
				<td><textarea name="intro" cols="50" rows="5" id="intro"><?php echo $row_ad['intro']; ?></textarea>
				</td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">&nbsp;</td>
				<td><input type="submit" value="更新记录"></td>
			</tr>
		</table>
		<input type="hidden" name="MM_update" value="form1"> <input
			type="hidden" name="id" value="<?php echo $row_ad['id']; ?>">
	
	</form>
	<script language="JavaScript" type="text/javascript"
		src="../../js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript"
		src="../../js/jquery.validate.min.js"></script>
	<link rel="stylesheet"
		href="/js/jquery-ui-1.11.4.custom/jquery-ui.min.css">
		<script language="JavaScript" type="text/javascript"
			src="../../js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
		<script>
 $(function() {
	$( "#start_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#end_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
});
</script>
		<script>
$().ready(function(){

	$("#form1").validate({
        rules: {
            name: {
                required: true 
            },
            image_width: {
                required: true,
				digits:true
				  
            },
            image_height: {
                required: true,
				digits:true
            } ,
            intro: {
                 maxlength: 1000  
            }
        },
        messages: {
            name: {
                required: "必填" 
            },
            image_width: {
                required: "必填" ,
				digits:"必须是整数哦"
              },
            image_height: {
                required: "必填",
				digits:"必须是整数哦"
            } ,
            intro: {
                 maxlength:"最多1000个字符哦"
            }
        }
    });
	
});</script>

</body>
</html>