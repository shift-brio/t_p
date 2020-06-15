<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller {
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
   $echoed =  "***********************************************<br>";
   $echoed .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp----------------------------------<br>";
   $echoed .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<strong>TalkPoint API v1.0.0<br>";
   $echoed .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----------------------------------<br>";
   $echoed .= "***********************************************<br>";
   $d['echoed'] = $echoed; 
   $d['data'] = json_decode(json_encode(['title' => "TalkPoint API v1.0.0"]));
   $this->load->view("templates/base_header",$d);   	
 }
 public function propagateUsernames(){
  $users = $this->commonDatabase->get_data("tp_users",false,false);
  foreach ($users as $user) {
    
    if ($user['u_photo_name'] == "") {
      $data['u_photo_name'] = "default_prof_white.png";
      $this->commonDatabase->update("tp_users",$data,'usr_id',$user['usr_id']);
    }    
  }
} 
public function getHot($type = "trends"){
  $type = isset($_POST['type']) ? $_POST['type']: $type;
  if ($type == 'trends' || $type == 'popular') {
      $filesize  = filesize ('./'.$type.'.json');
      if ($filesize > 0) {      
        $file = json_decode(file_get_contents('./'.$type.'.json'));
        if ($file->last_update) {
          if ($type == "popular") {
            $time = 60 * 24;
          }else{
            $time = 30;
          }
          if ((time() - $file->last_update)/60 <= $time) {
            $filer = true;            
            $hot = $file->list;            
          }else{
            $hot = common::_getHot($type);
          } 
        }else{
          $hot = common::_getHot($type);
        }
      }else{
       $hot = common::_getHot($type);
      }
      if (!$hot) {
        $hot = common::_getHot($type);
      }
    if ($hot) {
      $r['status'] = true;
      $r['m'] = $hot;
    }else{
      $r['status'] = false;
      $r['m'] = "No records"; 
    }
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid type";
  }
  common::emitData($r);
}
public function propPop(){
  $file = json_decode(file_get_contents('./popular.json'));
  if(!isset($file->last_update) && (($file->last_update/60) <= 30)) {
     $hot = common::_getHot('popular');
  }
  /**/
  $file = json_decode(file_get_contents('./trends.json'));
  if(!isset($file->last_update) && (($file->last_update/60) <= 30)) {
     $hot = common::_getHot('trends');
  }
}
public function getArticle($id = false){
  $identifier = isset($_POST['atricle']) ? $_POST['atricle'] : false;
  $identifier = $identifier ? $identifier : $id;
  if ($identifier) {
   $article = common::getArticle($identifier);

   if ($article) {
    $r['status'] = true;
    $r['m'] = $article;
  }else{
    $r['status'] = true;
    $r['m'] = "Article not found";
  }
}else{
 $r['status'] = false;
}
common::emitData($r);
}
public function getWriter($username = false){
  $username = isset($_POST['username']) ? $username : $username;
  if ($username) {
   $user = common::_getWriter($username);
   if ($user) {
    $r['status'] = true;
    $r['m'] = $user;
  }else{
    $r['status'] = false;
    $r['m'] = "User not found";
  }
}else{
 $r['status'] = false;
 $r['m'] = "Invalid username";
}
common::emitData($r);
}
public function getColumn($col = false,$cur_page = false,$cur_id = false,$user = false){
  $col = isset($_POST['col']) ? $_POST['col'] : $col;  
  $cur_page = isset($_POST['page']) ? $_POST['page']: $cur_page;
  $cur_id = isset($_POST['start_id']) ? $_POST['start_id']: $cur_id;
  $user = isset($_POST['user']) ? $_POST['user']: $user;
  if ($col && in_array($col, $this->config->item("tables"))) {   
    if ($col == 'feeds' && !isset($_SESSION['user'])) {
      $_SESSION['column'] = "politics";
      $col = 'politics';
    }else{
      $_SESSION['column'] = $_POST['col'];
    }
    $posts = common::_getColumn($col,$cur_page,$cur_id,$user);   
    $processed = [];
    $r['status'] = true;
    $r['m'] = $posts;
    if ($posts) {
      $r['status'] = true; 
      foreach ($posts as $post) {
       $p_data = common::getArticle($post['identifier']);
       array_push($processed, $p_data);
      } 
      $final = ""; 
      foreach ($posts as $post) {
        $v =  common::renderArticle($post,'col',$col);
        $final .= $v;
      } 
      $r['m'] = $final;
   }    				  			
 }else{
   $r['status'] = false;
   $r['m'] = "Invalid column";
 }
 common::emitData($r);
}
public function getItem($item = false,$type = 'user'){
  $item = isset($_POST['item']) ? $_POST['item'] : $item;
  $type = isset($_POST['type']) ? $_POST['type'] : $type;
  if ($item) {
   if ($type == 'user') {
    $r['m'] = common::_getWriter($item);
    $user = $this->commonDatabase->get_data("tp_users",1,false,'u_name',$item);
    if ($user) {
      common::viewUser($user[0]['usr_id']);
    }
  }else{   
    common::_viewArticle($item);
    $item_ = $item;
    $item = common::getArticle($item);
    $item['article_comments'] = false;    
    if (isset($_POST['comments'])) {
      $item['article_comments'] = common::_loadComments($item_);
    }
    $r['m'] = $item;
  }
  if ($r['m'] == false) {
    $r['status'] = false;
    $r['m'] = "Not found";
  }else{
    $r['status'] = true;
  }  			
}else{
 $r['status'] = false;
 $r['m'] = "Invalid column";
}
common::emitData($r);
}
public function loadReplies($comment = false){
  $comment = isset($_POST['comment']) ? $_POST['comment'] : $comment;
  if ($comment) {
   $replies = common::_loadReplies($comment);
   if ($replies) {
    $r['status'] = true;
    $r['m'] = $replies;
  }else{
    $r['status'] = true;
    $r['m']['data'] = false;
  }
}else{
  $r['status'] = false;
  $r['m'] = "Invalid comment";
}
common::emitData($r);
}
public function loadComments($post = false){
  $post = isset($_POST['identifier']) ? $_POST['identifier'] : $post;
  if ($post) {    
    if (isset($_POST['start_id'])) {
      $start = $_POST['start_id'];
      $comments = common::_loadComments($post,$start);
    }else{
      $comments = common::_loadComments($post);
    }   
    if ($comments) {
      $r['status'] = true;
      $r['m'] = $comments;
    }else{
      $r['status'] = false;
      $r['m'] = "No comments";
    }
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid comment";
  }
  common::emitData($r);
}
public function readNotif($notif = false){
  $notif = isset($_POST['notif']) ? $_POST['notif'] : $notif;
  $not = $notif;
  if ($notif && isset($_SESSION['user'])) {
    $data = ['state' => 1];
    $this->commonDatabase->update("tp_notifications",$data,'owner',$_SESSION['user']->id);
    $r['status'] = true;
    $r['m'] = "read";
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid notification";
  }
  common::emitData($r);
}
public function likePost(){
  if (isset($_SESSION['user']) && isset($_POST['article'])) {
    $like = common::_likePost($_POST['article']);
    $r['status'] = $like['status'];
    $r['m']  = $like['m']; 
  }else{
    $r['status'] = false;
    if (!isset($_SESSION['user'])) {
      $r['m'] = "Log in to like post";
    }else{
     $r['m'] = "Invalid article";
   }
 }
 common::emitData($r);
}
public function comment(){
  if (isset($_SESSION['user']) && isset($_POST['type'])) {
    if ($_POST['type'] == 'comment' || $_POST['type'] == 'reply') {
      $time = time();
      $error = false;
      $uploaded = false;
      if (isset($_FILES['file-0'])) { 
        $filename = md5(time());         
        $config['upload_path']          = './uploads/posts/';
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
          $uploaded = $filename.$this->upload->data('file_ext');
          $data = [
            'link' => $filename.$this->upload->data('file_ext'),
            'type' => 'image',
            'added_at' => $time
          ];
          $this->commonDatabase->add("tp_attachments",$data);        
        }
      }
      if (!$error) {
        $data = [];
        if ($uploaded) {
         $att = $this->commonDatabase->get_data("tp_attachments",1,false,'added_at',$time,'link',$uploaded);
         if ($att) {
           $data['attached'] =  $att[0]['id'];
         }else{
          $data['attached'] =  "";
        }
      }else{
        $data['attached'] =  "";
      }
      $data['usr_id'] = $_SESSION['user']->id;
      if ($_POST['type'] == 'comment' && isset($_POST['comment']) && isset($_POST['article'])) {
       $c =  preg_replace("/\s+/", "_", $_POST['comment']);
       if (strlen($c) > 0 || isset($_FILES)) {
         $data['post_identifier'] = $_POST['article'];
         $data['c_date'] = $time;
         $data['comment'] = $_POST['comment'];
         $this->commonDatabase->add("tp_comments",$data);
         $r['status'] = true;
         $r['m'] = common::_loadComments($_POST['article']);
       }else{
        $r['status'] = false;
        $r['m'] = "Comment toot short.";
      }
    }elseif($_POST['type'] == 'reply' && isset($_POST['reply']) && isset($_POST['comment'])){
     $r =  preg_replace("/\s+/", "_", $_POST['reply']);
     if (strlen($r) > 0  || isset($_FILES)) {
       $data['comment_id'] = $_POST['comment'];
       $data['time'] = $time;
       $data['reply'] = $_POST['reply'];
       $this->commonDatabase->add("tp_comment_replies",$data);
       $r = [];
       $r['status'] = true;
       $r['m'] = common::_loadReplies($_POST['comment']);
     }else{
      $r['status'] = false;
      $r['m'] = "Reply toot short.";
    }
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid access";
  }
}
}else{
  $r['status'] = false;
  $r['m'] = "Invalid acces";
}
}else{
  $r['status'] = false;
  $r['m'] = "Log in first to ".$_POST['type'];
}
common::emitData($r);
}
public function delete(){  
  if (isset($_SESSION['user']) && isset($_POST['type']) && isset($_POST['item'])) {
    $item = $_POST['item'];
    $type = $_POST['type'];
    if ($type == "comment") {
      $ch_comment = $this->commonDatabase->get_data("tp_comments",1,false,'comment_id',$item,'usr_id',$_SESSION['user']->id);
      if ($ch_comment) {
        $this->commonDatabase->delete("tp_comments",'comment_id',$item);
        $r['status'] = true;
      }else{
        $r['status'] = false;
        $r['m'] = "Comment not found";
      }
    }elseif($type == "reply"){
      $ch_comment = $this->commonDatabase->get_data("tp_comment_replies",1,false,'reply_id',$item,'usr_id',$_SESSION['user']->id);
      if ($ch_comment) {
        $this->commonDatabase->delete("tp_comment_replies",'reply_id',$item);
        $r['status'] = true;
      }else{
        $r['status'] = false;
        $r['m'] = "Reply not found";
      }
    }elseif($type == "post"){
      $ch_post = $this->commonDatabase->get_data("tp_post",1,false,'identifier',$item);
      if ($ch_post) {
        if (($ch_post[0]['usr_id'] == $_SESSION['user']->id) || in_array($_SESSION['user']->email, $this->config->item("editors"))) {
          $this->commonDatabase->delete("tp_notifications",'sender',$_SESSION['user']->id,'type','post','post_identifier', $item);
          $this->commonDatabase->delete("tp_post",'identifier',$item);
          $r['status'] = true;
        }else{
          $r['status'] = false;
          $r['m'] = "Post not not found";
        }
      }else{
        $r['status'] = false;
        $r['m'] = "Post not not found";
      }
    }else{
      $r['status'] = false;
      $r['m'] = "Invalid access";
    }
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid access";
  }
  common::emitData($r);
}
public function setCol(){
  if (isset($_POST['col']) && in_array($_POST['col'], $this->config->item("tables"))) {
    $_SESSION['column'] = $_POST['col'];
  }
}
public function propCountries(){
     common::propCountries();
}
public function loadCountries(){
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']->id;
     $countries  = $this->commonDatabase->get_cond("tp_countries","id order by name ASC");
     $user_countries = $this->commonDatabase->get_data("tp_user_countries","id AND usr_id='$user'");
     $user_countries_sel = $this->commonDatabase->get_cond("tp_user_countries","usr_id='$user' AND state='1'"); 
     $c_list = [];    
     if ($countries) {       
       foreach ($countries as $country) {
         $selected = 0;
         $c_id = $country['id'];
         $ch_select = $this->commonDatabase->get_cond("tp_user_countries","usr_id='$user' AND state='1' AND country='$c_id'");
         if ($ch_select) {
           $v['selected'] = true;
         }else{
           $v['selected'] = false;
         }
         $v['name'] = $country['name'];
         $v['id'] = $country['id'];         
         array_push($c_list, $v);
         $c_l = "";

         foreach ($c_list as $country) {          
           $c_l .= common::renderCountry($country);
         }
         if (sizeof($countries) == sizeof($user_countries_sel)) {
           $r['all'] = true;
         }else{
           $r['all'] = false;
         }
         $r['m'] = $c_l;
         $r['status'] = true;
       }
     }else{
      $r['status'] = false;
      $r['m'] = "No countries";
    }
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid access";
  }
  common::emitData($r);
} 

public function saveCountries(){
  if (isset($_SESSION['user']) && isset($_POST['countries'])) {
    $user = $_SESSION['user']->id;
    $countries  = $this->commonDatabase->get_cond("tp_user_countries","usr_id='$user'");    
    $p = $_POST['countries'];
    $c_list = [];
    $avail = [];    
    if ($countries) {
      foreach ($countries as $country) {
        $c = $country["country"];
        $cou = $this->commonDatabase->get_data("tp_countries","id='$c' limit 1"); 
        if ($cou) {
          array_push($avail, $cou[0]['name']);
        }
      }
    }
    if ($p == "all") {
      $countries  = $this->commonDatabase->get_data("tp_countries",false,false);
      foreach ($countries as $country) {
       $data = [              
          'state' => 1
        ];
       $this->commonDatabase->update("tp_user_countries",$data,'usr_id',$_SESSION['user']->id);
      }      
    }else{      
      foreach ($avail as $country) {
        $cou = $this->commonDatabase->get_data("tp_countries","name='$country' limit 1"); 
        $c_sel = 0;           
        foreach ($p as $co) {                      
          if ($co == $country) {              
            $c_sel += 1;
          }
        }           
        if ($c_sel > 0) {                    
          $data = [              
                'state' => 1
          ];
          echo $cou[0]['id'];
          $this->commonDatabase->update("tp_user_countries",$data,'usr_id',$_SESSION['user']->id,'country', $cou[0]['id']);            
        }else{
          $data = [              
                'state' => 0
            ];
            $this->commonDatabase->update("tp_user_countries",$data,'usr_id',$_SESSION['user']->id,'country', $cou[0]['id']);
        }        
      }
    }    
    $r['status'] = true;
    $r['m'] = "saved";
  }else{
    $r['status'] = false;
    $r['m'] = "Invalid access";
  }
  common::emitData($r);
}
}