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
$doc_url = "admin.html#add";
$support_email_question = "添加管理员";
log_admin ( "添加管理员" );
$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ( $_SERVER ['QUERY_STRING'] )) {
	$editFormAction .= "?" . htmlentities ( $_SERVER ['QUERY_STRING'] );
}

if ((isset ( $_POST ["MM_insert"] )) && ($_POST ["MM_insert"] == "form1")) {

	// 参数验证

	$insertSQL = sprintf ( "INSERT INTO member (username, password, mobile, email, mobile_confirmed, role_id) VALUES (%s, %s, %s, %s, %s, %s)", GetSQLValueString ( $_POST ['username'], "text" ), GetSQLValueString ( md5 ( $_POST ['password'] ), "text" ), GetSQLValueString ( $_POST ['mobile'], "text" ), GetSQLValueString ( $_POST ['email'], "text" ), GetSQLValueString ( $_POST ['mobile_confirmed'], "text" ), GetSQLValueString ( $_POST ['role_id'], "int" ) );
	
	
	$Result1 = mysqli_query($localhost,$insertSQL);
	if (! $Result1) {
		$logger->fatal ( __FILE__." :添加管理员数据库操作失败:" . $insertSQL );
		throw new Exception(COMMON_LANG_DB_ERROR);
	}
	$insertGoTo = "index.php"; // 跳转
	if (isset ( $_SERVER ['QUERY_STRING'] )) {
		$insertGoTo .= (strpos ( $insertGoTo, '?' )) ? "&" : "?";
		$insertGoTo .= $_SERVER ['QUERY_STRING'];
	}
	header ( sprintf ( "Location: %s", $insertGoTo ) );
}

// 选择角色

$query_roles = "SELECT * FROM `role` WHERE is_delete = 0";
$roles = mysqli_query($localhost,$query_roles);
if (! $roles) {
	$logger->fatal ( "数据库操作失败:" . $updateSQL );
}
$row_roles = mysqli_fetch_assoc ( $roles );
$totalRows_roles = mysqli_num_rows ( $roles );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<span class="phpshop123_title">添加管理员 </span>
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
	<a href="index.php"><input style="float: right;" type="submit"
		name="Submit2" value="管理员列表" /> </a>

	<form method="post" name="form1" id="form1"
		action="<?php echo $editFormAction; ?>">
		<table align="center" class="phpshop123_form_box">
			<tr valign="baseline">
				<td nowrap align="right">角色:</td>
				<td><label> <select name="role_id" id="role_id">
          <?php
										do {
											?>
          <option value="<?php echo $row_roles['id']?>"><?php echo $row_roles['name']?></option>
          <?php
										} while ( $row_roles = mysqli_fetch_assoc ( $roles ) );
										$rows = mysqli_num_rows ( $roles );
										if ($rows > 0) {
											mysqli_data_seek ( $roles, 0 );
											$row_roles = mysqli_fetch_assoc ( $roles );
										}
										?>
        </select>
				</label></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">账户:</td>
				<td><input name="username" type="text" id="username" value=""
					size="32" maxlength="18"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">密码:</td>
				<td><input name="password" type="password" id="password" value=""
					size="32" maxlength="16"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">确认:</td>
				<td><input name="password2" type="password" id="password2" value=""
					size="32" maxlength="16" /></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">手机:</td>
				<td><input name="mobile" type="text" id="mobile" value="" size="32"
					maxlength="11"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">邮件:</td>
				<td><input name="email" type="text" id="email" value="" size="32"
					maxlength="32"></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="right">&nbsp;</td>
				<td><input type="submit" value="插入记录"></td>
			</tr>
		</table>
		<input type="hidden" name="mobile_confirmed" value="1"> <input
			type="hidden" name="MM_insert" value="form1">
	
	</form>
	<p>&nbsp;</p>
	<script language="JavaScript" type="text/javascript"
		src="../../js/jquery-1.7.2.min.js"></script>
	<script language="JavaScript" type="text/javascript"
		src="../../js/jquery.validate.min.js" charset="UTF-8"></script>

	<script>
$().ready(function(){

	$("#form1").validate({
        rules: {
            username: {
                required: true,
                minlength: 6,
				remote:{
                    url: "ajax_username.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'username': function(){return $("#username").val();}
                    }
					}
            },
            password: {
                required: true,
                minlength: 8   
            },
            password2: {
                required: true,
                minlength: 8 ,
				equalTo:"#password"
            },
			mobile: {
				
                required: true,
                minlength: 11,
				remote:{
                    url: "ajax_mobile.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'mobile': function(){return $("#mobile").val();}
                    }
					}
            },
			email: {
				email:true,
                required: true,
                minlength: 6,
				remote:{
                    url: "ajax_email.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'email': function(){return $("#email").val();}
                    }
					}
            }
			
        },
        messages: {
			username: {
  				remote:"用户名已存在"
            },
 			email: {
  				remote:"邮件地址已经存在"
            },
			mobile: {
                
 				remote:"手机号码已经存在"
            }
        }
    });
	
});</script>
</body>
</html>
<?php
mysqli_free_result ( $roles );
?>