<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('user_agent');
		$this->load->library("common");
		$this->load->helper('url_helper');
		$this->load->model("commonDatabase");    
	}
	public function index(){
		echo json_encode(json_decode(json_encode(['~'=>"User page"])));
	}
	public function user($username = false){
		if ($username) {			
			$user = $this->commonDatabase->get_data("tp_users",1,false,'u_name',$username);
			if (!$user) {
				$user = $this->commonDatabase->get_data("tp_users",1,false,'tp_mail',$username);
			}
			if ($user) {
				common::viewUser($user[0]['usr_id']);
				$u = json_decode(json_encode($user[0]));
				$data['title']  = "@".strtolower($u->u_name)." - TalkPoint";
				$data['user']   = strtolower($u->u_name);				
				$data['name']   = ucfirst($u->f_name." ".$u->l_name);
				$data['user_page']  = strtolower($u->u_name);
				$data['data']   = $user[0];
				$data['page']   = "user";
				$data['loaded'] = "user_page";
				$data = $data;	

				$data['x_data'] = $data;	
				$this->load->view("templates/base_header",$data);
				$this->load->view("user_base",$data);
			}else{
				$data = [
		      'title' => "Error",
		      'page' => 'error'      
		    ];
		    header("HTTP/1.0 404 Not Found");
		    $this->load->view("templates/base_header",$data);
		    $this->load->view('404');
			}
		}else{
			$data = [
	      'title' => "Error",
	      'page' => 'error'      
	    ];
	    header("HTTP/1.0 404 Not Found");
	    $this->load->view("templates/base_header",$data);
	    $this->load->view('404');
		}
	}
	public function saveDetails(){
		if (isset($_SESSION['user']) && isset($_POST['bio']) && isset($_POST['password']) && isset($_POST['f_name']) && isset($_POST['l_name']) && isset($_POST['email']) && isset($_POST['phone'])) {
			$email = $_POST['email'];
			$f_name = $_POST['f_name'];
			$l_name = $_POST['l_name'];
			$password = sha1($_POST['password']);
			$ch_email=$this->commonDatabase->get_data("tp_users",1,false,'u_email',$email);
			if (!$ch_email || ($ch_email && $ch_email[0]['u_name'] == $_SESSION['user']->username) || $ch_email) {
				$f_clean = preg_replace("/\s+/", "", $f_name);
				$l_clean = preg_replace("/\s+/", "", $l_name);				
				if (strlen($f_clean) > 0 && strlen($l_clean)) {
					$login = $this->commonDatabase->get_data("tp_users",1,false,'u_name',$_SESSION['user']->username,'u_pass',$password);
					$ch_phone =$this->commonDatabase->get_data("tp_users",1,false,'u_phone',$_POST['phone']);
					if (!$ch_phone || ($ch_phone[0]['u_name'] && $ch_phone[0]['u_name'] == $_SESSION['user']->username) || $_POST['phone'] == "") {
						if ($login) {
							$data = [
								'f_name' => $f_name,
								'l_name' => $l_name,
								'u_email' => $email,
								'u_phone' => $_POST['phone'],
								'bio' => $_POST['bio']
							];
							$this->commonDatabase->update("tp_users",$data,'u_name',$_SESSION['user']->username);
							common::update_user_session($_SESSION['user']->username);
							$r['status'] = true;
							$r['m'] = "Updated.";
						}else{
							$r['status'] = false;
							$r['m'] = "Incorrect password.";
						}
					}else{
						$r['status'] = false;
						$r['m'] = "The phone number is already registered";
					}
				}else{
					$r['status'] = false;
					$r['m'] = "Enter a valid first/last name";
				}
			}else{
				$r['status'] = false;
				$r['m'] = "The email is already registered";
			}
			
		}else{
			$r['status'] = false;
			$r['m'] = "Invalid access";
		}
		common::emitData($r);
	}
	public function changePass(){
		if (isset($_SESSION['user']) && isset($_POST['curr']) && isset($_POST['new'])) {
			$curr = sha1($_POST['curr']);			
			if (strlen($_POST['new']) > 4) {
				$new  = sha1($_POST['new']);
				$login = $this->commonDatabase->get_data("tp_users",1,false,'u_name',$_SESSION['user']->username,'u_pass',$curr);
				if ($login) {
					$data = [
						'u_pass' => $new
					];
					$this->commonDatabase->update("tp_users",$data,'u_name',$_SESSION['user']->username);
					common::update_user_session($_SESSION['user']->username);
					$r['status'] = true;
					$r['m'] = "Password changed.";
				}else{
					$r['status'] = false;
					$r['m'] = "Incorrect password.";
				}
			}else{
				$r['status'] = false;
				$r['m'] = "Password too short.";
			}
		}else{
			$r['status'] = false;
			$r['m'] = "Invalid access";
		}
		common::emitData($r);
	}
	public function saveProf(){
		if (isset($_SESSION['user']) && isset($_FILES) && isset($_POST['type'])) {
			$filename = md5(time());
			if (isset($_FILES['file-0'])) {		       
				if ($_POST['type'] == 'prof') {
					$config['upload_path']          = './uploads/profile/';
				}else{
					$config['upload_path']          = './uploads/banner/';
				}
				$config['allowed_types']        = 'gif|jpg|png|jpeg';
				$config['file_name']            = $filename;
				$config['max_size']             = 160000;
				$this->load->library('upload', $config);  

				if (!$this->upload->do_upload('file-0'))
				{
					$r["status"] = false;
					$error = array('error' => $this->upload->display_errors());             
					$r['m'] = $error['error'];          
				} 
				else{
					if ($_POST['type'] == "prof") {
						$data = [
							'u_photo_name' => $filename.$this->upload->data('file_ext')
						];
					}elseif($_POST['type'] == "banner"){
						$data = [
							'banner' => $filename.$this->upload->data('file_ext')
						];
					}else{
						$r['status'] = false;
						$r['m'] = "Invalid type";
					}
					if ($_POST['type'] == "prof" || $_POST['type'] == "banner") {
						$this->commonDatabase->update("tp_users",$data,'u_name',$_SESSION['user']->username);
						$r['status'] = true;
						$r['m']['data'] = $filename.$this->upload->data('file_ext');
						common::update_user_session($_SESSION['user']->username);
					}        	
				}
			}else{
				$r['status'] = false;
				$r['m'] = "Invalid file";
			}
		}else{
			$r['status'] = false;
			$r['m'] = "Invalid access";
		}
		common::emitData($r);
	}
	public function removeProfs(){
		if (isset($_SESSION['user']) && isset($_POST['type'])) {
			$type = $_POST['type'];
			if($type == "prof"){
				$data = [
					'u_photo_name' => 'default_prof_white.png'
				];
			}elseif($type == "banner"){
				$data = [
					'banner' => ''
				];
			}else{
				$r['status'] = false;
				$r['m'] = "Invalid type";
			}	
			if ($type == "prof" || $type == "banner") {
				$this->commonDatabase->update("tp_users",$data,'u_name',$_SESSION['user']->username);
				$r['status'] = true;    		
				common::update_user_session($_SESSION['user']->username);
			}
		}else{
			$r['status'] = false;
			$r['m'] = "Invalid access";
		}
		common::emitData($r);
	}
	public function follow(){
		if (isset($_SESSION['user']) && isset($_POST['target'])) {
			$user = $_SESSION['user']->id;
			if (mb_substr($_POST['target'], 0,1) == "@") {
				$_POST['target'] = mb_substr($_POST['target'], 1,strlen($_POST['target']));
			}
			$ch_user = $this->commonDatabase->get_data("tp_users",1,false,"u_name",$_POST['target']);			
			if ($ch_user && $ch_user[0]['usr_id'] != $user) {
				$target = $ch_user[0]['usr_id'];
				$ch_follow = $this->commonDatabase->get_data("tp_circles",1,false,'usr_id_adding',$user,'u_id_added',$target);
				if ($ch_follow) {
					$this->commonDatabase->delete("tp_circles",'usr_id_adding',$user,'u_id_added',$target);
					$this->commonDatabase->delete("tp_notifications",'owner',$target,'sender',$user,'type','follow');
					$r['m']['status'] = "unfollowed";
				}else{
					$data = [
						'usr_id_adding' => $user,
						'u_id_added' => $target,
						'date' => time()
					];
					$this->commonDatabase->add("tp_circles",$data);					
					common::sendNotif($target, $user, "Started following you", "follow");		
					$r['m']['status'] = "followed";
				}
				$r['m']['username'] = strtolower($_POST['target']);
				$r['status'] = true;
			}else{
				$r['status'] = false;
				$r['m'] = "You cannot unfollow yourself";
			}
		}else{
			if (!isset($_SESSION['user'])) {
				$r['status'] = false;
				$r['m'] = "Log in to follow writer";
			}else{
				$r['status'] = false;
				$r['m'] = "Invalid access";
			}
		}
		common::emitData($r);
	}
	public function userTime(){
		$time = common::userTime();
		if ($time) {
			$r['status'] = true;
			$r['m'] = "active";
		}else{
			$r['status'] = false;
			$r['m'] = "logged out";
		}
		common::emitData($r);
	}
}