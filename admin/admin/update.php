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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="admin.html#update";
$support_email_question="更新管理员信息";
log_admin($support_email_question);
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

  // 检查参数，如果参数不合法，那么告知

  // 检查id是否存在，如果不存在，那么抛错

  // 如果参数合法，那么正是开始更新
  $updateSQL = sprintf("UPDATE member SET password=%s, username=%s, mobile=%s, email=%s, role_id=%s WHERE id=%s",
                       GetSQLValueString(md5($_POST['password']), "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['mobile'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
					             GetSQLValueString($_POST['role_id'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  // 如果用户没有填写密码的话，那么不更新密码字段
	if(empty($_POST['password']) || !isset($_POST['password'])){
 		$updateSQL = sprintf("UPDATE member SET   username=%s, mobile=%s, email=%s, role_id=%s WHERE id=%s",
                        GetSQLValueString($_POST['username'], "text"),
                        GetSQLValueString($_POST['mobile'], "text"),
                        GetSQLValueString($_POST['email'], "text"),
					              GetSQLValueString($_POST['role_id'], "text"),
                 	      GetSQLValueString($_POST['id'], "int"));
	}
  
  
  
  $Result1 = mysqli_query($localhost,$updateSQL);
  if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_admin = "-1";
if (isset($_GET['id'])) {
  $colname_admin = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$query_admin = sprintf("SELECT * FROM member WHERE id = %s", $colname_admin);
$admin = mysqli_query($localhost);if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL,$query_admin);}
$row_admin = mysqli_fetch_assoc($admin);
$totalRows_admin = mysqli_num_rows($admin);

// 如果找不到这个id的话

$query_roles = "SELECT * FROM `role` WHERE is_delete = 0";
$roles = mysqli_query($localhost,$query_roles);
if(!$Result1){$logger->fatal("数据库操作失败:".$updateSQL);}
$row_roles = mysqli_fetch_assoc($roles);
$totalRows_roles = mysqli_num_rows($roles);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
 
<span class="phpshop123_title">管理员信息更新</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
</p>
<p>&nbsp; </p>
<input style="float:right;" type="submit" name="Submit2" value="管理员列表" />

<form action="<?php echo $editFormAction; ?>" method="post" name="admin_update_form" id="admin_update_form">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">角色:</td>
      <td><label>
        <select name="role_id">
          <?php
do {  
?>
          <option value="<?php echo $row_roles['id']?>"<?php if ($row_roles['id']==$row_admin['role_id']) {echo "selected=\"selected\"";} ?>><?php echo $row_roles['name']?></option>
          <?php
} while ($row_roles = mysqli_fetch_assoc($roles));
  $rows = mysqli_num_rows($roles);
  if($rows > 0) {
      mysqli_data_seek($roles, 0);
	  $row_roles = mysqli_fetch_assoc($roles);
  }
?>
        </select>
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">账号:</td>
      <td><input name="username" id="username"  type="text" value="<?php echo $row_admin['username']; ?>" size="32" maxlength="16" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">密码:</td>
      <td><input name="password"  id="password"  type="password" value="" size="32" maxlength="16" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">确认：</td>
      <td><input name="password2"  id="password2"  type="password" value="" size="32" maxlength="16" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">手机:</td>
      <td><input name="mobile"  id="mobile"  type="text" value="<?php echo $row_admin['mobile']; ?>" size="32" maxlength="11"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">邮箱:</td>
      <td><input name="email"  id="email"  type="text" value="<?php echo $row_admin['email']; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新记录"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id"  id="id" value="<?php echo $row_admin['id']; ?>">
</form>
<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>	
<script language="JavaScript" type="text/javascript" src="../../js/jquery.validate.min.js" charset="UTF-8"></script>

<script>
$().ready(function(){

	$("#admin_update_form").validate({
        rules: {
            username: {
                required: true,
                minlength: 6,
				remote:{
                    url: "ajax_update_username.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'username': function(){return $("#username").val();},
						'id': function(){return $("#id").val();}
                    }
					}
            },
            password: {
                 minlength: 8   
            },
            password2: {
                minlength: 8 ,
				equalTo:"#password"
            },
			mobile: {
                 minlength: 11,
				remote:{
                    url: "ajax_update_mobile.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'mobile': function(){return $("#mobile").val();},
						'id': function(){return $("#id").val();}
                    }
					}
            },
			email: {
				email:true,
                required: true,
                minlength: 6,
				remote:{
                    url: "ajax_update_email.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'email': function(){return $("#email").val();},
						'id': function(){return $("#id").val();}
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
mysqli_free_result($admin);

mysqli_free_result($roles);
?>