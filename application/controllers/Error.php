<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Error extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('user_agent');
		$this->load->helper('url_helper');    
	}
	public function _404(){
    $data = [
      'title' => "Error",
      'page' => 'error'      
    ];
    header("HTTP/1.0 404 Not Found");
    $this->load->view("templates/base_header",$data);
    $this->load->view('404');
  }   
}