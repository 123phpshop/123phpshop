<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/Connections/localhost.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Connections/lib/product.php");
$locations=explode("_",$_POST['data'][2]);
$_SESSION['user']['province']=$locations[0];
$_SESSION['user']['city']=$locations[1];
$_SESSION['user']['district']=$locations[2];
echo could_devliver($_POST['data'])?"true":"false";
