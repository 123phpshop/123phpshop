<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?>
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/Connections/check_install.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Connections/const.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/init_session.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Connections/lib/common.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Connections/lib/cart.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Connections/check_admin_login.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Connections/check_user_login.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Connections/check_access.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/Connections/lib/apache-log4php-2.3.0/Logger.php"; 
Logger::configure($_SERVER["DOCUMENT_ROOT"]."/Connections/log4php.123phpshop.properties");
$logger = Logger::getRootLogger(); 