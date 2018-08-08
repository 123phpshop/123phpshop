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
class Privilege{
	public $id;
	public $biz_rule;
}

class Privileges{

	private $privileges_items_folder;
	
	public function __construct(){
		$this->privileges_items_folder=$_SERVER['DOCUMENT_ROOT']."admin/privileges/items/";
	}
	
	public function add($privilege){
		//	检查是否有文件存在，如果有的话，那么进行删除
		if($this->check_file_exists($privilege->id) ){
			if(!remove_file($privilege->id)){
				return false;
			}
		}
		
		//  如果没有的话，那么进行添加
		return $this->do_add_file($privilege);
	}
	
	public function remove($privilege){
		// 检查文件是否存在，如果不存在，那么直接返回ok
		if(!$this->check_file_exists($privilege->id) ){
			return true;
		}
		return remove_file($privilege->id);
	}
	
	public function update($privilege){
		//	检查是否有文件存在，如果有的话，那么进行删除
		if($this->check_file_exists($privilege->id) ){
			if(!$this->remove_file($privilege->id)){
				return false;
			}
		} 
		//  如果没有的话，那么进行添加
		return $this->do_add_file($privilege);
	}
	
	public function get_by_id($id){
		$file_name = $this->privileges_items_folder."$id.php"; 
		return $content = file_get_contents($file_name);
	}
	
	private function check_file_exists($id){
		$file_name = $this->privileges_items_folder."$id.php"; 
		return file_exists($file_name);
	}
	
	private function remove_file($id){
		$file_path = $this->privileges_items_folder."$id.php"; 
		return unlink($file_path);
	}
	private function do_add_file($privilege){
		$file_path = $this->privileges_items_folder."$privilege->id.php"; 
		$fopen=fopen($file_path,'w');
		fwrite($fopen,$privilege->biz_rule);
		return fclose($fopen);
	}
}