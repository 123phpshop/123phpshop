<?php
class PromotionManager {
	public function get_all_promotions() {
		// 获取所有可用的促销
		
		$query_promotions = "SELECT * FROM promotion WHERE is_delete = 0 and start_date<=" . date ( 'Ymd' ) . " and end_date>=" . date ( 'Ymd' );
		$promotions = mysqli_query($localhost,$query_promotions);
		if (! $Result1) {
			$logger->fatal ( "数据库操作失败:" . $updateSQL );
		}
		$row_promotions = mysqli_fetch_assoc ( $promotions );
		$totalRows_promotions = mysqli_num_rows ( $promotions );
	}
	public function _get_promotion_fee($product_fee, $order) {
		
		// 初始化结果参数
		$results = array ();
		$results ['fee'] = 0.00;
		$results ['presents'] = array ();
		$results ['promotion_ids'] = array ();
		
		// 从数据库中获取所有当前可用的促销计划
		global $db_conn;
		global $db_database_localhost;
		
		$sql = "SELECT * FROM promotion WHERE is_delete = 0 and start_date<=" . date ( 'Ymd' ) . " and end_date>=" . date ( 'Ymd' );
		$promotions = mysqli_query($db_conn,$sql);
		if (mysqli_num_rows ( $promotions ) == 0) {
			return $results;
		}
		
		// 循环这些促销
		while ( $promotion_plan = mysqli_fetch_assoc ( $promotions ) ) {
			
			// 这里需要检查用户是否已经享受到了这个促销,如果已经享受了的话，那么就不用在进行了
			$promotion_ids_array = explode ( ",", $order ['promotion_id'] );
			
			// 检查用户已经享受了这个促销
			$_promotion_in_order = in_array ( $promotion_plan ['id'], $promotion_ids_array );
			$this->logger->debug ( "检查用户是否已经享受了这个促销：" );
			
			// 如果用户没有享受过这个促销，而且具有享受这个促销的条件，那么直接添加
			if ($promotion_plan ['promotion_limit'] == "1") {
				
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $product_fee );
				
				// 如果是全场的话，商品的总费用没有达到享受促销所需要的费用，但是订单有这个促销的话，那么将这个促销删除
				if (( float ) $product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) {
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					continue;
				}
				
				// 如果用户已经享受过这个促销的话
				if ($_promotion_in_order) {
					// $this->logger->debug ( "用户是否已经享受了这个促销：跳过。。。" );
					continue;
				}
				
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
			
			// 如果是分类的话
			if ($promotion_plan ['promotion_limit'] == "2") {
				
				// 检查获取这张订单中所有参与分类的商品的总金额
				$catalog_product_fee = $this->_123phpshop_get_catalog_product_fee ( $order, $promotion_plan );
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $catalog_product_fee );
				
				// 如果指定分类的商品的总额《参与促销活动的最低的金额的话，那么说明用户无权享受这个促销，那么需要退出
				if (( float ) $catalog_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) { // 如果用户已经享受过这个促销的话，那么需要收回这个促销
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					// 进行下一个
					continue;
				}
				
				// 如果用户已经享受过这个促销的话，那么直接循环下一个
				if ($_promotion_in_order) {
					continue;
				}
				
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
			
			// 如果是品牌的话
			if ($promotion_plan ['promotion_limit'] == "3") {
				
				// 获取订单中
				$brand_product_fee = $this->_123phpshop_get_brand_product_fee ( $order, $promotion_plan );
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $brand_product_fee );
				// 如果这个品牌的商品的总额《参与促销活动的最低的金额的话
				if (( float ) $brand_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) {
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					continue;
				}
				
				// 如果用户已经享受过这个促销的话，那么直接循环下一个
				if ($_promotion_in_order) {
					continue;
				}
				
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
			
			// 如果是指定产品的话
			if ($promotion_plan ['promotion_limit'] == "4") {
				
				$product_product_fee = $this->_123phpshop_get_product_product_fee ( $order, $promotion_plan );
				
				// 然后获取这个促销的金额和赠品
				$promotion_fee_presents = $this->_get_promotion_fee_presents ( $promotion_plan, $product_product_fee );
				// 如果指定商品的总额《参与促销活动的最低的金额的话
				if (( float ) $product_product_fee < ( float ) $promotion_plan ['amount_lower_limit']) {
					if ($_promotion_in_order) {
						// 那么将这个促销从订单中删除
						$this->_unset_promotion ( $promotion_plan, $promotion_fee_presents );
					}
					continue;
				}
				
				// 如果用户已经享受过这个促销的话，那么直接循环下一个
				if ($_promotion_in_order) {
					continue;
				}
				
				$results ['fee'] += ( float ) $promotion_fee_presents ['fee'];
				// 如果这个促销是满增的话，那么将赠品添加到结果中
				if (count ( $promotion_fee_presents ['presents'] ) > 0) {
					$results ['presents'] = array_merge ( $promotion_fee_presents ['presents'], $results ['presents'] );
				}
				$results ['promotion_ids'] [] = $promotion_plan ['id'];
				continue;
			}
		}
		return $results;
	}
}