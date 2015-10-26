<?php //require_once('Connections/localhost.php'); ?>
<?php require_once('Connections/lib/captcha.php');
$_vc = new Captcha();  //实例化一个对象
$_vc->doimg(); 

if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['comment_captcha'] = $_vc->getCode();
?>