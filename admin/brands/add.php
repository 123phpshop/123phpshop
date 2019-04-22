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
$doc_url = "brand.html#add";
$support_email_question = "添加品牌";
log_admin ( $support_email_question );
$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ( $_SERVER ['QUERY_STRING'] )) {
	$editFormAction .= "?" . htmlentities ( $_SERVER ['QUERY_STRING'] );
}

// 如果有文件上传的话
if ((isset ( $_POST ["MM_insert"] )) && ($_POST ["MM_insert"] == "form1")) {
	try {
		// @todo 这里需要进行服务器方面参数的验证，如果验证不通过，那么告知
		$insertSQL = sprintf ( "INSERT INTO brands (name, url, sort, `desc`) VALUES (%s,  %s, %s, %s)", GetSQLValueString ( $_POST ['name'], "text" ), GetSQLValueString ( $_POST ['url'], "text" ), GetSQLValueString ( $_POST ['sort'], "int" ), GetSQLValueString ( $_POST ['desc'], "text" ) );
		// 如果有文件上传的话
		if (! empty ( $_FILES ['image_path'] ['tmp_name'] )) {
			$glogger->debug('貌似有文件要上传！');
			include ($_SERVER ['DOCUMENT_ROOT'] . '/Connections/lib/upload.php');
			
			$up = new fileupload ();
			// 设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up->set ( "path", $_SERVER ['DOCUMENT_ROOT'] . "/uploads/brands/" );
			$up->set ( "maxsize", 2000000 );
			$up->set ( "allowtype", array (
					"gif",
					"png",
					"jpg",
					"jpeg" 
			) );
			$up->set ( "israndname", true );
			
			// 使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
			if ($up->upload ( "image_path" )) {
				$image_path = "/uploads/brands/" . $up->getFileName ();
				$glogger->debug('文件上传成功！'.$image_path);
				$insertSQL = sprintf ( "INSERT INTO brands (name, image_path, url, sort, `desc`) VALUES (%s, %s, %s, %s, %s)", GetSQLValueString ( $_POST ['name'], "text" ), GetSQLValueString ( $image_path, "text" ), GetSQLValueString ( $_POST ['url'], "text" ), GetSQLValueString ( $_POST ['sort'], "int" ), GetSQLValueString ( $_POST ['desc'], "text" ) );
			} else {
				
				$glogger->fatal($up->getErrorMsg ());
				// 获取上传失败以后的错误提示
				throw new Exception ( $up->getErrorMsg () );
			}
		}
		
		$logger->debug(__FILE__." ".$insertSQL);
		// 这里需要检查是否有问题，如果没有问题，那么执行添加品牌的操作
		mysql_select_db ( $database_localhost, $localhost );
		$Result1 = mysql_query ( $insertSQL, $localhost );
		if (! $Result1) {
			$logger->fatal ( "数据库操作失败:" . $insertSQL );
		}
		
		// 开始跳转
		$insertGoTo = "index.php";
		header ( sprintf ( "Location: %s", $insertGoTo ) );
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
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<span class="phpshop123_title">添加品牌</span>
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
		name="Submit2" value="品牌列表" />
	</a>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/_error.php');?>
<form action="<?php echo $editFormAction; ?>" method="post"
		enctype="multipart/form-data" name="form1" id="form1">
		<table align="center" class="phpshop123_form_box">
			<tr valign="baseline">
				<td nowrap align="right">名称:</td>
				<td><input type="text" name="name" value="" size="32" maxlength="32">
						*</td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">图片:</td>
				<td><input type="file" name="image_path" value="" size="32"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">网址:</td>
				<td><input name="url" type="text" value="http://" size="32"
					maxlength="60"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">排序:</td>
				<td><input name="sort" type="text" value="" size="32" maxlength="10"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right" valign="top">介绍:</td>
				<td><textarea name="desc" cols="50" rows="5"></textarea></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">&nbsp;</td>
				<td><input type="submit" value="添加"></td>
			</tr>
		</table>
		<input type="hidden" name="MM_insert" value="form1">
	
	</form>
	<script language="JavaScript" type="text/javascript"
		src="/js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript"
		src="/js/jquery.validate.min.js"></script>
	<script>
$().ready(function(){

	$("#form1").validate({
        rules: {
            name: {
                required: true
            },
			url:{
				url:true
			}, 
 			sort:{
				digits:true
			},
 			desc:{
				maxlength:100
			}
             
        },
        messages: {
            name: {
                required: "必填" 
            },
			url:{
				url:"网址格式不正确"
			}, 
 			sort:{
				digits:"只能是数字哦"
			},
 			desc:{
				maxlength:"最多只能100个字符哦"
			}
        }
    });
	
});</script>
</body>
</html>