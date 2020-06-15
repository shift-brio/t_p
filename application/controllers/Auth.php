<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('user_agent');
		$this->load->helper('url_helper');    
    $this->load->library("common");
    $this->load->model("commonDatabase");
	}
  public function index(){
    common::emitData(['~' => 'TalkPoint Auth ;-)']);
  }
	public function save_details(){
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['birth']) && isset($_POST['country']) && isset($_POST['password']) && !isset($_SESSION['user'])) {        
      $r = [];

      $password   = $_POST['password'];
      $pass       = sha1($_POST['password']);
      $first_name = $_POST['first_name'];
      $last_name  = $_POST['last_name'];
      $username   = $_POST['username'];
      $email      = $_POST['email'];
      $country    = $_POST['country'];
      $birth      = $_POST['birth'];

      if (strlen($first_name) > 0) {
        if (strlen($last_name) > 0) {
          if (strlen($username) > 0 && common::check_name($username)) {
            $u_check =$this->commonDatabase->get_data("tp_users",1,false,'u_name',$username);
            if (!$u_check) {
              if (in_array($country, $this->config->item("countries"))) {
                $mail_check = $this->commonDatabase->get_data("tp_users",1,false,'u_email',$email);
                if (!$mail_check) {
                  $tp_mail = preg_replace('/[\s_!&|)}><($@;?{]/', '', $first_name.".".$last_name.".".mb_substr(md5($username.$email),0,5));
                  $tp_mail = strtolower(preg_replace("/[\s_!&|)}>'<($@;?{]/", '', $tp_mail));
                  $data = [
                    'usr_id' => "",
                    'f_name' => $first_name,
                    'l_name' => $last_name,
                    'u_name' => strtolower($username),
                    'u_email'=> $email,
                    'u_pass' => $pass,
                    'u_photo_name' =>'default_prof_white.png',
                    'tp_mail' => $tp_mail,
                    'country' => $country,
                    'year'   => $birth,
                    'joined' => date('d-m-Y')
                  ];
                  $this->commonDatabase->add("tp_users",$data);
                  $user = $this->commonDatabase->get_data("tp_users",1,false,'u_email',$email);
                  $user = $user[0];
                  if ($user) {
                    $data = [
                      'circ_id' => "",
                      'usr_id_adding' => $user['usr_id'],
                      'u_id_added' => $user['usr_id']
                    ];
                    $this->commonDatabase->add("tp_circles",$data);
                    common::update_user_session($username);
                    $_SESSION['new_user'] = true; 
                    $_SESSION['page'] = 'feeds';
                    common::uCountries();
                     
                    $r['status'] = true;
                    $r['m'] = "Saved";              
                  }else{
                    $r['status'] = false;
                    $r['m'] = "An unknown error occurred, please try again.";
                  }                  
                }else{
                  $r['status'] = false;
                  $r['m'] = "The email you submitted has already been registered.";
                }
              }else{
                $r['status'] = false;
                $r['m'] = "Select a valid country.";
              }
            }else{
              $r['status'] = false;
              $r['m'] = "Sorry, the username has already been taken.";
            }
          }else{
            $r['status'] = false;
            if (strlen($username) > 0) {
              $r['m'] = "The username you submitted contains unwanted characters.";
            }else{
              $r['m'] = "Enter a valid username";
            }
          }
        }else{
          $r['status'] = false;
          $r['m'] = "Enter a valid Last Name";
        }
      }else{
        $r['status'] = false;
        $r['m'] = "Enter a valid First Name";
      }
    }else{
      $r['status'] = false;
      $r['m'] = "Invalid access";
    }
    common::emitData($r);
  }
  public function logger(){
    if (!isset($_SESSION['user'])) {
      if (isset($_POST['email']) && isset($_POST['password'])) {
         $email = $_POST['email'];
         $pass  = sha1($_POST['password']);
         $login = $this->commonDatabase->get_data("tp_users",1,false,"u_email",$email,"u_pass",$pass);
         if ($login) {
           common::update_user_session($login[0]['u_name']);
           $r['status'] = true;
           $r['m'] = "logged in";
         }else{
          $r['status'] = false;
          $r['m'] = "Invalid email or password";
         }
      }
    }else{
      $r['status'] = false;
      $r['m'] = "Your are already logged in";
    }
    common::emitData($r);
  }
  public function logout(){
    unset($_SESSION['user']);
    redirect(base_url());
  }
}