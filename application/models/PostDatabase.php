<?php 
class PostDatabase extends CI_Model {

	public function __construct(){
		parent::__construct();		
	}
	public function table(){
		return 'tp_post';
	}
	public function getPost($identifier){
		$this->db->select('*');
		$this->db->from($this->table());
		$this->db->where("identifier='$identifier'");
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	public function countLikes($identifier){
		$this->db->select('*');
		$this->db->from('tp_likes');
		$this->db->where("post_identifier='$identifier'");

		$query = $this->db->get();

		return $query->num_rows();
	}
	function delete($table,$field,$key,$field2 = false,$key2 = false,$field3 = false,$key3 = false)
	{		
		if ($field2 == false || $key2 == false) {			
			$condition = "$field='$key'";
		}
		elseif($field3 == false || $key3 == false){			
			$condition = "$field='$key' AND $field2='$key2'";
		}
		else{
			$condition = "$field='$key' AND $field2='$key2' AND $field3='$key3'";
		}
		$this->db->where($condition);
		$this->db->delete($table);
		return true;
	}
	public function countComments($identifier){
		$this->db->select('*');
		$this->db->from('tp_comments');
		$this->db->where("post_identifier='$identifier'");

		$query = $this->db->get();

		return $query->num_rows();
	}
	public function getPostsUser($user){
		$this->db->select('*');
		$this->db->from($this->table());		
		$this->db->where("usr_id='$user' AND state='1'");			
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}	
	}
	public function getAll($table,$order = 'id'){
		$this->db->from($table);
		$this->db->select('*');		
		$this->db->order_by($order,"DESC");		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	function get_data($table,$limit = false,$start_id = false,$parent_field = false,$parent_value ="",$parent_field_2 = false,$parent_field_2_value = "",$order_by = false,$parent_field_3 = false,$parent_field_3_value = "",$ord = false){		
		if ($parent_field != false) {
			if ($parent_field_3 != false) {
				$condition = "$parent_field = '$parent_value' AND $parent_field_2 = '$parent_field_2_value' AND $parent_field_3 = '$parent_field_3_value'";
			}
			elseif ($parent_field_2 != false) {
				$condition = "$parent_field = '$parent_value' AND $parent_field_2 = '$parent_field_2_value' ";
			}else{				
				$condition = " $parent_field = '$parent_value'";
			}
		}			
		$this->db->select('*');
		$this->db->from($table);
		if ($order_by) {
			$order_by = $order_by;
		}else{
			if ($ord) {
				$order_by = "ORDER by ".$ord." DESC";
			}else{
				$order_by = "";
			}
		}
		if (isset($condition)) {
			$this->db->where($condition.$order_by);
		}else{
			$this->db->where(" 1 ".$order_by);
		}
		if ($limit != false) {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	function update($table,$data,$field,$key,$field2 = false,$key2 = false,$field3 = false,$key3 = false)
	{		
		if ($field2 == false || $key2 == false) {			
			$condition = "$field='$key'";
		}
		elseif($field3 == false || $key3 == false){			
			$condition = "$field='$key' AND $field2='$key2'";
		}
		else{
			$condition = "$field='$key' AND $field2='$key2' AND $field3='$key3'";
		}
		$this->db->where($condition);
		$this->db->update($table, $data);
	}
	function add($table,$data){
		$this->db->insert($table,$data);
	}
}