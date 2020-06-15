<?php 
class Posts_database extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}
	public function get_post($table,$slug = FALSE)
	{
		$this->db->cache_on();
		$condition = "post_identifier =" . "'" . $slug . "'";
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}	
	public function comments($slug)
	{
		$condition = "post_id =" . "'" . $slug . "'";
		$this->db->select('*');
		$this->db->from('tp_comments');
		$this->db->where($condition);
		$this->db->cache_on();
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	public function submit($table,$data){
		$this->db->insert($table, $data);
	}
	public function submitPosts($data){
		$this->db->insert('tp_posts', $data);
	}
	public function submitComment($data)
	{
		$this->db->insert('tp_comments',$data);
	}
	public function add($table,$data){
		$this->db->insert($table,$data);
	}
	public function check_l($ip,$item){
		$this->db->cache_off();
		$this->db->select('*');
		$this->db->from('logs');
		if (isset($_SESSION['email'])) {
			$this->db->where("user='$ip' AND item='$item'");
		}
		else{
			$this->db->where("ip='$ip' AND item='$item'");
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function c_ip($ip){
		$this->db->select('*');
		$this->db->from('logs');
		$this->db->where("ip='$ip'");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function insert_state($identifier)
	{
		$state="true";
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpass = '';
		$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
		if(! $conn )
		{
			die('Could not connect: ' . mysql_error());
		}
		mysqli_select_db( $conn,"talk_point_db" );
		$query=("UPDATE tp_views SET state = $state WHERE post_identifier = '$identifier'");
		$update=mysqli_query($conn,$query) or die(mysqli_error($conn));
	}
	public function insert_view($data_views){
		$this->db->insert('tp_views',$data_views);
	}
	public function search_post($search){
		$page="tp_".$_SESSION['page'];
		$query = $this->db->query("SELECT * FROM $page where tittle like '%$search%' or p_content like '%$search%' ORDER BY post_id DESC LIMIT 30");
		return $query->result_array();
	}
	public function getComments($id){	
		$query = $this->db->query("SELECT * FROM tp_comments where comment_id = '$id' ORDER BY comment_id LIMIT 8");
		return $query->result_array();
	}
	public function getPost($id){
		$this->db->cache_on();
		$condition = "post_identifier =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('tp_posts');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
		return $query->result();
		} else {
		return false;
		}
	}
	public function getPost_($table,$id){
		$this->db->cache_on();
		$condition = "post_identifier =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
		return $query->result();
		} else {
		return false;
		}
	}
	public function delete($id,$table)
	{
		$this->db->where('post_identifier', $id);
        $this->db->delete('tp_posts');
        $this->db->where('post_identifier', $id);
        $this->db->delete($table);
        $this->db->where('post_identifier', $id);
        $this->db->delete("tp_comments");
        $this->db->where('post_identifier', $id);
        $this->db->delete("tp_likes");
	}
	public function checkRefresh()
	{
		if (isset($_SESSION['refreshed'])) {
			$time=$_SESSION['refreshed'];
			$condition = "p_date >" . "'" . $time . "'";
			$this->db->select('*');
			$this->db->from('tp_'.strtolower($_SESSION['page']));
			$this->db->where($condition);
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}
	}
	public function reply($data)
	{
		$this->db->insert('tp_comment_replies',$data);
	}
	public function check_likes($post_identifier) {
	$userid=$_SESSION['userid'];
	$this->db->select('*');
	$this->db->from('tp_likes');
	$this->db->where("usr_id='$userid' AND post_identifier='$post_identifier'");
	$this->db->limit(1);
	$query = $this->db->get();

	if ($query->num_rows() == 1) {
	return true;
	} else {
	return false;
	}
	}
	public function like($data)
	{
		$this->db->insert('tp_likes',$data);
	}

	public function delete_like($id,$usr_id)
	{
		$this->db->where("usr_id='$usr_id' AND post_identifier='$id'");
        $this->db->delete('tp_likes');        
	}
	public function se($data,$identifier,$table)
	{
		$this->db->where('post_identifier', $identifier);
		$this->db->update('tp_posts', $data);
		$this->db->where('post_identifier', $identifier);
		$this->db->update($table, $data);
	}
	public function see($data,$id)
	{
		$this->db->where('n_id', $id);
		$this->db->update('tp_notifications', $data);		
	}
	public function notify($data)
	{
		$this->db->insert('tp_notifications',$data);
	}

	public function getComment($id){
		$this->db->cache_on();
		$condition = "comment_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('tp_comments');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
		return $query->result();
		} else {
		return false;
		}
	}	
	public function check_post($table,$title) {
	$this->db->cache_on();
	$this->db->select('*');
	$this->db->from($table);
	$this->db->where("post_identifier='$title'");
	$this->db->limit(1);
	$query = $this->db->get();
	if ($query->num_rows() == 1) {
	return true;
	} else {
	return false;
	}
	}

	public function check_p($post,$table){
		$this->db->cache_on();
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where("identifier='$post'");
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
		return true;
		} else {
		return false;
		}
	}
    
    public function get_p($post,$table){ 
    	$this->db->cache_on();   	
		$condition=("identifier='$post'");
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
    }
	public function get_user($post_identifier)
	{
		$this->db->cache_on();
		$user=$_SESSION['userid'];
		$condition=("post_identifier='$post_identifier' AND usr_id!='$user'");
		$this->db->select('*');
		$this->db->from('tp_comments');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	public function search($search){
		$query = $this->db->query("SELECT * FROM tp_posts where tittle like '%$search%' or u_name like '%$search%'  or post_identifier like '%$search%' AND usr_id !='70' ORDER by post_id DESC");
		return $query->result_array();
	}
	public function searchPost($search){
		$query = $this->db->query("SELECT * FROM tp_post where title like '%$search%' or identifier like '%$search%' AND usr_id !='70' ORDER by id DESC");
		return $query->result_array();
	}	
}
?>