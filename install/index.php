<?php
	
include_once($_SERVER['DOCUMENT_ROOT']."/Connections/localhost.php");	 

//	这里需要检查是否已经安装过了，如果已经安装过了，那么直接跳转到首页
 
if(trim($hostname_localhost)==''){
	 
	_to_index();
	return;
}
	
//	如果没有安装，那么
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
</head>

<body>
<form id="install" name="install" method="post" action="">
  <h1>123PHPSHOP安装程序</h1>
  <table width="957" height="167" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="163" scope="row"><div align="right">数据库主机名：</div></td>
      <td width="778"><label>
        <input type="text" name="db_host" />
      </label></td>
    </tr>
    <tr>
      <td scope="row"><div align="right">数据库名称：</div></td>
      <td><input type="text" name="db_name" /></td>
    </tr>
    <tr>
      <td scope="row"><div align="right">数据库连接用户名：</div></td>
      <td><input type="text" name="db_username" /></td>
    </tr>
    <tr>
      <td scope="row"><div align="right">数据库连接密码：</div></td>
      <td><input type="text" name="db_password" /></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td><label>
        <input name="checkbox" type="checkbox" value="checkbox" checked="checked" />
        我接受<a href="license.php">123PHPSHOP服务协议</a>      </label></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td><label>
        <input type="submit" name="Submit" value="安装" />
      </label></td>
    </tr>
  </table>
</form>
</body>
</html>
