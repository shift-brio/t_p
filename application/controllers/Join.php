<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Join extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('user_agent');
		$this->load->helper('url_helper');    
	}
  public function index(){
    if (!isset($_SESSION['user'])) {
      $data['title'] = "Sign Up - TalkPoint";
      $data['page'] = "sign_up";   
      $this->load->view("templates/base_header",$data);
      $this->load->view("join");
    }else{
      redirect(base_url());
    }
  }	
}