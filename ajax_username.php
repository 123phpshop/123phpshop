<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
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
?><?php

require_once ('Connections/localhost.php');
?>
<?php

try {
	
	// 这里对字段进行验证
	$validation->set_rules ( 'username', '用户名', 'required|min_length[6]|max_length[18]|alpha_dash' );
	if (! $validation->run ()) {
		$logger->fatal ( "用户在注册候出现参数错误问题，ip是." . $_SERVER ['REMOTE_ADDR'] . ", 企图注册的用户名是：" . $_POST ['username'] );
		throw new Exception ( "参数错误！" );
	}
	
	$result = "true";
	$colname_get_username = "-1";
	if (isset ( $_POST ['username'] )) {
		$colname_get_username = (get_magic_quotes_gpc ()) ? $_POST ['username'] : addslashes ( $_POST ['username'] );
	}
	mysql_select_db ( $database_localhost, $localhost );
	$query_get_username = sprintf ( "SELECT * FROM `user` WHERE username = '%s'", $colname_get_username );
	$get_username = mysql_query ( $query_get_username, $localhost );
	if(!$get_username){$logger->fatal("数据库操作失败:".$query_get_username);}
	$row_get_username = mysql_fetch_assoc ( $get_username );
	$totalRows_get_username = mysql_num_rows ( $get_username );
	if ($totalRows_get_username > 0) {
		$result = "false";
	}
	die ( $result );
} catch ( Exception $ex ) {
	die ( "false" );
}
