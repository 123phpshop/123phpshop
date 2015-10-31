<?php
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