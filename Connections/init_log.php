<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/lib/apache-log4php-2.3.0/Logger.php"; 
Logger::configure($_SERVER["DOCUMENT_ROOT"]."/Connections/log4php.php");
global $glogger;
$glogger = Logger::getRootLogger(); 
$logger = Logger::getRootLogger(); 
$validation = new Form_validation();
?>
