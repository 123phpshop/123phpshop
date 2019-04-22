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
 ?>
<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="links.html#add";
$support_email_question="添加友情链接";
log_admin($support_email_question);
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  // 验证参数

  $insertSQL = sprintf("INSERT INTO friend_links (link_text, link_url, sort) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['link_text'], "text"),
                       GetSQLValueString($_POST['link_url'], "text"),
                       GetSQLValueString($_POST['sort'], "int"));

  
  $Result1 = mysqli_query($localhost,$insertSQL);
  if(!$Result1){$logger->fatal("数据库操作失败:".$insertSQL);}

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
<span class="phpshop123_title">添加链接 </span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php">
<input style="float:right;" type="submit" name="Submit2" value="友情链接列表" />
</a>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" >
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">连接文字:</td>
      <td><input name="link_text" type="text" value="" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">连接网址:</td>
      <td><input type="text" name="link_url" value="http://www." size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">排序:</td>
      <td><input type="text" name="sort" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">上传图片：</td>
      <td><label>
        <input type="file" name="file" />
        [购买此功能]
      </label></td>
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
	<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script><br />
<script>
$().ready(function(){
 	$("#form1").validate({
        rules: {
             link_text: {
                required: true,
				minlength: 2,
             },
            link_url: {
                required: true,
                minlength: 16,
				url:true
				   
            },
            sort: {
 				digits:true  
            }
        } 
    });
});</script>
</body>
</html>