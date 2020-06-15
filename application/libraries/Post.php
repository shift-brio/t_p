<?php 
Class Post {
  public function __construct() {
    $CI = & get_instance();      
    $CI->load->library('session');
    $CI->load->helper('form');          
    $CI->load->library('user_agent');
    $CI->load->helper('url_helper');
    $CI->load->helper('date'); 
    $CI->load->model("posts_database");
    $CI->load->model("PostDatabase");                  
  }
  static function log($type,$item){
    $CI = & get_instance();   
    if (isset($_SESSION['userid'])) {
      $user = $_SESSION['userid'];
      $c = $CI->posts_database->check_l($user,$item);
    }
    else{
      $user = "visitor";
      $c = $CI->posts_database->check_l($CI->get_ip(),$item);
    } 
    $p = $CI->posts_database->c_ip($CI->get_ip());
    if ($p == true) {
      $mode = 'repeat';
    }
    else{
      $mode = 'original';
    }  
    if ($c == true && $type == 'post' && !isset($_SESSION['userid'])) {    
      $data['ip'] = $CI->get_ip();
      $data['time'] = time();
      $data['user'] = $user;
      $data['id']   = '';
      $data['item'] = $item;
      $data['kind'] = "repeat";
      $data['type'] = $type;
      $data['mode'] = $mode;
      $CI->posts_database->add('logs',$data);
    } 
    elseif($c == true && isset($_SESSION['userid'])){

    } 
    else{
      if ($type == 'post') {
        $identifier = $item;
        $p = $this->PostDatabase->get_data("tp_view_count",1,false,'identifier',$identifier);
        if ($p) {
          $v['last_update'] = time();
          $views = $p[0]['views'];
          $views += 1;
          $v['views'] = $views;
          $this->PostDatabase->update('tp_view_count',$v,'identifier',$identifier);
        }
      }
      $data['ip'] = $CI->get_ip();
      $data['time'] = time();
      $data['user'] = $user;
      $data['id']   = '';
      $data['item'] = $item;
      $data['type'] = $type;
      $CI->posts_database->add('logs',$data);
    }
  }
  static function cmp($a,$b){
    if ($a['id'] == $b['id']) {
      return 0;
    }
    return ($a['id'] < $b['id']) ? -1 : 1;
  }
  static function sorter($arr){  
    $CI = & get_instance();       
    usort($arr, [$CI,'cmp']);
    return $arr;
  }  
  static function emitData($val){
    header("Content-type:application/json");
    echo json_encode($val);
    exit();
  } 
  static  function lc($identifier){
    $CI = & get_instance(); 
    if (!isset($identifier)) {
      return 0;
    }
    else{
      $data['identifier']=$identifier;
      return $CI->load->view('like_count',$data,true);
    }
  } 
  static  function get_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN'; 
    return $ipaddress;
  }
  public static function getPopular(){
    $CI = & get_instance();
    $d = $CI->PostDatabase->get_data("tp_view_count",false,false,false,false,false,false,"ORDER BY views DESC LIMIT 15");
    if ($d) {
      $processed = [];
      foreach ($d as $post) {
        $x['identifier'] = $post['identifier'];
        $x['views'] = post::getViews($post['identifier']);
        array_push($processed, $x);
      }
      $finished = [];
      foreach ($processed as $post) {
        $p_data = $CI->PostDatabase->get_data("tp_post",1,false,'identifier',$post['identifier']);
        if ($p_data) {
          $p_data = $p_data[0];
          $user = $CI->PostDatabase->get_data("tp_users",1,false,'usr_id',$p_data['usr_id'])[0];
          $views = post::getViews($post['identifier']);
          $views = $views['text']." ".$views['txt'];
          $x = "
              <div value='".($post['identifier'])."' class='waves-effect p_sin p_title_'><p class='p_title'><strong>".($p_data['title'])."</strong></p>
              <span><a class='anchor'>@".($user['u_name'])."</a><span class='dot'>&nbsp;.&nbsp;</span>$views</span>
              &nbsp;&nbsp;
                  </div>
            ";
            array_push($finished, $x);
        }
      }
      return $finished;
    }else{
      return false;
    }
  }
  public static function getViews($identifier = false,$text = false){
    if ($identifier) {
      $CI    = & get_instance();
      $views = $CI->PostDatabase->get_data('tp_view_count',1,false,'identifier',$identifier);      
      if ($views) {
        $views = $views[0];
        $count = [];
        $count['numbers'] = $views['views'];  
        $count['last_update'] = $views['last_update'];      
        if ($count['numbers'] > 999 && $count['numbers'] < 1000000) {
          $count['text'] = number_format(($count['numbers']/1000),1)."K";
        }
        elseif($count['numbers'] > 999999 && $count['numbers'] < 1000000000){
          $count['text'] = number_format(($count['numbers']/1000000),1)."M";
        }elseif ($count['numbers'] > 999999999 && $count['numbers'] < 1000000000000) {
          $count['text'] = number_format(($count['numbers']/1000000),1)."B";
        }else{
          $count['text'] = $count['numbers'];
        }
        if ($count['numbers'] == 1) {
          $count['txt'] = 'View';
        }else{
          $count['txt'] = 'Views';
        }
      }else{
        $count = [];
        $count['numbers'] = 0; 
        $count['text'] = '';
        $count['txt'] = 'Views';
      }
      return $count;
    }else{
      return false;
    }
  }  
  static function processPost($post = false){
    $CI = & get_instance(); 
    if ($post) { 
      $l['post_identifier'] = $post['identifier'];
      $views = post::getViews($l['post_identifier']);
      $post['views'] =  $views['text']." ".$views['txt']; 
      $type = $post['type'];
      $post['body'] = htmlspecialchars_decode($post['body']);
      if (strlen($post['body']) > 300) {        
        $moretext = '
          <div class="more-hold">
            <div class="moretext">
              <button class="more-text">Read More</button>
            </div>
          </div>
        ';
        $m_class = "less";  
      }else{
        $moretext = "";
        $m_class = "";
      }     
      $post['body'] = nl2br($post['body']);
      $user = $CI->PostDatabase->get_data('tp_users',1,false,'usr_id',$post['usr_id'],false,false,false,false,false,'usr_id');
      if (!$user) {
        return false;
      }else{
        $user = $user[0];
      }
      if (isset($_SESSION['userid']) && $_SESSION['userid'] == $post['usr_id']) {
        $menu="
        <ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel'>
            <li><a><button class='d_btn' onclick='transferpost(this)' value='".$post['identifier']."' data-toggle='modal' data-target='#repostPost'>Repost</button></a></li>
            <li><a><button class='d_btn' btn-default' onclick='transfer(this)' data-toggle='modal' value='".$post['identifier']."'  data-target='#deletePost'>Delete</button></a></li>
            <li>
              <a>
                <button class='d_btn btn-default' onclick='transfer_edit(this.value)' data-toggle='modal' value='".$post['identifier']."'  data-target='#editPost'>Edit
                </button>
              </a>
            </li>                     
          </ul>
        ";
      }elseif(isset($_SESSION['userid'])){
        $menu="
        <ul class='dropdown-menu pull-right' role='menu' aria-labelledby='dLabel'>
             <li><a><button value='".$post['identifier']."'  class='d_btn' onclick='transferpost(this)' data-toggle='modal' data-target='#repostPost'>Repost</button></a></li>                            
        </ul>";
      }else{
        $menu = "";
      }
      if ($user['u_photo_name'] == '') {
        $profile = base_url("uploads/profile/acfa4c75570bd368adb04e2282754684.png");
      }else{
        $profile = base_url("uploads/profile/".$user['u_photo_name']);
      }
      $url_user = base_url("profiles/".$user['tp_mail']);
      $u_name = $user['u_name'];
      $logo = base_url()."favicon.ico"; 
      $comment_url=base_url()."posts/get_comments";
      $twitter_image=base_url()."uploads/system/twitter.png";
      $facebook_image=base_url()."uploads/system/facebook.png";
      $copy_image =base_url()."uploads/system/copy.png";
      $linked_in = base_url("uploads/system/linked_in.png");
      $url_loader=base_url()."uploads/system/ajax-common.gif";
      $com_b = base_url()."uploads/system/comment.png";

      if ($type == 'normal') {
        $attach = "";
      }elseif($type == 'image'){
        $url = base_url("uploads/posts/").$post['attachment'];
        $attach = "
          <div class='img-article'>
            <img class='responsive-img' src='".$url."'>
          </div>
        ";
      }elseif($type == 'video'){
        $file_url=base_url()."uploads/posts/".$post['attachment'];
        $vid['v'] = $file_url;
        $vid['id']= mb_substr(sha1($row[7]), 0,5);
        $attach  = $CI->load->view('video',$vid,true);
      }else{
        $attach = "";
      }
      if (isset($_SESSION['names'])) {
        $onclick="onclick='like(this)'";
      }
      else{
        $onclick="";
      }
      $like_rows = $post['likes'];
      $u_like = false;
      if (isset($_SESSION['userid'])) {
       $u_like = $CI->PostDatabase->get_data("tp_likes",1,false,'post_identifier',$post['identifier'],'usr_id',$_SESSION['userid']);
      }
      if ($like_rows > 0 && isset($_SESSION['names']) && $u_like) {
        $like_style = "style='color:#cd0000;'";
      }
      else{
        $like_style = "style=' color:#D9D9D9;'";
      }
      $t = strtolower($post['post_column']);
      if ($t == "politics") {
        $column = "Politics & leadership";
      }
      elseif ($t=="life") {
        $column="life & style";
      }
      elseif ($t=="money") {
        $column="money & business";
      }elseif ($t == "estate") {
        $column="Real Estate";        
      }
      else{
        $column = $post['post_column'];
      }
      $kind = $post['kind'];
      if ($kind != 'normal') {
        $column = $column;
      }else{
        $column = '';
      }
      if (isset($_SESSION['userid'])) {
        $pop = "data-toggle='popover' data-trigger='hover' data-placement='top'";
      }
      else{
        $pop ="";
      }
      $fb =base_url()."view/".$post['identifier'];   
      $shares = "
          <ul  class='dropdown-menu pull-right' role='menu'>
           <li>
             <a  class='facebook customer share' href='http://www.facebook.com/sharer.php?u=$fb' title='Facebook share' target='_blank' ><img class='fb' src='$facebook_image'> Facebook</a>
           </li>
          <li>
            <a class='drop_a' href='http://twitter.com/intent/tweet?url=$fb&text=".$post['title']."'><img class='twttr' src='$twitter_image'>
                   Twitter
            </a>
          </li> 
          <li>
            <a class='drop_a IN-widget customer share' href='https://www.linkedin.com/cws/share?url=$fb'><img class='twttr' src='$linked_in'>
                   LinkedIn
            </a>
          </li> 
          <li>
            <a class='drop_a link-share' data-toggle='modal' data-target='#copy_link' data-src='$fb'><img class='twttr' src='$copy_image'>
                   Copy Link
            </a>
          </li>                                            
        </ul>
      "; 
      if (!$CI->agent->mobile()) {
        $suppl['id'] = $user['usr_id'];
        $data = $CI->load->view('popover',$suppl,true);
        $st='-px;width:100px !important;';
        $set='';    
      } 
      else{
        $data = "";
        $st='-3px !important;margin-right:40%;';
        $set='margin-left:50%;margin-top:-25%; width:80px;height:auto;';  
      }
      $day = 60 * 60 * 23.9;
      if ((time() - $post['added_at']) > $day) {
        if (mb_substr(date("P"), 0,1) == '+') {
            $post_date = date("d",$post['added_at'] + $CI->config->item('offset_time'))." ".date("M",$post['added_at'])." ".date("y",$post['added_at'] + $CI->config->item('offset_time'));           
          }
          else{
            $post_date = date("d",$post['added_at'] - $CI->config->item('offset_time'))." ".date("M",$post['added_at'])." ".date("y",$post['added_at'] - $CI->config->item('offset_time'));     
          }
      }
      else{
        $post_date = timespan($post['added_at'],time(),1);
      }
      $identifier = $post['identifier'];
      if ($post['likes'] > 999 && $post['likes'] < 1000000) {
        $post['likes'] = number_format(($post['likes']/1000),1)."K";
      }
      elseif($post['likes'] > 999999 && $post['likes'] < 1000000000){
        $post['likes'] = number_format(($post['likes']/1000000),1)."M";
      }

      if ($post['comments'] > 999 && $post['comments'] < 1000000) {
        $post['comments'] = number_format(($post['comments']/1000),1)."K";
      }
      elseif($post['comments'] > 999999 && $post['comments'] < 1000000000){
        $post['comments'] = number_format(($post['comments']/1000000),1)."M";
      }
      if ($post['type'] != 'add') {
        $post_data = "
            <div class='content article'>
              <div class='main-details'>
                <div class='a_head'>
                  <a class='anchor' $pop data-content='$data' href='$url_user'>
                   <img class='prof' src='$profile' alt='badge'>
                  </a>
                  <div class='d_s'>
                    <span class='u_na'>
                      $u_name
                    </span><br>
                    <span class='p_t bold'>
                      $post_date <span class='badge red-text grey lighten-3'>$column</span>
                    </span>
                  </div>
                </div>
                <div class='a_tool'>
                  <div class='dropdown dropper'>
                  <button class='grey-text dropdown-button waves-effect btn btn-tool grey lighten-5 glyphicon glyphicon-chevron-down'
                  data-toggle='dropdown'>
                  </button>               
                    ".$menu."                   
                  </div>
                </div>
                <h5 class='p_sin waves-effect' value='$identifier'>
                  <strong>".$post['title']."</strong>
                </h5>
                ".$attach." 
                <div class='main-content ".$m_class." post".$identifier."'>
                  ".$moretext."
                  ".$post['body']."
                </div>                          
              </div>          
              <div class='a-foot'>
                <div style='float:left;'>
                  <button value='".$identifier."'  style='color:#cd0000;' id='comment_button' table='posts' onclick='f_com(this)' class='options waves-effect' data-original-title='View comments'>
                  <img class='com_b' src='$com_b'> ".$post['comments']."</button>                  
                  <div class='l_holder'>
                    <button id='likes_btn".$identifier."' value='".$identifier."' $onclick $like_style value='".$identifier."' class='p_table".$identifier." waves-effect options glyphicon glyphicon-heart'></button>
                    <small style='color:#cd0000;'> <span id='likes".$identifier."'>".$post['likes']."</span>
                    </small>
                  </div> 
                  <div class='view-holder'>
                      <small style='color:#cd0000;'> <span id='views".$identifier."'>".$post['views']."</span>
                      </small>
                  </div>                 
                </div>
                  <div style='float:right;'>    
                   <div class='dropdown'> 
                   <button style='margin-top: -3px;' data-toggle='dropdown' class='btn-tool waves-effect dropdown-button btn btn-default glyphicon glyphicon-share-alt' ></button>
                    $shares
                  </div>
                </div>
              </div>
            </div>
        ";
      }else{
        $post_data = "
            
        ";
      }
      return $post_data;
    }else{
      return false;
    }
  }
}
?>