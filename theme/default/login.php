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
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style>
*{
	font-family:"Microsoft Yahei";
}
a{
	text-decoration:none;
	font-size:12px;
	color:black;
}
.STYLE1 {font-size: 20px}
.STYLE2 {font-size: 14px}
.STYLE3 {color: #FF0000}
</style>
</head>
<body>
<table width="990" height="60" border="0" align="center" bgcolor="#ffffff">
   <tr>
     <td valign="middle"><a href="index.php"><img src="<?php echo $row_shopinfo['logo_path']; ?>"   height="60" border="0" style="float:left;" /></a><span style="font-size:30px;line-height:60px;float:left;">欢迎登录</span></td>
   </tr>
</table>
<form id="user_login_form" name="user_register" method="POST" action="<?php echo $loginFormAction; ?>">
<table width="100%" height="475" border="0" bgcolor="#e93854">
  <tr>
    <td>&nbsp;</td>
    <td width="990"><table width="346" height="371" border="0" cellspacing="20" bgcolor="#ffffff" style="float:right;">
      <tr>
        <td><span class="STYLE1">会员登陆</span></td>
        <td><div align="right" class="STYLE2"><a href="register.php" class="STYLE3">立即注册</a></div></td>
      </tr>
      <tr>
        <td colspan="2">
		<?php if(!isset($_GET['error'])){ ?>
		<div style="text-align:center;line-height:26px;height:26px;background-color:#fff6d2;border:1px solid #ffe57d;font-size:12px;">公共场所登录建议进行密码保护，以防账号丢失</div><?php }else{?><div style="color:#e4393c;text-align:center;line-height:26px;height:26px;font-size:12px;background-color:#ffebeb;border:1px solid #e4393c"><?php echo $_GET['error'];?></div><?php } ?></td>
        </tr>
      <tr>
        <td colspan="2" align="center"> 
            <input placeholder="账户" style="padding-left:10px;border:1px solid #bdbdbd;height:38px;width:304px;" name="username" type="text" class="required" id="username" maxlength="18" />
         
           </td>
        </tr>
      <tr>
        <td colspan="2" align="center">
          <input placeholder="密码" style="padding-left:10px;border:1px solid #bdbdbd;height:38px;width:304px;" name="password" type="password" class="required" id="password" maxlength="16" /></td>
        </tr>
		<tr>
        <td colspan="2" align="center">
          <input name="captcha"  class="required" id="captcha" style="padding-left:10px;border:1px solid #bdbdbd;height:38px;width:152px;float:left;" size="4" maxlength="4" placeholder="验证码" /> 
          <img height="37" style="cursor:pointer;" title="点击刷新" src="/kcaptcha/index.php" align="absbottom" onclick="this.src='/kcaptcha/index.php?'+Math.random();" style="float:right;"></td>
        </tr>
		
      <tr>
        <td colspan="2" align="center"><input style="color:white;border:1px solid #e85356;font-size:21px;width:302px;background-color:#e4393c;height:33px;line-height:28px;" type="submit" name="Submit"class="required"    value="提交" /></td>
        </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../../js/jquery.validate.min.js"></script>

<script>
$().ready(function(){

	$("#user_login_form").validate({
        rules: {
            username: {
                required: true 
            },
            password: {
                required: true 
            } 
        },
        messages: {
            username: {
                required: "必填"
            },
            password: {
                required: "必填"
             } 
        }
    });
	
});</script>
<table width="990" height="86" border="0" align="center">
  <tr>
    <td><div align="center"><a rel="nofollow" target="_blank" href=" ">关于我们 </a>| <a rel="nofollow" target="_blank" href=" ">联系我们 </a>| <a rel="nofollow" target="_blank" href=" ">人才招聘 </a>| <a rel="nofollow" target="_blank" href="">商家入驻 </a>| <a rel="nofollow" target="_blank" href=" ">广告服务 </a>|<a rel="nofollow" target="_blank" href=" "> </a> <a target="_blank" href=" ">友情链接 </a>| <a target="_blank" href=" ">销售联盟 </a>| <a target="_blank" href=" " clstag=" ">English Site</a></div></td>
  </tr>
  <tr>
    <td><div align="center">Copyright©2004-2015  123PHPSHOP.com 版权所有</div></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($shopinfo);
?>