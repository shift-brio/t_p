<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Editor extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('user_agent');
		$this->load->helper('url_helper'); 
		$this->load->library("common");    
	}
	public function index(){
    if (common::isEditor()) {
			$data['title'] = "TalkPoint Editor";
			$data['user'] = $_SESSION['user']->username;

			$data['page'] = "editor";		
			$data['data'] = json_decode(json_encode($data));	
			$this->load->view("templates/base_header",$data);
			$this->load->view("editor",$data);
		}	else{
			$data = [
	      'title' => "Error",
	      'page' => 'error'      
	    ];
	    header("HTTP/1.0 404 Not Found");
	    $this->load->view("templates/base_header",$data);
	    $this->load->view('404');
		}	
  } 
  public function saveEdits(){
  	if (common::isEditor() && isset($_POST['post']) && isset($_POST['title']) && isset($_POST['content'])) {
			 $t_test = preg_replace("/\s+/", "", $_POST['title']);
	  	 if (strlen($t_test) > 3) {
	  	 	  $c_test = preg_replace("/\s+/", "", $_POST['content']);
	  	 	  if (strlen($c_test) > 10) {
	  	 	  	$url = preg_replace("/\s+/", "-", $_POST['title']);
				    $url = preg_replace("/[\s:.,!&|)}`>'*+^<($@;?{]/", "", $url);
				    $url = preg_replace('/["]/', "", $url);
				    $url = preg_replace("/[']/", "", $url);
				    $url = $url."~".strtolower(mb_substr(md5(time()), 0,5));

				    $data = [				    	
				    	'title'  => $_POST['title'],
				    	'body'   => $_POST['content'],				    	
				    	'link' => $url,				    	
				    	'added_at' => time()					    	
				    ];
				    $r['m'] = "Saved";
				    if (isset($_POST['publish']) && $_POST['publish'] == 'true') {
				    	$data['state'] = 1;
				    	common::addNotify($_POST['post']);
				    	$r['m'] = "Published.";
				    }
				    $this->commonDatabase->update("tp_post",$data,"identifier",$_POST['post']);
				    $r['status'] = true;				    
	  	 	  }else{
	      	 		$r['status'] = false;
		   				$r['m'] = "Article too short.";	
	      	 }
	  	 }else{
	  	 		$r['status'] = false;
	 				$r['m'] = "Article title too short.";	
	  	 }			
		}else{
			$r['status'] = false;
	    $r['m'] = "Invalid access";
	  }
   common::emitData($r);
  }  
  public function editNotify(){
  	if (common::isEditor() && isset($_POST['item']) && isset($_POST['text'])) {
			 $t_test = preg_replace("/\s+/", "", $_POST['text']);
	  	 if (strlen($t_test) > 3) {
	  	 		$post = $this->commonDatabase->get_data("tp_post",1,false,'identifier',$_POST['item']);
	  	 	  if ($post) {
	  	 	  	common::sendNotif($post[0]['usr_id'], 70, $_POST['text'], "notif",$post[0]['identifier']);
	  	 	  	$r['status'] = true;
	 				  $r['m'] = common::getNotifs($_POST['item']);	
	  	 	  }else{
	  	 	  	$r['status'] = false;
	 				  $r['m'] = "Article not found";	
	  	 	  }
	  	 }else{
	  	 		$r['status'] = false;
	 				$r['m'] = "Notification too short.";	
	  	 }			
		}else{
			$r['status'] = false;
	    $r['m'] = "Invalid access";
	  }
   common::emitData($r);
  }
  public function getNotifs(){
  	if (common::isEditor() && isset($_POST['item'])) {
  		$r['status'] = true;
	   $r['m'] = common::getNotifs($_POST['item']);	;
  	}else{
		$r['status'] = false;
	   $r['m'] = "Invalid access";
	}
  	common::emitData($r);
  } 
}