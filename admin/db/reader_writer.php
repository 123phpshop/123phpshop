<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php 
$support_email_question="设置数据库读写分离";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">数据库读写分离</span><div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://baike.baidu.com/link?url=-4O22FjOY_e-cS_CJTa34UEABLHcDqJZcNC3V0f9a0qcoCQJdScCxt_NeJNDWXu_Shw-S5IHG0_4_-XOKvxrp_";?>[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes";?>[购买]</a></div>
<div class="phpshop123_infobox">
<p>为什么要进行数据库读写分离：</p>
<p>1.多台数据库备份，一台服务器崩溃，其他服务器就可以马上代替其工作，可以大幅度提升网站的稳定性。</p>
<p>2.多台服务器共同为数据库操作服务，可以大幅度降低单个服务器压力，提升整个网站的反应性能。</p>
</div>
<form id="form1" name="form1" method="post" action="">
  <p>读数据设置：</p>
  <table width="200" border="0" class="phpshop123_form_box">
    <tr>
      <th scope="row">读数据库地址</th>
      <td><label>
        <input type="text" name="textfield" value="<?php echo $hostname_localhost;?>"/>
      </label></td>
    </tr>
    <tr>
      <th scope="row">读数据库名称</th>
      <td><input type="text" name="textfield2" value="<?php echo $database_localhost;?>"/></td>
    </tr>
    <tr>
      <th scope="row">读数据库账户</th>
      <td><input name="textfield3" type="password" value="123phpshop" /></td>
    </tr>
    <tr>
      <th scope="row">读数据库密码</th>
      <td><input name="textfield4" type="password" value="123phpshop" /></td>
    </tr>
  </table>
  <p>写数据库设置：</p>
  <table width="200" border="0" class="phpshop123_form_box">
    <tr>
      <th scope="row">写数据库地址</th>
      <td><label>
      <input type="text" name="textfield5" value="<?php echo $hostname_localhost;?>"/>
      </label></td>
    </tr>
    <tr>
      <th scope="row">写数据库名称</th>
      <td><input type="text" name="textfield22" value="<?php echo $database_localhost;?>"/></td>
    </tr>
    <tr>
      <th scope="row">写数据库账户</th>
      <td><input name="textfield32" type="password" value="123phpshop" /></td>
    </tr>
    <tr>
      <th scope="row">写数据库密码</th>
      <td><input name="textfield42" type="password" value="123phpshop" /></td>
    </tr>
  </table>
</form>
<p>&nbsp; </p>
</body>


</html>
