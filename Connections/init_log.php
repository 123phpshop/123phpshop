<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/lib/apache-log4php-2.3.0/Logger.php"; 
Logger::configure(array(
    'rootLogger' => array(
        'appenders' => array('default'),
    ),
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
				'ConversionPattern' => "%d{ISO8601} [%p] %c: %m (at %F line %L)%n"
            ),
            'params' => array(
            	'file' => $_SERVER['DOCUMENT_ROOT'].'/logs/%s.php',
            	'append' => true,
				'datePattern' => 'Ymd'
            )
        )
    )
));
global $glogger;
$glogger = Logger::getRootLogger(); 
$logger = Logger::getRootLogger(); 
?>
