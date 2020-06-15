<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Legal extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->helper('date');
		$this->load->library('user_agent');
		$this->load->helper('form');		
	}
	public function index(){
   			redirect(base_url()."legal/terms");            
   }
   public function privacy(){
   		$data['page']="Privacy policy";
   		$this->load->view('templates/terms',$data);
        $this->load->view('privacy');  
   }
   public function terms(){
   		$data['page']="Terms of service";
   		$this->load->view('templates/terms',$data);
      $this->load->view('terms');  
   }
}