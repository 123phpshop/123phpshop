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
$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ( $_SERVER ['QUERY_STRING'] )) {
	$editFormAction .= "?" . htmlentities ( $_SERVER ['QUERY_STRING'] );
}

if ((isset ( $_POST ["MM_update"] )) && ($_POST ["MM_update"] == "form1")) {
	try {
		// 这里首先验证参数，检查参数是否正确
		$validation->set_rules ( 'password', '旧密码', 'required|min_length[8]|max_length[18]|alpha_dash' );
		$validation->set_rules ( 'password2', '新密码', 'required|min_length[8]|max_length[18]|alpha_dash' );
		$validation->set_rules ( 'password3', '新密码确认', 'required|min_length[8]|max_length[18]|alpha_dash|matches[password2]' );
		if (! $validation->run ()) {
			throw new Exception ( "参数错误：" . $validation->error_string ( '', '' ) );
		}
		
		// 检查旧密码是否正确
		$colname_user = "-1";
		if (isset ( $_SESSION ['user_id'] )) {
			$colname_user = (get_magic_quotes_gpc ()) ? $_SESSION ['user_id'] : addslashes ( $_SESSION ['user_id'] );
		}
		mysql_select_db ( $database_localhost, $localhost );
		$query_user = sprintf ( "SELECT id, password FROM `user` WHERE id = %s and is_delete=0  and password= '%s'", $colname_user,md5($_POST['password']) );
		$user = mysql_query ( $query_user, $localhost ) or die ( mysql_error () );
		$row_user = mysql_fetch_assoc ( $user );
		$totalRows_user = mysql_num_rows ( $user );
		if ($totalRows_user == 0) {
			throw new Exception ( "旧密码错误，重试！" );
		}
		
		// 如果旧密码正确，那么进行正式更新
		$updateSQL = sprintf ( "UPDATE user SET password=%s WHERE id=%s", GetSQLValueString ( md5 ( $_POST ['password2'] ), "text" ), GetSQLValueString ( $_SESSION ['user_id'], "int" ) );
		mysql_select_db ( $database_localhost, $localhost );
		$Result1 = mysql_query ( $updateSQL, $localhost );
		if (!$Result1) {
			$logger->fatal("更新用户密码操作错误！".$updateSQL);
			throw new Exception ( "系统错误，请稍后重试！" );
		}
		
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
<link href="../../css/common_user.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<p class="phpshop123_user_title">更新密码</p>
	
	<form method="post" name="form1" id="form1"
		action="<?php echo $editFormAction; ?>">
		<?php if($error!=''){ ?>
			<p class="phpshop123_user_infobox"><?php echo $error;?></p>
		<?php } ?>
		<table align="center" class="phpshop123_user_form_box">
			<tr valign="baseline">
				<td nowrap align="right">旧密码:</td>
				<td><input name="password" type="password" id="password"
					value="" size="32"
					maxlength="16"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">新密码</td>
				<td><input name="password2" type="password" id="password2"
					value="" size="32"
					maxlength="16" /></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">新密码</td>
				<td><input name="password3" type="password" id="password3"
					value="" size="32"
					maxlength="16" /></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">&nbsp;</td>
				<td><input type="submit" value="更新密码"></td>
			</tr>
		</table>
		<input type="hidden" name="MM_update" value="form1">
	
	</form>
	<script language="JavaScript" type="text/javascript"
		src="/js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript"
		src="/js/jquery.validate.min.js"></script>
	<script>
$().ready(function(){

	$("#form1").validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
				remote:{
                    url: "ajax_password.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'password': function(){return $("#password").val();}
                    }
					}
            },
            password2: {
                required: true,
                minlength: 8 
				  
            },
            password3: {
                required: true,
                minlength: 8 ,
				equalTo:"#password2"
            } 
        },
        messages: {
            password: {
                required: "必填",
                minlength: "最少要8个字符哦",
				remote:"旧密码不正确"
            },
            password2: {
                required: "必填",
                minlength: "最少要8个字符哦"
              },
            password3: {
                required: "必填",
                minlength: "最少要8个字符哦",
				equalTo:"两个密码不一致哦"
            } 
        }
    });
	
});</script>
</body>
</html>