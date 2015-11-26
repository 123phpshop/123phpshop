<?php

error_reporting (E_ALL);

include('kcaptcha.php');
  
$captcha = new KCAPTCHA();
  	include_once($_SERVER['DOCUMENT_ROOT']."/Connections/localhost.php");
	$_SESSION['captcha'] = $captcha->getKeyString();
 

?>