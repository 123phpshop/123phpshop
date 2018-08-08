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
?><?php
 
// require_once('../Connections/localhost.php'); ?>
<?php
// 如果session没有启动的话，那么启动session

// 初始化参数
$can_access_the_page = false;
$exclud_pages = array (
		"/admin/index.php" 
);
$current_page = str_replace ( "?", "", str_replace ( $_SERVER ['QUERY_STRING'], "", $_SERVER ['REQUEST_URI'] ) );

// 决定当前用户的角色id，如果session中没有角色id的话，说明是游客
// if(!isset($_SESSION['role_id'])){
// $_SESSION['role_id']=VISITOR_ROLE_ID;
// }

// ----------------------获取这个用户的权限--------------------------------------------------//

// 检查session中是否有相应的权限列表，如果没有的话，那么需要获取这个角色的权限列表
if (! isset ( $_SESSION ['privileges'] )) {
	
	// 根据role_id获取权限id
	$colname_get_privilege_id = $_SESSION ['role_id'];
	
	mysql_select_db ( $database_localhost, $localhost );
	$query_get_privilege_id = sprintf ( "SELECT * FROM role_privilege WHERE role_id = %s", $colname_get_privilege_id );
	$get_privilege_id = mysql_query ( $query_get_privilege_id, $localhost );
	if (! $Result1) {
		$logger->fatal ( "数据库操作失败:" . $updateSQL );
	}
	$row_get_privilege_id = mysql_fetch_assoc ( $get_privilege_id );
	$totalRows_get_privilege_id = mysql_num_rows ( $get_privilege_id );
	
	if ($totalRows_get_privilege_id > 0) {
		
		while ( $row = $row_get_privilege_id ) {
			// 根据权限id获取权限
			mysql_select_db ( $database_localhost, $localhost );
			$query_get_privilege = sprintf ( "SELECT * FROM privilege WHERE id = %s", $colname_get_privilege );
			$get_privilege = mysql_query ( $query_get_privilege, $localhost );
			if (! $Result1) {
				$logger->fatal ( "数据库操作失败:" . $updateSQL );
			}
			$row_get_privilege = mysql_fetch_object ( $get_privilege );
			$totalRows_get_privilege = mysql_num_rows ( $get_privilege );
			if ($totalRows_get_privilege > 0) {
				while ( $privilege_item = $row_get_privilege ) {
					$_SESSION ['privileges'] [] = $privilege_item;
				}
			}
		}
	} else {
		
		$_SESSION ['privileges'] = array ();
	}
}
?>
<?php

/*
 * //-----------------------------检查用户是否有访问当前页面的权限-----------------------------//
 *
 * if(count($_SESSION['privileges'])>0){
 * $controller_action_array=array();
 *
 * // 注意检查权限列表，检查是否可以访问这个页面，如果可以的话，那么检查有关于这个页面的权限中是否有其他条件，
 * foreach($_SESSION['privileges'] as $privileges_items){
 *
 * // 检查是否有全部权限，如果有的话，那么可以访问
 * if($privileges_items->controller_action=="*"){
 * $can_access_the_page==true;
 * break;
 * }
 *
 * // 首先检查是否含有管理页面，如果有的话，那么可以访问
 * if(is_admin_page($privileges_items->controller_action,$current_page)){
 * $can_access_the_page==true;
 * break;
 * }
 *
 * // ? 如果没有管理页面的话，那么获取含有这个页面的权限列表,如果有的话，那么加载相应的约束条件文件
 * if($privileges_items->controller_action==$current_page){
 *
 * // 如果这个权限么有有biz_rule的话
 * $priviles_obj=new Privileges();
 * if(trim($priviles_obj->get_by_id($privileges_items->id)==""){
 * $can_access_the_page==true;
 * break;
 * }else{
 *
 * // 如果有biz_rule，但是满足条件的话
 * require_once $_SERVER['DOCUMENT_ROOT']."admin/privileges/items/$privileges_items->id.php" ;
 * if($can_access_the_page==true){
 * break;
 * }
 * }
 *
 * }
 * }
 *
 * //------------------------------如果用户没有权限访问这个页面的话，那么进行跳转---------------------//
 * // 检查如果经过一系列的检查，can_access_the_page还是为false的话，那么说明这个用户确实没有权限访问这个页面，那么则需要跳转到非法访问页面
 * if($can_access_the_page==false){
 * echo "YOU ARE AUTHORIZED TO ACCESS THE PAGE!";
 * }
 *
 */
?>