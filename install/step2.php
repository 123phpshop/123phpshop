<?php
	
include_once($_SERVER['DOCUMENT_ROOT']."/Connections/localhost.php");	 

//	这里需要检查是否已经安装过了，如果已经安装过了，那么直接跳转到首页
$error=array();
if(trim($hostname_localhost)!=''){
	//_to_index();
	//return;
}
	
// 如果没有安装，那么检查数据库是否可以链接，如果不能链接，那么告知

// 如果可以链接，那么进行数据的导入，如果导入失败，那么告知

// 如果导入成功，那么告知安装成功，

function _to_index(){
	$insertGoTo="/index.php";
	header(sprintf("Location: %s", $insertGoTo));

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>123PHPSHOP-安装</title>
<style>
tr{
	height:36px;
}
</style>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if(count($error)>0){ ?>
  <p class="phpshop123_infobox">
  	<?php foreach($error as $error_item){ 
  		echo $error_item;
  	  } ?>
  </p>
<?php } ?>
<form id="install" name="install" method="post" action="">
  <h1 align="center" class="phpshop123_title">上海序程信息科技有限公司123PHPSHOP安装程序</h1>
  <table width="957" border="0" cellpadding="0" cellspacing="0" class="phpshop123_form_box">
    <tr>
      <th width="163" scope="row"><div align="right">数据库地址：</div></th>
      <td width="778"><label>
        <input name="db_host" type="text" value="localhost" maxlength="32" />
      </label></td>
    </tr>
    <tr>
      <th scope="row"><div align="right">数据库名称：</div></th>
      <td><input name="db_name" type="text" value="123phpshop" maxlength="32" /></td>
    </tr>
    <tr>
      <th scope="row"><div align="right">数据库账户：</div></th>
      <td><input name="db_username" type="text" value="root" maxlength="32" /></td>
    </tr>
    <tr>
      <th scope="row"><div align="right">数据库密码：</div></th>
      <td><input name="db_password" type="text" maxlength="32" /></td>
    </tr>
    <tr style="border-top:2px solid #CCCCCC;">
      <th scope="row"><div align="right">后台管理员账户：</div></th>
      <td><label>
        <input name="admin_username" type="text" id="admin_username" value="admin" maxlength="32" />
      </label></td>
    </tr>
    <tr>
	
      <th scope="row"><div align="right">后台管理员密码：</div></th>
      <td><input name="admin_password" type="text" id="admin_password" maxlength="32" /></td>
    </tr>
    <tr>
      <th scope="row"><div align="right">管理员密码确认：</div></th>
      <td><input name="admin_passconf" type="text" id="admin_passconf" maxlength="32" /></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td><label>
        <input type="submit" name="Submit" value="我接受123PHPSHOP服务协议并安装" />
      </label></td>
    </tr>
  </table>
  <p align="center">上海序程信息科技有限公司，版权所有，违者必究</p>
</form>
</body>
</html>
