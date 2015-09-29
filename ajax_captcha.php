<?php 
$result="true";
if(!isset($_SESSION['captcha']) && $_POST['captcha']!=$_SESSION['captcha']){
	$result="false";
}
echo $result;return;