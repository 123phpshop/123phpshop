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
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
<!--
body {
	background-color: #f2f2f2;
	margin: 0px;
}
.STYLE1 {font-size: 12px}
.register_input{
 	width:238px;
	height:16px;
	padding:10px 25px 10px 5px;
	border:1px solid #cccccc;
}
#register_submit{
	font-size:14px;
	font-weight:bold;
	width:270px;
	height:36px;
	color:white;
	background-color:#FF0000;
	border:1px solid #FF0000;
}

.simple_bottom{
	font-size:12px;
}

.simple_bottom a{
	text-decoration:none;
	color:#000000;
}
.STYLE2 {color: #FF0000;line-height:20px;text-align:center;}
-->
</style></head>

<body>
<?php include($template_path.'/widget/top_full_nav_reg.php'); ?>
<table width="990" height="60" border="0" style="margin:10px auto;">
  <tr>
    <td valign="middle"><a href="index.php"><img src="<?php echo $row_shopinfo['logo_path']; ?>"   height="60" border="0" style="float:left;" /></a><span style="font-size:30px;line-height:60px;float:left;">欢迎注册</span></td>
  </tr>
</table>

<form id="register_form" method="post" name="form1" action="<?php echo $editFormAction; ?>">
  
  <table width="989" height="31" border="0" align="center" bgcolor="#f2f2f2">
    <tr>
      <td><div align="right" class="STYLE1">我已经注册，现在就 <a href="login.php" style="text-decoration:none;">登录</a></div></td>
    </tr>
  </table>
 
 <div style="border:1px solid #dddddd;width:989px;margin:0 auto;">
  <table width="989" height="585" border="0" align="center" cellspacing="100" bordercolor="#dddddd" bgcolor="#FFFFFF">
     
    <tr align="left" valign="top">
      <td width="750" valign="top">
	  <?php if($error!=''){ ?>
	  <div id="error_message">
        <div align="center" class="STYLE2">
          <div align="left" style="padding-left:100px;"><?php echo $error;?></div>
        </div>
      </div>
	  <?php } ?>
      <table align="center">
        <tr valign="baseline">
          <td height="60" align="right" nowrap="nowrap">账户:</td>
          <td height="60"><input  name="username" id="username"  type="text" class="required register_input" value="<?php  echo_post($_POST['username']);?>" size="32" maxlength="18" />
            *</td>
        </tr> 
        <tr valign="baseline">
          <td height="60" align="right" nowrap="nowrap">密码:</td>
          <td height="60"><input    name="password" id="password"  type="password" class="required register_input" value="<?php  echo_post($_POST['password']);?>" size="18" maxlength="18" />
            *</td>
        </tr>
        <tr valign="baseline">
          <td height="60" align="right" nowrap="nowrap">确认:</td>
          <td height="60"><input  name="passconf" type="password" class="required register_input" id="passconf " value="<?php  echo_post($_POST['passconf']);?>" size="18" maxlength="18" />
            *</td>
        </tr>
        <tr valign="baseline">
          <td height="60" align="right" nowrap="nowrap">&nbsp;</td>
          <td height="60"><span class="STYLE1">
            <label>
            <input name="agree" type="checkbox" id="agree" value="checkbox" checked="checked" />
            我已阅读并同意《注册协议》            </label>
          </span></td>
        </tr>
        <tr valign="top">
          <td height="60" align="right" nowrap="nowrap">&nbsp;</td>
          <td height="60"><input id="register_submit" name="submit" type="submit" value="立即注册" /></td>
        </tr>
      </table></td>
      <td align="left" valign="top"><table width="220" height="182" border="1" cellpadding="0" cellspacing="0" bordercolor="#f2f2f2">
        <tr>
          <td bordercolor="#f2f2f2"><img src="../../uploads/image/IMG_6548.JPG" width="256" height="256" border="0"/></td>
        </tr>
      </table></td>
    </tr>
  </table>
   <input type="hidden" name="MM_insert" value="form1">
    
</form>
</div>
<p>
  <script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
  <script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
   <script>
$().ready(function(){
 	$("#register_form").validate({
        rules: {
            username: {
                required: true,
                minlength: 6,
				maxlength: 18,
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
                minlength: 8,
				maxlength: 18,
            },
            passconf: {
                required: true,
                minlength: 8 ,
				equalTo:"#password",
				maxlength: 18
            } 
        },
        messages: {
            username: {
                required: "必填",
                minlength: "最少要6个字符哦",
				maxlength: "最多18个字符哦",
				remote:"用户名已经被占用"
            },
            password: {
                required: "必填",
				maxlength: "最多18个字符哦",
                minlength: "最少要8个字符哦",
              },
            passconf: {
                required: "必填",
				maxlength: "最多18个字符哦",
                minlength: "最少要8个字符哦",
 				equalTo:"两个密码不一致哦"
            } 
        }
    });
	
});</script>
</p>
<table width="990" height="86" border="0" align="center" class="simple_bottom">
  <tr>
    <td><div align="center"><a rel="nofollow" target="_blank" href=" ">关于我们 </a>| <a rel="nofollow" target="_blank" href=" ">联系我们 </a>| <a rel="nofollow" target="_blank" href=" ">人才招聘 </a>| <a rel="nofollow" target="_blank" href="">商家入驻 </a>| <a rel="nofollow" target="_blank" href=" ">广告服务 </a>|<a rel="nofollow" target="_blank" href=" "> </a> <a target="_blank" href=" ">友情链接 </a>| <a target="_blank" href=" ">销售联盟 </a>| <a target="_blank" href=" " clstag=" ">English Site</a></div></td>
  </tr>
  <tr>
    <td><div align="center">Copyright©2004-2015  123PHPSHOP.com 版权所有</div></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($shopinfo);

isset($get_user_by_username)?mysql_free_result($get_user_by_username):'';
?>