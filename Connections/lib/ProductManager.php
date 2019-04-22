<?php
class ProductManager {
	
	/**
	 * 通过商品的id获取这个商品的图片
	 *
	 * @param unknown $product_id        	
	 */
	public function _get_product_image_by_id($product_id) {
		$result = "";
		global $db_conn;
		global $db_database_localhost;
		
		$query_get_product_image = "SELECT * FROM product_images WHERE product_id = $product_id and is_delete=0 limit 1";
		$get_product_image = mysqli_query($db_conn,$query_get_product_image ) or die ( mysqli_error ($localhost).$query_get_product_image);
		$row_get_product_image = mysqli_fetch_assoc ( $get_product_image );
		$totalRows_get_product_image = mysqli_num_rows ( $get_product_image );
		if ($totalRows_get_product_image > 0) {
			return $row_get_product_image ['image_files'];
		}
		return $result;
	}
}