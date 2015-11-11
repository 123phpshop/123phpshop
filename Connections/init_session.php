<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/MySqlSessionHandler.php');

$session = new MySqlSessionHandler();
$session->setDbDetails($hostname_localhost, $username_localhost, $password_localhost, $database_localhost);
$session->setDbTable('session_handler_table');
session_set_save_handler(array($session, 'open'),
                         array($session, 'close'),
                         array($session, 'read'),
                         array($session, 'write'),
                         array($session, 'destroy'),
                         array($session, 'gc'));

register_shutdown_function('session_write_close');
session_start();
