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

global $catalog_tree;
$catalog_tree = array ();
function get_catalog_tree($parent_catalog = array(), $prefix = "") {
	// 如果是初次运行的话
	global $db_database_localhost;
	global $db_conn;
	global $glogger;
	global $catalog_tree;
	
	if ($parent_catalog == array ()) {
		$pid = 0;
	} else {
		$pid = $parent_catalog ['id'];
	}
	
	mysql_select_db ( $db_database_localhost, $db_conn );
	$query_catalogs = "SELECT * FROM `catalog` where is_delete=0 and pid=" . $pid;
	$glogger->debug ( $query_catalogs );
	$catalogs = mysql_query ( $query_catalogs, $db_conn ) or die ( mysql_error () );
	$totalRows_catalogs = mysql_num_rows ( $catalogs );
	
	$glogger->debug ( "获取分类的数目：" . $totalRows_catalogs );
	
	// 如果找不到记录的话，那么直接返回
	if ($totalRows_catalogs == 0) {
		return;
	}
	
	// 如果可以找到记录的话，那么循环这些记录
	$new_prefix = $prefix . "&nbsp&nbsp&nbsp";
	while ( $catalog = mysql_fetch_assoc ( $catalogs ) ) {
 		$catalog ['prefix'] = $prefix;
		$catalog_tree [] = $catalog;
		get_catalog_tree ( $catalog, $new_prefix );
	}
}
get_catalog_tree ();
?>
<select name="catalog_id" id="catalog_id">
  <?php
		foreach ( $catalog_tree as $catalog_tree_item ) {
			?>
  <option value="<?php echo $catalog_tree_item['id']?>"
		<?php if(isset($_GET['id']) && isset($row_product['catalog_id']) && $catalog_tree_item['id']==$row_product['catalog_id']){ ?>
		selected <?php } ?>><?php echo $catalog_tree_item['prefix'].$catalog_tree_item['name'];?></option>
  <?php
		}
		?>
</select>