<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sitemap extends CI_Controller {
	public function __construct() {
		parent::__construct();
		  $this->load->library('session');
      $this->load->helper('form');
      $this->load->helper('url_helper');
      $this->load->helper('date');
      $this->load->library('javascript');
      $this->load->model("PostDatabase");
      $this->load->model("users_database");
      $this->load->model("posts_database");
      $this->load->library('user_agent');      
      $this->load->library('calendar');
      $this->load->library('post');
	}
	public function index($opt = false){
   		$data['values'] = [];   			
        $this->load->view('sitemap',$data); 
   }
   public function people(){
   		$data['values'] = [];   			
        $this->load->view('sitemap',$data);
   }
   public function pages(){ 
   		$back = ['Back',base_url("sitemap")];  		
		$data['values'] = [
			$back,
			["Posts",base_url("sitemap/posts")],
			["Users",base_url("sitemap/users")]   					
		];		 		
		$this->load->view("sitemap",$data);
   }
   public function posts(){
   		$data['values'] = []; 
   		$back = ['Back',base_url("sitemap/pages")];
 		  array_push($data['values'], $back);
      $posts = $this->PostDatabase->get_data("tp_post",false,false,'state',1); 
      $p = [];
      for ($i= 0; $i < sizeof($posts); $i++) { 
          $x =  $posts[sizeof($posts) - $i - 1];   
          array_push($p, $x);         
      }  
      $posts = $p;
      foreach ($posts as $post) {
   			$url = base_url("view/".$post['identifier']);
   			$name = $post['title'];
   			$v = [$name,$url];
   			array_push($data['values'], $v);
   		} 			 				
		$this->load->view("sitemap",$data);
   }
   public function users(){
   		$data['values'] = []; 
   		$back = ['Back',base_url("sitemap/pages")];
 		  array_push($data['values'], $back);
      $users = $this->PostDatabase->get_data("tp_users",false,false,'state',0);
      $u = [];
      for ($i= 0; $i < sizeof($users); $i++) { 
          $x =  $users[sizeof($users) - $i - 1];   
          array_push($u, $x);         
      }
      $users = $u;
      foreach ($users as $user) {
   			$url = base_url("profiles/".$user['tp_mail']);
   			$name = $user['f_name']." ".$user['l_name'];
   			$v = [$name,$url];
   			array_push($data['values'], $v);
   		}  				
		$this->load->view("sitemap",$data);
   }
}