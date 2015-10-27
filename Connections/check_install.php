<?php

/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
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
$install_url = '/install/';
$home_url = '/index.php';

//	检查当前是不属于安装区域
if (!_is_install_area ()) {
 
  	//	检查是否已经安装，如果没有安装，那么跳转到安装区域
 	 if(!isset($hostname_localhost) || trim($hostname_localhost)=="") {
	 	header ( sprintf ( "Location: %s", $install_url ) );
	 }
}else{
//	检查是否已经安装，如果没有安装，那么跳转到安装区域
 	 if(isset($hostname_localhost) && trim($hostname_localhost)!="") {
	 	header ( sprintf ( "Location: %s", $home_url ) );
	 }

}

/**
 * 检查当前页面是否属于管理员页面。
 */
function _is_install_area() {
	$curr_url = $_SERVER ['REQUEST_URI'];
	return strpos ( $curr_url, '/install/' ) > - 1;
}