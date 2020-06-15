<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
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
		if (isset($_SESSION['user'])) {
			$data['title'] = "TalkPoint";
			$data['user']  = $_SESSION['user']->username;
		}else{
			$data['title'] = "Welcome to TalkPoint";
		}
		if (!isset($_SESSION['column']) || $_SESSION['column'] == "user") {
			if (isset($_SESSION['user'])) {
				$_SESSION['column'] = "feeds";
			}else{
				$_SESSION['column'] = "politics";
			}
		}		
		$data['page'] = "home";		
		$data['data'] = json_decode(json_encode($data));	
		$this->load->view("templates/base_header",$data);
		$this->load->view("base",$data);
	}
	public function column($col = false){
		if (in_array($col, $this->config->item("tables"))) {
			if (isset($_SESSION['user'])) {
				$data['title'] = "TalkPoint";
				$data['user'] = $_SESSION['user']->username;
			}else{
				$data['title'] = "Welcome to TalkPoint";
			}
			if ($col == 'feeds' && !isset($_SESSION['user'])) {
					redirect(base_url("politics"));
			}	
			$_SESSION['column'] = $col;	
			$data['page'] = "home";		
			$data['data'] = json_decode(json_encode($data));	
			$this->load->view("templates/base_header",$data);
			$this->load->view("base",$data);
		}else{
			$data = [
	      'title' => "Page Not Found",
	      'page' => 'error'      
	    ];
	    header("HTTP/1.0 404 Not Found");
	    $this->load->view("templates/base_header",$data);
	    $this->load->view('404');
		}
	}
	public function postArticle(){
		if (isset($_SESSION['user']) && isset($_POST['title']) && isset($_POST['upload_type']) && isset($_POST['content']) && isset($_POST['column']) && in_array(strtolower($_POST['column']), $this->config->item("tables")) && $_POST['column'] != 'videos') {
			$column = $_POST['column'];
			$time = time();
			$error = false;
			$filename = md5($time);
			$uploaded = "";
			if (isset($_FILES['file-0'])) { 
        $filename = md5(time());         
        $config['upload_path']          = './uploads/posts/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['file_name']            = $filename;
        $config['max_size']             = 1600000;
        $this->load->library('upload', $config);  
        
        if (!$this->upload->do_upload('file-0'))
        {
          $r["status"] = false;
          $error = array('error' => $this->upload->display_errors());             
          $r['m'] = $error['error'];          
        } 
        else{
          $uploaded = $filename.$this->upload->data('file_ext');
          $data = [
              'link' => $filename.$this->upload->data('file_ext'),
              'type' => 'image',
              'added_at' => $time
          ];
          $this->commonDatabase->add("tp_attachments",$data);        
        }
      }
      if ($_POST['upload_type'] == "link") {
        $uploaded = $_POST['link'];
        $data = [
            'link' => $_POST['link'],
            'type' => 'yt',
            'meta' => $_POST['thumbnail'],
            'added_at' => $time
        ];
        $this->commonDatabase->add("tp_attachments",$data);
      }
      if (!$error) {
      	 $attached = $this->commonDatabase->get_data("tp_attachments",1,false,'link',$uploaded,'added_at',$time);
      	 if ($attached) {
      	 	  $attached = $attached[0]['id'];
      	 }else{
      	 	  $attached = 0;
      	 }
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
					    	'usr_id' => $_SESSION['user']->id,
					    	'title'  => $_POST['title'],
					    	'body'   => $_POST['content'],
					    	'attached' => $attached,
					    	'identifier' => $filename,
					    	'link' => $url,
					    	'country' => $_SESSION['user']->country,
					    	'post_column' => $column,
					    	'added_at' => $time					    	
					    ];
					    $this->commonDatabase->add("tp_post",$data);
					    $r['status'] = true;
					    $r['m'] = "Published.";
      	 	  }else{
		      	 		$r['status'] = false;
			   			$r['m'] = "Article too short.";	
		      	 }
      	 }else{
      	 		$r['status'] = false;
	   				$r['m'] = "Article title too short.";	
      	 }
      }
		}else{
	    $r['status'] = false;
	    $r['m'] = "Log in first to publish.";
	  }
	  common::emitData($r);
	}
	public function article($url = false){
		if ($url) {			
			$get_id = $this->commonDatabase->get_data("tp_post",1,false,'link',$url,'state','1');
			if (!$get_id) {
				$get_id = $this->commonDatabase->get_data("tp_post",1,false,'identifier',$url,'state','1');
			}
			if ($get_id) {
				$post = common::getArticle($get_id[0]['identifier']);
				if (isset($_SESSION['user'])) {					
					$data['user'] = $_SESSION['user']->username;
				}
				if (isset($_GET['p'])) {
					 common::logPlatform($post['details']['identifier'],$_GET['p']);
				}
				common::_viewArticle($get_id[0]['identifier']);
				$data['article'] = $post;
				$data['title'] = $post['details']['title']." - TalkPoint";					
				$data['page'] = "post";		
				$data['data'] = json_decode(json_encode($data));	
				$this->load->view("templates/base_header",$data);
				$this->load->view("post_page",$data);

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
	public function shareWith(){
		if (isset($_SESSION['user']) && isset($_POST['post']) && isset($_POST['followers']) && sizeof($_POST['followers']) > 0) {
			$article = $this->commonDatabase->get_data("tp_post",1,false,'identifier',$_POST['post'],'state','1');
			if ($article && $article[0]['usr_id'] != $_SESSION['user']->id && $article[0]['type'] != 'share_with') {
				$column = $article[0]['post_column'];
				$time = time();
				$error = false;
				$filename = md5($time);
				$uploaded = "";			
	      if (!$error) {	      	 
	      	 $t_test = preg_replace("/\s+/", "", $_POST['text']);
	      	 if (strlen($t_test) > 3) {
	      	 	    $url = preg_replace("/\s+/", "-", $article[0]['title']."_shared");
						    $url = preg_replace("/[\s:.,!&|)}`>'*+^<($@;?{]/", "", $url);
						    $url = preg_replace('/["]/', "", $url);
						    $url = preg_replace("/[']/", "", $url);
						    $url = $url."~".strtolower(mb_substr(md5(time()), 0,5));

						    $data = [
						    	'usr_id' => $_SESSION['user']->id,
						    	'title'  => "",
						    	'body'   => $_POST['text'],
						    	'attached' => 0,
						    	'identifier' => $filename,
						    	'link' => $url,
						    	'state' => 1,
						    	'type' => 'share_with',
						    	'country' => $_SESSION['user']->country,
						    	'post_column' => $column,
						    	'added_at' => $time					    	
						    ];
						    $this->commonDatabase->add("tp_post",$data);
						    $r['status'] = true;
						    $r['m'] = "Published.";

						    $data = [
						    	'date_added' => $time,
						    	'sharing' => $filename,
						    	'identifier' => $article[0]['identifier'],
						    	'shared_to'  => json_encode($_POST['followers']),
						    	'usr_id' => $_SESSION['user']->id
						    ];
						    $this->commonDatabase->add("tp_share_with",$data);
	      	 }else{
	      	 		$r['status'] = false;
		   				$r['m'] = "Article too short.";	
	      	 }
	      }
			}else{
				$r['status'] = false;
				if (!$article) {
					$r['m'] = "Article not found";
				}else{
					$r['m'] = "Article already shared";
				}
			}
		}else{
	    $r['status'] = false;
	    $r['m'] = "Log in first to share.";
	  }
	  common::emitData($r);
	}
	public function searcher(){
		if (isset($_SESSION['user']) && isset($_POST['key']) && isset($_POST['type'])) {
    $key  = $_POST['key'];
    $type = $_POST['type'];
    $processed = "";
    if ($type == "users") {
    	if (mb_substr($key, 0,1) == "@") {
    		$key = mb_substr($key, 0,strlen($key));
    	}    	
      $query = $this->commonDatabase->get_cond("tp_users","u_name like '%$key%'");
      if ($query) {
      	foreach ($query as $user) {
      		$name = ucfirst($user['f_name']." ".$user['l_name']);
      		$prof = base_url("uploads/profile/".$user['u_photo_name']);
      		$link = base_url("writer/".$user['u_name']);
      		$articles = common::format_number($this->commonDatabase->count("tp_post",'usr_id',$user['usr_id'],'state','1'));
      		$followers = common::format_number($this->commonDatabase->count("tp_circles",'u_id_added',$user['usr_id']));

      		$v = '<div class="g-item search-user" tabindex="1">
								<a href="'.$link.'" data-type="user" ddata-item="'.$user['u_name'].'" data-position="right" data-tooltip="View profile">
									<div class="item-head left">								
											<img src="'.$prof.'" alt="item" class="left item-img">
										<div class="item-desc linke">
											<div class="item-title">
												'.$name.'
											</div>
											<div class="item-decription">	
												<div class="user-handle">@'.$user['u_name'].'</div>
												<div class="item-date">'.$articles.' articles . '.$followers.' followers</div>
											</div>							
										</div>
									</div>	
								</a>													
							</div>';
						$processed .= $v;
      	}
      }else{
      	$r['status'] = false;
        $r['m'] = "No writers match search keyword.";
      }
    }elseif($type == "articles"){
    	$query = $this->commonDatabase->get_cond("tp_post","title like '%$key%' AND state='1'");
    	if ($query) {
    		foreach ($query as $post) {
    			$url = base_url("article/".$post['link']);
    			$identifier = $post['identifier'];
    			$date = date('d-m-Y',$post['added_at']);
    			$author = $this->commonDatabase->get_data("tp_users",1,false,'usr_id',$post['usr_id']);
    			$t = strtolower($post['post_column']);
					if ($t == "politics") {
						$column = "Politics & Leadership";
					}
					elseif ($t=="life") {
						$column= "Life & Style";
					}
					elseif ($t=="money") {
						$column= "Money & Business";
					}elseif ($t == "estate") {
						$column= "Real Estate";        
					}
					else{
						$column = $t;
					}
					if (strlen($post['title']) > 40) {
						$post['title'] = mb_substr($post['title'], 0,40);
					}
					$column = ucfirst($column);
    			$views = common::format_number(common::getViews($post['identifier']));	
    			if ($author) {
    				$v = '
    						<div class="hot-item search-item">
									<a href="'.$url.'" class="in-link" data-type="post" data-item="'.$identifier.'">			
										<div>
											<span class="hot-item-title post-title">'.$post['title'].'</span>
											<div class="hot-item-data">@'.$author[0]['u_name'].' . '.$column.'</div>
											<div class="hot-item-data">11-12-2018 . '.$views.' Views</div>
										</div>
										<div class="right launch-trend">
											<i class="material-icons">launch</i>
										</div>
									</a>
								</div>
    				';
    				$processed .= $v;
    			}
    		}
    	}else{
      	$r['status'] = false;
        $r['m'] = "No articles match search keyword.";
      }
    }else{    	
      $r['status'] = false;
      $r['m'] = "Invalid access";
    }
    if (!isset($r)) {
    	$r['status'] = true;
    	$r['m'] = $processed;
    }
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid access";
  }
  common::emitData($r);
	}
	public function rateUs(){
		if (isset($_SESSION['user']) && isset($_POST['rating'])) {
			$ch_rate = $this->commonDatabase->get_data("tp_ratings",1,false,'usr_id',$_SESSION['user']->id);
			if (!$ch_rate) {
				if ($_POST['rating'] > 0 && $_POST['rating'] <= 5) {
					$data = [
						'usr_id' => $_SESSION['user']->id,
						'rating' => $_POST['rating'],
						'date_added' => time()
					];
					$this->commonDatabase->add("tp_ratings",$data);
					$r['status'] = true;
					$ratings = common::getRating();					
	   			$r['m'] = $ratings['rating'].' ~ '.$ratings['count'].'  people';
				}else{
					$r['status'] = false;
		   		$r['m'] = "Invalid rating";
				}		
			}else{
				$r['status'] = true;
	   		$r['m'] = "Rated";
			}
		}else{
	    $r['status'] = false;
	    $r['m'] = "Log in to rate.";
	  }
	  common::emitData($r);
	}
	public function loadSupport(){
		if (isset($_SESSION['user'])) {
			  $user = $_SESSION['user']->id;
				$ch_avail = $this->commonDatabase->get_cond("tp_support","m_from='$user' or m_to='$user' order by id ASC");				
				if ($ch_avail) {
					$r['m'] = '';
					$r['status'] = true;
					foreach ($ch_avail as $message) {
						$r['m'] .= common::renderSupport($message);
					}
				}else{
					$r['status'] = false;
					$r['m'] = '
						<div class="support-mess support-in">
							<div class="support-mess-head">
								<img src="'.base_url("uploads/system/about_white.png").'" alt="item" class="support-prof">
								<div class="mess-u-name">
									<div class="mess-u author-name">TalkPoint <div class="mess-time right">'.(date('d-m-Y')).'</div></div>
								</div>
							</div>
							<div class="mess-text">
								Welcome to TalkPoint. How may we assist you?
							</div>
						</div>
					';
				}
		}else{
	    $r['status'] = false;
	    $r['m'] = "Logged out.";
	  }
	  common::emitData($r);
	}
	public function askSupport(){
		if (isset($_SESSION['user']) && isset($_POST['message'])) {
			  $user = $_SESSION['user']->id;
				$data = [
					'm_from' => $user,
					'm_to' => '70',
					'm_text' => $_POST['message'],
					'm_date' => time(),								
				];
				$this->commonDatabase->add("tp_support",$data);
				$r['status'] = true;
				$ch_avail = $this->commonDatabase->get_cond("tp_support","m_from='$user' or m_to='$user' order by id DESC");				
				$r['m'] = '';
				$r['status'] = true;
				foreach ($ch_avail as $message) {
					$r['m'] .= common::renderSupport($message);
				}
		}else{
	    $r['status'] = false;
	    $r['m'] = "Logged out.";
	  }
	  common::emitData($r);
	}
	public function getCode(){
		if (isset($_POST['email'])) {
			$ch_user = $this->commonDatabase->get_data("tp_users",1,false,'u_email',$_POST['email']);
			$email = $_POST['email'];
			if ($ch_user) {
				$code = strtoupper(mb_substr( md5(time().time().time()), 0,5));
				$data = [
					'token' => $code
				];
				if (common::sendCode($email,$code)) {
				 $this->commonDatabase->update("tp_users",$data,'u_email',$email);
				 $r['status'] = true;
				 $r['m'] = "Code sent.";
				}else{
					$r['status'] = false;
				  $r['m'] = "An error occurred while sending code to email, kindly contact support@talkpoint.online for help.";
				}
			}else{
		    $r['status'] = false;
		    $r['m'] = "Account not found.";
		  }
		}else{
	    $r['status'] = false;
	    $r['m'] = "Logged out.";
	  }
	  common::emitData($r);
	}
	public function recoverAccount(){
		if (isset($_POST['email']) && isset($_POST['code']) && isset($_POST['password'])) {
			$email = $_POST['email'];
			$code = $_POST['code'];
			$password = sha1($_POST['password']);
			$ch_user = $this->commonDatabase->get_data("tp_users",1,false,'u_email',$email,'token',$code);			
			if ($ch_user) {				
				$data = [
					'token' => '',
					'u_pass' => $password
				];
				$this->commonDatabase->update("tp_users",$data,'u_email',$email);
				$r['status'] = true;
				$r['m'] = "changed.";
			}else{
		    $r['status'] = false;
		    $r['m'] = "Invalid code.";
		  }
		}else{
	    $r['status'] = false;
	    $r['m'] = "Logged out.";
	  }
	  common::emitData($r);
	}
}