<?php 
class CommonDatabase extends CI_Model {

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
	function get_data($table,$limit = false,$start_id = false,$field = false,$value ="",$field_2 = false,$field_2_value = "",$field_3 = false,$field_3_value = "",$field_4 = false,$field_4_value = "",$ord = false){		
		if ($field != false) {
			if ($field_4 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' AND $field_3 = '$field_3_value' AND $field_4 = '$field_4_value'";
			}
			elseif ($field_3 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' AND $field_3 = '$field_3_value'";
			}
			elseif ($field_2 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' ";
			}else{				
				$condition = " $field = '$value'";
			}
		}			
		$this->db->select('*');
		$this->db->from($table);
		if ($ord) {
			$order_by = "ORDER by ".$ord." DESC";
		}else{
			$order_by = "";
		}
		if (isset($condition)) {
			$this->db->where($condition.$order_by);
		}else{
			if ($order_by != "") {
				$this->db->where(" 1 ".$order_by);
			}
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
	function get_cond($table,$cond = false){		
		if ($cond) {					
			$condition = $cond;
		}	
		$this->db->select('*');
		$this->db->from($table);		
		if (isset($condition)) {
			$this->db->where($condition);
		}		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	function update($table,$data = [],$field = false,$value ="",$field_2 = false,$field_2_value = "",$field_3 = false,$field_3_value = "",$field_4 = false,$field_4_value = "",$ord = false){		
		if ($field != false) {
			if ($field_4 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' AND $field_3 = '$field_3_value' AND $field_4 = '$field_4_value'";
			}
			elseif ($field_3 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' AND $field_3 = '$field_3_value'";
			}
			elseif ($field_2 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' ";
			}else{				
				$condition = " $field = '$value'";
			}
		}					
		$this->db->where($condition);
		$this->db->update($table, $data);
	}
	function add($table,$data){
		$this->db->insert($table,$data);
	}
	function count($table = false,$field = false,$value ="",$field_2 = false,$field_2_value = "",$field_3 = false,$field_3_value = "",$field_4 = false,$field_4_value = ""){		
		if ($field != false) {
			if ($field_4 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' AND $field_3 = '$field_3_value' AND $field_4 = '$field_4_value'";
			}
			elseif ($field_3 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' AND $field_3 = '$field_3_value'";
			}
			elseif ($field_2 != false) {
				$condition = "$field = '$value' AND $field_2 = '$field_2_value' ";
			}else{				
				$condition = " $field = '$value'";
			}
		}			
		if ($table) {
			$this->db->select('*');
			$this->db->from($table);
			$this->db->where($condition);

			$query = $this->db->get();

			return $query->num_rows();
		}else{
			return false;
		}
	}
}
?>