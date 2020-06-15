<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Support extends CI_Controller {
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
    	if (common::isSupport()) {
			$data['title'] = "TalkPoint Support";
			$data['user'] = $_SESSION['user']->username;
			
			$data['page'] = "support";		
			$data['data'] = json_decode(json_encode($data));	
			$this->load->view("templates/base_header",$data);
			$this->load->view("support",$data);
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
  public function supportList(){
  	if (common::isSupport()) {  		
  		$support = $this->commonDatabase->get_cond("tp_support","m_state='0' AND m_to='70' group by m_from order by m_date DESC");
			if ($support) {
				$r['status'] = true;
				$l = "";
				 foreach ($support as $support) {
				 	  $l .= common::returnSupport($support);
				 }
				 $r['m'] = $l;
			}else{
				$r['status'] = false;
	    	$r['m'] = '<div class="flow-text center">No queries</div>';
			}
  	}else{
	    $r['status'] = false;
	    $r['m'] = "Invalid notification";
	  }
	  common::emitData($r);
  }
  public function uSupport(){
  	if (common::isSupport() && isset($_POST['user'])) {
  		$id = $_POST['user'];
  		$support = $this->commonDatabase->get_cond("tp_support","(m_from='$id' AND m_to='70') or (m_from='70' AND m_to='$id')");
  		if ($support) {
  			$data = ['m_state' => 1];
  			$this->commonDatabase->update("tp_support",$data,'m_from',$id);
  			 $l = '';
  			 foreach ($support as $s) {
  			 	  $l .= common::renderSupport_($s);
  			 }
  			 $r['m'] = $l;
  			 $r['status'] = true;
  		}else{
  			$r['m'] = '<div class="flow-text center">No messages yet</div>';
  		}
  	}else{
	    $r['status'] = false;
	    $r['m'] = "Invalid notification";
	  }
	  common::emitData($r);
  } 
  public function adminSupport(){
  	if (common::isSupport() && isset($_POST['user']) && isset($_POST['text'])) {
  		$id = $_POST['user'];
  		$data = [
  			'm_from' => 70,
  			'm_to' => $id,
  			'm_text' => $_POST['text'],
  			'm_date' => time(),
  			'm_state' => 0
  		];
  		$this->commonDatabase->add("tp_support",$data);
  		$support = $this->commonDatabase->get_cond("tp_support","(m_from='$id' AND m_to='70') or (m_from='70' AND m_to='$id')");
  		if ($support) {
  			 $l = '';
  			 foreach ($support as $s) {
  			 	  $l .= common::renderSupport_($s);
  			 }
  			 $r['m'] = $l;
  			 $r['status'] = true;
  		}else{
  			$r['m'] = '<div class="flow-text center">No messages yet</div>';
  		}
  	}else{
	    $r['status'] = false;
	    $r['m'] = "Invalid notification";
	  }
	  common::emitData($r);
  }
}