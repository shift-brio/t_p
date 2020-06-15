<?php 
class Common
{

	public function __construct() {
		$CI = & get_instance();      
		$CI->load->library('session');
		$CI->load->helper('form');
		$CI->load->model('PostDatabase');      
		$CI->load->library('user_agent');
		$CI->load->helper('url_helper');
		$CI->load->helper('date'); 
		$CI->load->model("commonDatabase");		           
	}
	static function check_name($name = false){
		if ($name) {
			$regex = "/[a-zA-Z\-0-9]|\~|\_|\-|\./"; 
			$name = str_split($name);
			for ($i=0; $i < sizeof($name); $i++) { 
				if (!preg_match($regex, $name[$i]) && $name[$i] != " ") {
					return false;
				}
			}
		}else{
			return false;
		}
		return true;
	}
	static function update_user_session($username = false){
		if ($username) {
			$CI = &get_instance();
			$user = $CI->commonDatabase->get_data('tp_users',1,false,'u_name',$username);
			if ($user) {
				$user = $user[0];
				$name = $user['f_name']." ".$user['l_name'];
				if (strlen($name > 15)) {
					$name = mb_substr($name, 0, 15)."...";
				}
				$user = [
					'id'      => $user['usr_id'],
					'email'   => $user['u_email'],
					'profile' => $user['u_photo_name'],
					'banner'  => $user['banner'],
					'country' => $user['country'],
					'username'=> $user['u_name'],
					'tp_mail' => $user['tp_mail'],
					'f_name'  => $user['f_name'],
					'l_name'  => $user['l_name'],
					'full_name' => $name,
					'joined'  => $user['joined'],
				];
				$_SESSION['user'] = json_decode(json_encode($user));
			}
		}
	}
	static function emitData($val){
		header("Content-type:application/json");
		echo json_encode($val);
		exit();
	}
	static function getTime(){
		$CI = &get_instance();
		if ((time() - strtotime($CI->config->item("updated_at"))) < (60 * 60 * 24)) {
			$data['time'] = '?'.time();				
		}else{						
			$data['time'] = '';	
		}
		return $data['time'];
	}
	static function get_404(){
		redirect(base_url("404"));
	}
	static function format_number($num = false){
		if ($num) {
			if ($num >=0 && $num < 1000) {
				$text = $num;
			}
			elseif ($num > 999 && $num < 1000000) {
				$text = number_format(($num/1000),1)."K";
			}
			elseif($num > 999999 && $num < 1000000000){
				$text = number_format(($num/1000000),1)."M";
			}elseif ($num > 999999999 && $num < 1000000000000) {
				$text = number_format(($num/1000000),1)."B";
			}
			return $text;
		}
		return 0;
	}
	static function getLikes($identifier = false){
		if ($identifier) {
			$CI = &get_instance();
			$likes = $CI->commonDatabase->count("tp_likes",'post_identifier',$identifier);

			return $likes;
		}else{
			return false;
		}
	}
	static function _viewArticle($article = false){
		if ($article) {		
			$item = $article;
			$CI = &get_instance();
			$ip = common::getIp();	
			if (isset($_SESSION['user'])) {
				$user = $_SESSION['user']->username;
				$article = $CI->commonDatabase->get_data("tp_post_views",1,false,"viewer",$user,'identifier',$article);
			}else{
				$user = "visitor";
				$article = $CI->commonDatabase->get_data("tp_post_views",1,false,"ip_address",$ip,'identifier',$article,'viewer','visitor');
			}						
			if (!$article) {
				$data = [
					'viewer' => $user,
					'identifier' => $item,
					'ip_address' => $ip,
					'date' => time()
				];
				$CI->commonDatabase->add("tp_post_views",$data);
			} 
		}
	}
	static function _getWriter($username = false){
		if ($username) {
			$CI = &get_instance();
			$user = $CI->commonDatabase->get_data("tp_users",1,false,'u_name',$username);
			if (!$user) {
				$user = $CI->commonDatabase->get_data("tp_users",1,false,'tp_mail',$username);
			}
			if ($user) {				
				$user = $user[0];
				$posts = $CI->commonDatabase->count("tp_post",'usr_id',$user['usr_id']);
				$followers =$CI->commonDatabase->count("tp_circles",'u_id_added',$user['usr_id']);
				$name = ucwords($user['f_name']." ". $user['l_name']);
				if (strlen($name) > 15) {
					$name = mb_substr($name, 0,15);
				}
				if ($user['u_photo_name'] != "") {
					$prof = base_url("uploads/profile/".$user['u_photo_name']);
				}else{
					$prof = base_url("uploads/system/default_prof_white.png");
				} 
				$user = [
					'id' => $user['usr_id'],
					'username' => $user['u_name'],
					'u_profile' =>  $prof,
					'u_banner' => base_url("uploads/profile/".$user['banner']), 
					'bio' => $user['bio'],
					'country' => $user['country'],
					'name' => $name,					
					'isActive' => isset($_SESSION['user']) ? true : false,
					'posts' => common::format_number($posts),
					'followers' => common::format_number($followers),
				];
				return $user;
			} else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function getViews($identifier = false){
		if ($identifier) {
			$CI = &get_instance();
			$views = $CI->commonDatabase->count("tp_post_views",'identifier',$identifier);
			return $views;
		}else{
			return false;
		}
	}
	static function _getHot($type = 'trends'){
		if ($type == 'trends' || $type == 'popular') {
			$CI = &get_instance();
			if ($type == 'trends') {
				$days = time() - 60 * 60 * 24 * 7;	
			}else{
				$days = strtotime("01-01-2017");	
			}				
			$posts = $CI->commonDatabase->get_cond("tp_post","state = '1' AND added_at >= $days");
			if ($posts) {
				$trend_posts = [];
				foreach ($posts as $post) {
					$p = [];
					$p['id'] = $post['identifier'];
					$p['count'] = 0;
					$p['details'] = common::getArticle($post['identifier']);
					$p_views = $CI->commonDatabase->get_data("tp_post_views",false,false,'identifier',$p['id']);
					if ($p_views) {
						foreach ($p_views as $view) {
							$p['count'] += 1;
						}
					}
					array_push($trend_posts, $p);
				}
				$t_posts = [];
				foreach ($trend_posts as $post) {
					if ($post['count'] >= 10) {
						$post['formatted'] = common::format_number($post['count']);
						array_push($t_posts, $post);
					}
				}
				$trend_posts = $t_posts;
				if (sizeof($trend_posts) > 0) {					
					$sorted = common::asorter($trend_posts);		
					$trends = [];
					for ($i=0; $i < 15; $i++) { 
						$post = $i + 1;
						$sorted[$i]['position'] = "#".$post;
						if (isset($sorted[$i]['count'])) {
							array_push($trends, $sorted[$i]);
						}
					}
					$data = [];
					$data['last_update'] = time();
					$data['list'] = $trends;
					$file  = fopen ('./'.$type.'.json', "w");
					fwrite($file, json_encode($data));
					fclose($file);
					return $trends;		
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function getComments($identifier = false){
		if ($identifier) {
			$CI = &get_instance();
			$comments = $CI->commonDatabase->count("tp_comments",'post_identifier',$identifier);
			return $comments;
		}else{
			return false;
		}
	}
	static function countText($count = false){
		if ($count) {
			$text = "";
			if ($count > 999 && $count < 1000000) {
				$text = number_format(($count/1000),1)."K";
			}
			elseif($count > 999999 && $count < 1000000000){
				$text = number_format(($count/1000000),1)."M";
			}
			elseif($count > 999999999 && $count < 1000000000000){
				$text = number_format(($count/1000000000),1)."B";
			}     

			return $text;
		}
		return false;
	}
	static function getArticle($identifier = false){
		if ($identifier) {
			$CI = &get_instance();
			$table = "tp_post";
			$article = $CI->commonDatabase->get_data($table,1,false,'identifier',$identifier);
			if ($article) {				
				if ((Int)$article[0]['state'] == 1 || (isset($_SESSION['user']) && in_array($_SESSION['user']->email, $CI->config->item("editors")))) {
					$article = $article[0];
					$user = $CI->commonDatabase->get_data("tp_users",1,false,'usr_id',$article['usr_id']);
					$u_data = $user[0];
					if ($user) {
						$user = "@".$user[0]['u_name'];
					}else{
						$user = false;
					}
					if ($article['type'] == "share_with") {
						$shared = $CI->commonDatabase->get_data("tp_share_with",1,false,'sharing',$article['identifier']);
						if ($shared) {
							$r['shared'] = common::getArticle($shared[0]['identifier']);
							$r['share_list']  = $shared[0]['shared_to'];
							$r['share_count']  = common::format_number($CI->commonDatabase->count("tp_share_with",'identifier',$shared[0]['identifier']));
						}

					}
					if ($article['attached'] != '0') {						
						$attachment = common::getAttched($article['attached']);
					}else{
						$attachment = false;						
					}
					$name = ucwords($u_data['f_name']." ". $u_data['l_name']);
					if (strlen($name) > 15) {
						$name = mb_substr($name, 0,15)."...";
					}
					$r['status'] = true;
					if ($article['state'] == 1) {
						$r['published'] = true;
					}else{
						$r['published'] = true;
					}					
					$r['likes']    = common::format_number(common::getLikes($identifier));
					$r['views']    = common::format_number(common::getViews($identifier));
					$r['comments'] = common::format_number(common::getComments($identifier));
					$r['article_fb'] = "http://www.facebook.com/sharer.php?u=".base_url("article/".$article['link']."?p='fb'");
					$r['article_twitter'] = "http://twitter.com/intent/tweet?url=".base_url("article/".$article['link']."?p='twitter'")."&text=".$article['title'];
					$r['article_linked'] = "https://www.linkedin.com/cws/share?url=".base_url("article/".$article['link']."?p='linked'")."&text=".$article['title'];
					if (isset($_SESSION['user']) && $article['usr_id'] == $_SESSION['user']->id) {
						$r['owner'] = true;
					}else{
						$r['owner'] = false;
					}
					$r['user_following'] = 'offline';
					if (isset($_SESSION['user'])) {
						$u = $_SESSION['user']->id;
						$ch_liked = $CI->commonDatabase->get_data("tp_likes",1,false,'usr_id',$u,'post_identifier',$identifier);
						$ch_follow = $CI->commonDatabase->get_data("tp_circles",1,false,'usr_id_adding',$u,'u_id_added',$article['usr_id']);
						if ($ch_follow) {
							$r['user_following'] = true;
						}else{
							$r['user_following'] = false;
						}
						if ($ch_liked) {
							$r['user_liked'] = true;
						}else{
							$r['user_liked'] = false;
						}
					}else{
						$r['user_liked'] = null;
					}
					$t = strtolower($article['post_column']);
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
					$column = ucfirst($column);
					if (isset($_SESSION['user'])) {
						$r['isOnline'] = true;
					}	else{
						$r['isOnline'] = false;
					}					
					if ($u_data['u_photo_name'] != "") {
						$prof = base_url("uploads/profile/".$u_data['u_photo_name']);
					}else{
						$prof = base_url("uploads/system/default_prof_white.png");
					} 
					if ($article['body'] == '<p><br></p>') {
						if ($article['type'] == "share_with") {
							$article['body'] = "<p>Shared article</p>";
						}
					} 
					$article['body']  = preg_replace("/&lsquo;/", '&apos;', htmlspecialchars_decode($article['body']));
					$article['body']  = preg_replace("/&rsquo;/", '&apos;', htmlspecialchars_decode($article['body']));
					if ((time() - $article['added_at']) <= (60 * 60 * 7 * 24)) {
					   $date = timespan($article['added_at'], time(),1);
					}else{
						 $date = date('d-m-Y',$article['added_at']);
					} 
					if ($article['type'] == "share_with") {
						$shared = $CI->commonDatabase->get_data("tp_share_with",1,false,"sharing",$article['identifier']);
		      	if ($shared) {
		      		$shared = $CI->commonDatabase->get_data("tp_post",1,false,"identifier",$shared[0]['identifier']);
		      		if ($shared) {
		      			$article['body'] = $article['body']."&nbsp;<a href='".(base_url("article/".$shared[0]['link']))."'> <span class='material-icons'>launch</span></a>";
		      		}
		      	}
		      }     
					$r['details'] = [
						'identifier' => $article['identifier'],
						'column' => $column,
						'url' => base_url("article/".$article['link']),
						'title' => $article['title'],
						'attachment' => $attachment,
						'content' => $article['body'],
						'content_min' =>mb_substr(htmlspecialchars_decode($article['body']), 0,300),
						'author' => $user,
						'usr_id' => $article['usr_id'],
						'author_clean' => $u_data['u_name'],
						'author_name' => $name,
						'author_img' => $prof,						
						'type' => $article['type'],
						'published'  => $article['added_at'],
						'date'  => $date
					];
				}else{
					$r['status'] = true;
					$r['published'] = false;					
				}
				return $r;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function cmp($a,$b){
		if ($a['count'] == $b['count']) {
			return 0;
		}
		return ($a['count'] < $b['count']) ? -1 : 1;
	}
	static function acmp($a,$b){
		if ($a['count'] == $b['count']) {
			return 0;
		}
		return ($a['count'] > $b['count']) ? -1 : 1;
	}
	static function sorter($arr){  
		$CI = & get_instance();       
		usort($arr, ['common','cmp']);
		return $arr;
	} 
	static function asorter($arr){  
		$CI = & get_instance();       
		usort($arr, ['common','acmp']);
		return $arr;
	}
	static function _getColumn($col = false, $cur_page = false,$cur_id = false,$user = false){
		if ($col) {
			if ($col == "feeds" && !isset($_SESSION['user'])) {
				$col = 'politics';
				$_SESSION['column'] = "politics";
			}
			$CI = & get_instance();
			if ($cur_id) {
				$cur_id = $CI->commonDatabase->get_data("tp_post",1,false,'identifier',$cur_id);	
				if ($cur_id) {
					$cur_id = $cur_id[0]['id'];
				}else{
					$cur_id = 0;
				}  			
				$limit = 5;
			}else{
				$limit  = 10;
			}
			$ors = common::user_ors();
			if ($col == "videos") {
				if ($cur_id) {
					$st = "attached != '0' AND state='1' AND  id < '$cur_id' ".$ors." order by added_at DESC limit $limit";	
				}else{
					$st = "attached != '0' AND state='1' ".$ors." order by added_at DESC limit $limit";	
				}
			}
			elseif ($cur_page && $cur_page == "user") {
				$user = common::_getWriter($user);
				if ($user) {
					$user = $user['id'];
					if ($cur_id) {
						$st = "usr_id = '$user' AND id < '$cur_id' order by added_at DESC limit $limit";	
					}else{
						$st = "usr_id = '$user' order by added_at DESC limit $limit";	
					}
				}else{

				}
			}else{
				if ($cur_id) {
					$st = "post_column = '$col' AND state='1' AND  id < '$cur_id' ".$ors." order by added_at DESC limit $limit";	
				}else{
					$st = "post_column = '$col' AND state='1' ".$ors." order by added_at DESC limit $limit";	
				}
			}			
			if ($col == "feeds" && isset($_SESSION['user'])) {
				$posts  = common::getFeeds($_SESSION['user']->id,$cur_id);
			}else{
				$posts = $CI->commonDatabase->get_cond("tp_post",$st);
				if ($col == 'videos') {
					$ps = [];
					
					if ($posts) {
                  foreach ($posts as $post) {
                     $attachment = common::getAttched($post['attached']);
                     if ($attachment && $attachment['type'] == 'yt') {
                        array_push($ps, $post);
                     }
                  }
               }
					$posts = $ps;
				}

			}
			return $posts;
		}else{
			return false;
		}
	} 
	static function _loadReplies($comment = false){
		if ($comment) {
			$CI = & get_instance();
			$replies = $CI->commonDatabase->get_cond("tp_comment_replies","comment_id ='$comment' order by reply_id DESC");
			if ($replies) {
				$processed = [];
				foreach ($replies as $reply) {
					$user = $CI->commonDatabase->get_data("tp_users",1,false,'usr_id',$reply['usr_id']);
					if ($user) {  
						if ((time() - $reply['time']) <= (60 * 60 * 7 * 24)) {
						   $date = timespan($reply['time'], time(),1);
						}else{
							 $date = date('d-m-Y',$reply['time']);
						}        		
						$data = [           			         	
							'user' => common::_getWriter($user[0]['u_name']),
							'date' => $date,	 
							'content' => htmlspecialchars_decode($reply['reply']),
							'attachment' => common::getAttched($reply['attached']),
							'id' => $reply['reply_id']        	
						];
						if (isset($_SESSION['user'])) {
							$data['isOnline'] = true;
						}else{
							$data['isOnline'] = false;
						}
						if (isset($_SESSION['user']) && $_SESSION['user']->id == $reply['usr_id']) {
							$data['isOwner'] = true;
						}else{
							$data['isOwner'] = false;
						}
						array_push($processed, $data);						
					}
				} 
				$d = [];
				$d['data'] = $processed;          
				$d['count'] = common::format_number($CI->commonDatabase->count("tp_comment_replies",'comment_id',$comment));

				return $d;        
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function getAttched($id = false){
		if ($id) {
			$CI = & get_instance();
			$attached = $CI->commonDatabase->get_data("tp_attachments",1,false,'id',$id);
			if ($attached) {
				$attachment['type'] = $attached[0]['type'];
				$attached = $attached[0];
				$attachment['meta'] = $attached['meta'];
				if ($attached['type'] == "image") {
					$attachment['src'] = base_url("uploads/posts/".$attached['link']);
				}elseif($attached['type'] == "yt"){
					$attachment['type'] = "yt";
					$attachment['src'] = $attached['link'];
				}
				elseif($attached['type'] == "video"){
					$attachment['src'] = base_url("uploads/videos/".$attached['link']);
				}else{
					$attachment['src'] = "unknown";
				}

				return $attachment;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function _loadComments($identifier = false,$start = false){
		if ($identifier) {
			$CI = & get_instance();
			if ($start) {
				$comments = $CI->commonDatabase->get_cond("tp_comments","post_identifier = '$identifier' AND comment_id < '$start' order by comment_id DESC limit 10");
			}else{
				$comments = $CI->commonDatabase->get_cond("tp_comments","post_identifier = '$identifier' order by comment_id DESC limit 20");
			}
			if ($comments) {
				$processed = [];
				foreach ($comments as $comment) {
					$data = [];
					$user = $CI->commonDatabase->get_data("tp_users",1,false,'usr_id',$comment['usr_id']);
					if ($user) {
						$data['user'] = common::_getWriter($user[0]['u_name']);
					}else{
						$data['user'] = false;
					}
					if (isset($_SESSION['user'])) {
						$data['isOnline'] = true;
					}else{
						$data['isOnline'] = false;
					}
					if (isset($_SESSION['user']) && $_SESSION['user']->id == $comment['usr_id']) {
						$data['isOwner'] = true;
					}else{
						$data['isOwner'] = false;
					}
					if ((time() - $comment['c_date']) <= (60 * 60 * 7 * 24)) {
					   $date = timespan($comment['c_date'], time(),1);
					}else{
						 $date = date('d-m-Y',$comment['c_date']);
					}
					$data['id'] = $comment['comment_id'];
					$data['date'] = $date;
					$data['content'] = htmlspecialchars_decode($comment['comment']);
					if ((INT)$comment['attached'] != 0) {
						$data['attachment'] = common::getAttched($comment['attached']);
					}
					else{
						$data['attachment'] = false;
					}
					array_push($processed, $data);  		   	 	 
				}
				return $processed;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function returnHot($type =  false, $file = false){
		if ($type && $type == "trends" || $type == "popular") {
			if ($file) {
				$filesize  = filesize ('./'.$type.'.json');
				if ($filesize > 0) {
					$file = json_decode(file_get_contents('./'.$type.'.json'));
					$hot = $file->list;
				}else{
					$hot = common::_getHot($type);
				}
			}else{
				if ($type == 'trends' || $type == 'popular') {
					if ($type == 'popular') {
						$filesize  = filesize ('./'.$type.'.json');
						if ($filesize > 0) {
							$file = json_decode(file_get_contents('./'.$type.'.json'));
							if ($file->last_update) {
								if ((time() - $file->last_update)/60 <= 30) {	    			
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
					}else{
						$hot = common::_getHot($type);
					}
				}
			}
			return $hot;
		}else{
			return false;
		}
	}
	static function getFeeds($user = false,$start_id = false){
		if($user){
			$CI = & get_instance();
			$following = $CI->commonDatabase->get_data("tp_circles",false,false,'usr_id_adding',$user);
			$ors = "(";
			if ($following) {
				for ($i=0; $i < sizeof($following); $i++) { 
					if (sizeof($following) == 1) {
						$ors .= "usr_id = '".$following[$i]['u_id_added']."')";
					}else{
						if ($i < (sizeof($following) - 1)) {
							$ors .= "usr_id = '".$following[$i]['u_id_added']."' or ";
						}else{
							$ors .= "usr_id = '".$following[$i]['u_id_added']."')";
						}
					}
				}
				if ($ors != ")") {
					$time = time();
					if ($start_id) {						
						$cond = "state='1' AND added_at <= '$time' AND id < '$start_id' AND ".$ors." order by added_at DESC limit 5";
					}else{
						$cond = "state='1' AND added_at <= '$time' AND ".$ors." order by added_at DESC limit 10";
					}
					
					$posts = $CI->commonDatabase->get_cond("tp_post",$cond);
					return $posts;
				}else{
					return false;
				}
			}return false;
		}
		return false;
	}
	static function _likePost($article = false){
		if ($article) {
			$CI = & get_instance();
			$article = $CI->commonDatabase->get_data("tp_post",1,false,'identifier',$article,'state','1');
			if ($article) {
				$data = [
					'usr_id' => $_SESSION['user']->id,
					'post_identifier' => $article[0]['identifier'],
               'added_at' => time()
				];
				$ch_like = $CI->commonDatabase->get_data("tp_likes",1,false,'post_identifier',$article[0]['identifier'],'usr_id',$_SESSION['user']->id);
				if ($ch_like) {
					$CI->commonDatabase->delete("tp_likes",'post_identifier',$article[0]['identifier'],'usr_id',$_SESSION['user']->id);
					$r['m']['kind'] = "unliked";
				}else{
					$r['m']['kind'] = "liked";
					$CI->commonDatabase->add("tp_likes",$data);
				}
				$r['status'] = true;
				$identifier = $article[0]['identifier'];
				$r['m']['likes_count'] = common::format_number(common::getLikes($identifier));
				$r['m']['view_count'] = common::format_number(common::getViews($identifier));
				$r['m']['comments_count'] = common::format_number(common::getComments($identifier));
			}else{
				$r['status'] = false;
				$r['m'] = "Article not found";
			}
		}else{
			$r['status'] = false;
			$r['m'] = "Article not found";
		}
		return $r;
	}
	static function renderArticle($post = false, $page = 'col',$column = false){
		if ($post) {
			$CI = &get_instance();
			$p = common::getArticle($post['identifier']);				
			$details = $p['details'];	
			$attached = "";		
			if ($p) {		
				$author_url = base_url("writer/".$details['author_clean']);
				if ($details['attachment']) {
					$attachment = $details['attachment'];
					if ($attachment['type'] == 'image') {
						$attached = '<img src="'.$attachment['src'].'" alt="post" class="post-image">';							
					}elseif($attachment['type'] == 'yt'){
						$attached = '<img src="'.$attachment['meta'].'" alt="post" class="post-image yt-image" data-yt="'.$attachment['src'].'">';
					}
				}
				if ($p['user_following'] == true) {
					$following = '<button data-position="bottom" data-tooltip="Unfollow writer"  class="tooltipped follow-post material-icons following">done</button>';
				}else{
					$following = '<button data-position="bottom" data-tooltip="Follow writer"  class="tooltipped follow-post material-icons following">person_add</button>';
				}	
				if ($p['user_liked']) {
					$like_src = base_url("uploads/system/liked.png");
				}else{
					$like_src = base_url("uploads/system/like.png");
				}
				if (!isset($_SESSION['user'])) {
					$p_option = '';
					$like_class = "";
				}else{
					$like_class = "liker";
					if (isset($_SESSION['user'])) {
						$p_option = '<div class="right post-options">
						<button data-position="left" data-tooltip="Options" data-activates="dropdown_'.$details['identifier'].'" class="material-icons tooltipped dropdown-trigger option-btn">expand_more</button>
						<!-- Dropdown Structure -->
						<ul id="dropdown_'.$details['identifier'].'" class="dropdown-content">			    
						<li class="share-with red-text"><a class=""><i class="material-icons">share_alt</i>TalkPoint</a></li>
						<li class="del-post"><a><i class="material-icons">delete</i>Delete</a></li>
						</ul> 
						</div>';
					}else{
						$p_option = '<div class="right post-options">
						<button data-position="left" data-tooltip="Options" data-activates="dropdown_'.$details['identifier'].'" class="material-icons tooltipped dropdown-trigger option-btn">expand_more</button>
						<!-- Dropdown Structure -->
						<ul id="dropdown_'.$details['identifier'].'" class="dropdown-content">			    
						<li class="share-with red-text"><a><i class="material-icons">share_alt</i>TalkPoint</a></li>
						</ul> 
						</div>';
					}
				}					
				if ($column == "feeds" || $column == 'user' || $column == '' || $column == 'videos') {
					$col_class = "";
				}else{
					$col_class = "invinc";
				}
				if (isset($_SESSION['user'])) {
					if ($p['user_following']) {
						$tooltip = "Following<br><div class='center'>".$details["author"].'</div>';
					}else{
						$tooltip = $details["author"];
					}
				}else{
					$tooltip = $details["author"];
				}						
				if ($post['type'] != "share_with") {
					return '
					<div class="post" data-post="'.$details["identifier"].'">
						<div class="post-head">
							<div class="left author-data">
								<a data-tooltip="'.$tooltip.'" data-position="right" href="'.$author_url.'" class="in-link tooltipped" data-type="user" data-item="'.$details["author_clean"].'" data-position="bottom" data-tooltip="View profile"  class="tooltipped user-link">
									<img tabindex="1" class="author-img" src="'.$details["author_img"].'" alt="author">
								</a>
								'.$following.'
								<div class="author-info" style="display: inline-block;">		
									<div>
										<div class="author-name">'.$details["author_name"].'</div>
										<div class="user-handle">'.$details["author"].'</div>
										<div class="post-date">
											'.$details["date"].'<span class="post-column '.$col_class.'">'.$details["column"].'</span>
										</div>
									</div>				
								</div>					
							</div>		
							'.$p_option.'
						</div>
						<div class="post-main">
							<a  href="'.$details["url"].'" class="in-link post-link" data-type="post" data-item="'.$details["identifier"].'">
								<h5 class="post-title">
									'.$details["title"].'
								</h5>
								'.$attached.'
								<div class="post-content">
									'.mb_substr($details["content"], 0,300).'
									<div class="elipsis">									
									</div>
								</div>
							</a>
						</div>
						<div class="post-tools">
							<div class="left">
								<button data-position="top" data-tooltip="Comments"  class="tooltipped post-tool post_comments">
									<img src="'.base_url("uploads/system/comments.png").'" alt="comments">
									<div class="counter">
										'.$p["comments"].'
									</div>
								</button>
								<button data-position="top" data-tooltip="Likes"  class="'.$like_class.' tooltipped post-tool">
									<img src="'.$like_src.'" alt="likes">
									<div class="counter right">
										'.$p["likes"].'
									</div>
								</button>
								<button data-position="top" data-tooltip="Views"  class="tooltipped post-tool views">
									<img src="'.base_url("uploads/system/views.png").'" alt="views">
									<div class="counter right">
										'.$p["views"].'
									</div>
								</button>										
							</div>									
							<div class="right">
								<button class="post-tool dropdown-trigger"data-activates="share_'.$details['identifier'].'">
									<img src="'.base_url("uploads/system/share.png").'" alt="share">	
								</button>	
								<ul id="share_'.$details['identifier'].'" class="dropdown-content">			    
									<li>
										<a class="talk-share" href="'.$p['article_fb'].'"><img src="'.base_url("uploads/system/facebook_.png").'" class="drop-image" alt="facebook">Facebook</a>
									</li>
									<li>
										<a class="talk-share" href="'.$p['article_twitter'].'"><img src="'.base_url("uploads/system/twitter_.png").'" class="drop-image" alt="twitter">Twitter</a>
									</li>
									<li>
										<a  class="talk-share" href="'.$p['article_linked'].'"><img src="'.base_url("uploads/system/linkedin_.png").'" class="drop-image" alt="LinkedIn">LinkedIn</a>
									</li>
									<li>
										<a href="'.$details['url'].'?p=talk" class="link-copy"><img src="'.base_url("uploads/system/copy_.png").'" class="drop-image" alt="copy">Copy Link</a>
									</li>
								</ul> 					
							</div>
						</div>
					</div>
					';
				}else{
					$list = json_decode($p['share_list']);
					if (isset($_SESSION['user'])) {
						if ($list[0] != "timeline" ) {								 
							if ($p['details']['author_clean'] != $_SESSION['user']->username && !in_array($_SESSION['user']->username, $list)) {
								return "";
							}
						}else{
							$followers = $CI->commonDatabase->get_data("tp_circles",false,false,'u_id_added',$p['shared']['details']['usr_id']);
							$f = [];									 
							if ($followers || $p['details']['author_clean'] == $_SESSION['user']->username) {
								foreach ($followers as $follower) {
									array_push($f, $follower['usr_id_adding']);
								}									 	
								if (!in_array($_SESSION['user']->id, $f)) {
									return "";
								}
							}else{
								return "";
							}
						}
					}else{
						return "";
					}																				
					$shared = $p['shared'];
					if ($p['details']['content'] != '<p><br></p>') {
						$s_u = $shared['details']['url'];
						if (strlen($p['details']['content']) > 300) {
							$s_cl = mb_substr($p['details']['content'], 0, 300);
						}else{
							$s_cl = $p['details']['content'];
						}
					}	else{
						$s_cl = "";
					}
					$attached = "";
					if ($shared['details']['attachment']) {
						$attachment = $shared['details']['attachment'];
						if ($attachment['type'] == 'image') {
							$attached = '<img src="'.$attachment['src'].'" class="reposted-image">';							
						}elseif($attachment['type'] == 'yt'){
							$attached = '<img src="'.$attachment['meta'].'"  class="reposted-image" data-yt="'.$attachment['src'].'">';
						}
					}
					return '
					<div class="post" data-post="'.$details["identifier"].'">
						<div class="post-head">
							<div class="left author-data">
								<a data-tooltip="'.$tooltip.'" data-position="right" href="'.$author_url.'" class="in-link tooltipped" data-type="user" data-item="'.$details["author_clean"].'" data-position="bottom" data-tooltip="View profile"  class="tooltipped user-link">
									<img tabindex="1" class="author-img" src="'.$details["author_img"].'" alt="author">
								</a>
								'.$following.'
								<div class="author-info" style="display: inline-block;">		
									<div>
										<div class="author-name">'.$details["author_name"].'</div>
										<div class="user-handle">'.$details["author"].'</div>
										<div class="post-date">
											'.$details["date"].'<span class="post-column invinc">'.$details["column"].'</span>
										</div>
									</div>				
								</div>					
							</div>		
							'.$p_option.'
						</div>
						<div class="post-main">
							<a  href="'.$details["url"].'" class="in-link post-link" data-type="post" data-item="'.$details["identifier"].'">	
								<div class="shared-text">
									<i class="material-icons shared-icon">reply_all</i> shared 
									<div class="shared-counter"> ~ '.$p['share_count'].'</div>
								</div>		
								<div class="post-content">
									'.$s_cl.'
									<div class="reposted" tabindex="1">
										<a href="'.$shared['details']['url'].'" class="reposted-link in-link" data-type="post" data-item="'.$shared['details']['identifier'].'">
											<div class="rep-data left">
												'.$attached.'
											</div>
											<div class="rep-text">
												<div class="rep-headline post-title">'.$p['shared']["details"]["title"].'.</div>
												<div class="rep-meta">'.$p['shared']["details"]["author"].'.  '.$p["shared"]["details"]["date"].'.</div>
											</div>
										</a>
									</div>										
								</div>
							</a>
						</div>
						<div class="post-tools">
							<div class="left">
								<button data-position="top" data-tooltip="Comments"  class="tooltipped post-tool post_comments">
									<img src="'.base_url("uploads/system/comments.png").'" alt="comments">
									<div class="counter">
										'.$p["comments"].'
									</div>
								</button>
								<button data-position="top" data-tooltip="Likes"  class="'.$like_class.' tooltipped post-tool">
									<img src="'.$like_src.'" alt="likes">
									<div class="counter right">
										'.$p["likes"].'
									</div>
								</button>
								<button data-position="top" data-tooltip="Views"  class="tooltipped post-tool views">
									<img src="'.base_url("uploads/system/views.png").'" alt="views">
									<div class="counter right">
										'.$p["views"].'
									</div>
								</button>										
							</div>									
							<div class="right">
								<button class="post-tool dropdown-trigger"data-activates="share_'.$details['identifier'].'">
									<img src="'.base_url("uploads/system/share.png").'" alt="share">	
								</button>	
								<ul id="share_'.$details['identifier'].'" class="dropdown-content">			    
									<li>
										<a class="talk-share" href="'.$p['article_fb'].'"><img src="'.base_url("uploads/system/facebook_.png").'" class="drop-image" alt="facebook">Facebook</a>
									</li>
									<li>
										<a class="talk-share" href="'.$p['article_twitter'].'"><img src="'.base_url("uploads/system/twitter_.png").'" class="drop-image" alt="twitter">Twitter</a>
									</li>
									<li>
										<a  class="talk-share" href="'.$p['article_linked'].'"><img src="'.base_url("uploads/system/linkedin_.png").'" class="drop-image" alt="LinkedIn">LinkedIn</a>
									</li>
									<li>
										<a href="'.$details['url'].'?p=talk" class="link-copy"><img src="'.base_url("uploads/system/copy_.png").'" class="drop-image" alt="copy">Copy Link</a>
									</li>
								</ul> 					
							</div>
						</div>
					</div>
					';
				}
			}
		}else{
			return false;
		}
	}
	static function getRating(){
		$CI = &get_instance();
		$ratings = $CI->commonDatabase->get_data("tp_ratings",false,false);
		$rated = 0;
		$count = 0;
		if ($ratings) {			
			$count = sizeof($ratings);
			foreach ($ratings as $rating) {
				$rated += (Int)$rating['rating'];
			}
		}
		$totals = 5 * $count;				
		if ($count > 0) {
			$r['rating'] = number_format(($rated/$count),1);
		}else{
			$r['rating'] = 5;
		}
		$r['count'] = common::format_number($count);

		return $r; 
	}
	static function renderSupport($message = false){
		if (isset($_SESSION['user'])) {
			if ($message['m_from'] == $_SESSION['user']->id) {
				return '<div class="support-mess support-out">						
				<div class="mess-text">
				'.$message['m_text'].'
				</div>	
				<div class="support-mess-head">
				<div class="mess-u-name">
				<div class="mess-u author-name">You <div class="mess-time right">'.(date('d-m-Y',$message['m_date'])).'</div></div>
				</div>
				<a>
				<img src="'.(base_url("uploads/profile/".$_SESSION['user']->profile)).'" alt="item" class="support-prof">
				</a>					
				</div>				
				</div>';
			}else{
				return '
				<div class="support-mess support-in">
				<div class="support-mess-head">
				<img src="'.base_url("uploads/system/about_white.png").'" alt="item" class="support-prof">
				<div class="mess-u-name">
				<div class="mess-u author-name">TalkPoint ~ <span class="s_date">'.(date('d-m-Y',$message['m_date'])).'</span></div>
				</div>
				</div>
				<div class="mess-text">
				'.$message['m_text'].'
				</div>
				</div>
				';
			}
		}
		return false;
	}	
	static function renderComment($comment = false){
		if ($comment) {
			if ($comment) {
				if ($comment['attachment']) {
					$sub_img = '<div class="sub-img-cover">
					<img src="'.$comment['attachment']['src'].'" alt="Image" class="sub-img">
					</div>';
				}else{
					$sub_img = "";
				}
				$data = $comment;
				if ($comment['isOwner']) {
					$comment_tools = '<div class="comment-tools">
					<button data-position="top" data-tooltip="Reply to comment"  class="tooltipped material-icons comment-tool reply-to">reply</button>
					<button data-position="top" data-tooltip="Delete comment"  class="tooltipped material-icons del-comment comment-tool">delete</button>
					<button data-position="left" data-tooltip="View replies"  class="tooltipped material-icons right comment-tool view-reply">expand_more</button>
					</div>';
				}else{
					if ($comment['isOnline']) {
						$comment_tools = '<div class="comment-tools">
						<button data-position="top" data-tooltip="Reply to comment"  class="tooltipped material-icons comment-tool reply-to">reply</button>													
						<button data-position="left" data-tooltip="View replies"  class="tooltipped material-icons right comment-tool view-reply">expand_more</button>
						</div>';
					}else{
						$comment_tools = '<div class="comment-tools">	
						<button data-position="left" data-tooltip="View replies"  class="tooltipped material-icons right comment-tool view-reply">expand_more</button>
						</div><br><br>';
					}
				}				
				$comment = '<div class="comment" data-comment="'.$data['id'].'">
											<div class="comment-data">
												<div class="left author-data">
													<a href="'.(base_url("writer/".$data['user']['username'])).'" class="in-link" data-type="user" data-item="'.$data['user']['username'].'">
														<img class="author-img" src="'.$data['user']['u_profile'].'" alt="author">
													</a>
													<div class="right author-info">
														<div class="author-name">@'.$data['user']['username'].'</div>
														<div class="post-date">
															'.$data['date'].'
														</div>
													</div>
												</div>
												'.$comment_tools.'
											</div>
												'.$sub_img.'
											<div class="comment-text">
												'.$data['content'].'
											</div>
											<div class="comment-replies" data-expended="false">
												
											</div>
										</div>';
				return $comment;

			}
		}
	}
	static function propCountries(){
		 $CI = &get_instance();
		 $countries  = $CI->commonDatabase->get_cond("tp_users","usr_id > 0");
		 if ($countries) {
		   $c_list = [];
		   foreach ($countries as $country) {
		     if (!in_array($country['country'], $c_list)) {
		       array_push($c_list, $country['country']);
		     }
		   }
		   if (sizeof($c_list) > 0) {
		     foreach ($c_list as $country) {
		       $ch_country = $CI->commonDatabase->get_data("tp_countries",1,false,'name',$country);
		       if (!$ch_country && in_array($country, $CI->config->item("countries"))) {
		         $data = [
		          'name' => $country,
		          'date_added' => time()
		         ];
		         $CI->commonDatabase->add("tp_countries",$data);
		       }
		     }
		   }
		 }
  }
  static function countCountries($country  = false){
  	if ($country) {
  		$CI = &get_instance();
  		$r['articles'] = $CI->commonDatabase->count("tp_post",'country',$country);
  		$r['users'] = $CI->commonDatabase->count("tp_users",'country',$country);

  		return $r;
  	}
  	return false;
  }
  static function renderCountry($country = false){
  	if ($country) {
  		$CI = &get_instance();
  		$count = common::countCountries($country['name']);
  		$articles = common::format_number($count['articles']);
  		$users = common::format_number($count['users']);

  		if ($country['selected']) {
  			$value = 'value="true"';  
  			$class = "";			
  		}else{
  			$value = '';
  			$class = "hidden";
  		}
  		$c = '
  				<div class="country-item" data-country="'.$country['name'].'">
						<div class="left country-name">
							'.$country['name'].'
							<div class="country-stats">'.$articles.' Articles . '.$users.' Writers</div>
						</div>
						<div '.$value.' data-country="'.$country['id'].'" class="right country-indic">
							<i class="material-icons '.$class.' country-indic-in">done</i>
						</div>
					</div>
  		';

  		return $c;
  	}
  	return "";
  }
  static function user_ors(){
  	if (isset($_SESSION['user'])) {
  		 $CI = &get_instance();
  		 $user = $_SESSION['user']->id;
  		 $user_countries = $CI->commonDatabase->get_cond("tp_user_countries","usr_id='$user' AND state='1'");
  		 $u_list = "";
  		 if ($user_countries) {
  		 	foreach ($user_countries as $country) {
  		 	$c = $CI->commonDatabase->get_data("tp_countries",1,false,'id',$country['country']);
  		 	 if ($c) {
  		 	 	if ($country == $user_countries[sizeof($user_countries) - 1]) {
	  		 	 	$u_list .= " country='".$c[0]["name"]."')";
	  		 	 }else{
	  		 	 	if ($country == $user_countries[0]) {
	  		 	 		$u_list .= " AND (country='".$c[0]["name"]."' or ";
	  		 	 	}else{
	  		 	 		$u_list .= " country='".$c[0]["name"]."' or ";
	  		 	 	}
	  		 	 }
  		 	 }
  		 }
  		 }   		    		 		
  		 return $u_list;
  	}else{
  		return "";
  	}
  }
  static function sendCode($email = false,$code = false){
  	$CI  = &get_instance();
  	if ($email && $code) {
   	  	$d = [
   	  		'email' => $email,
   	  		'code' => $code
   	  	];  								
			$message = $CI->load->view("pass_email",$d,true);
			$return_t = "Recover Password Here";
			$return   = "recover?u=".$email;
			$head     = "Talkpoint Account Password Recovery";			
			/*=========================================================*/		  
		  common::sendMail($head,$message,$email,$return,$return_t,true);
		  common::log('recovery', $email,'req_request');
		  return true;		  		
  	}else{
  		return false;
  	}
  }
  static function sendMail($head = false,$message = false ,$to = false,$return = null,$return_t = null,$state){
  	$CI = &get_instance();
  	if ($return != null) {
			$foot = "<hr><a style='float:right;' href='".base_url().$return."'>".$return_t."</a>";
		}
		else{
			$foot = '';
		}
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'smtp.mail.yahoo.com';
		$config['smtp_port'] = 465;
		$config['smtp_user'] = 'kelvin_agengo@yahoo.com';
		$config['smtp_pass'] ='k3e4a2mydaudi';
		$CI->load->library('email', $config);
		/*=====================================================*/
		$CI->email->set_newline("\r\n");  
		$CI->email->set_mailtype("html"); 
		/*===================================================*/                      
		$CI->email->from('support@talkpoint.online', 'TalkPoint');
		$CI->email->to($to);
		$CI->email->subject($head);      
		$CI->email->message($message.$foot); 
		$CI->email->send();
		return true;
  }
  static function log($type = "post",$item = "",$user  = false){ 
  	$CI = &get_instance(); 
		if ($type == 'recovery') {
			$user =   $user;
		}
		else{
			if (isset($_SESSION['user'])) {
				$user = $_SESSION['user']->id;
			}
			else{
				$user = "visitor";
			}
		}	
		$u = common::getIp();	
		if (isset($_SESSION['user'])) {
			$c = $CI->commonDatabase->get_data("logs",false,false,'user',$u,'item',$item);
		}else{
			$c =$CI->commonDatabase->get_data("logs",false,false,'ip',$u,'item',$item);
		}
		if (!($c && $type == 'post')) {
			$data['ip'] = common::getIp();
			$data['time'] = time();
			$data['user'] = $user;
			$data['id']   = '';
			$data['item'] = $item;
			$data['type'] = $type;
			$CI->commonDatabase->add('logs',$data);
		}		
	}
	static function viewUser($user = false){
		$CI = &get_instance();
		if ($user) {
			if (isset($_SESSION['user'])) {
				$u = $_SESSION['user']->id;				
			}else{
				$u = "visitor";
			}
			$data = [
				'user' => $user,
				'viewer' => $u,
				'date' => time(),
				'ip_address' => common::getIp()
			];
			$CI->commonDatabase->add("tp_profile_visits",$data);
		}
	}
	static function logPlatform($identifier = false, $platform = false){
		$CI = &get_instance();
		if ($identifier && $platform) {			
			$data = [
				'identifier' => $identifier,				
				'date' => time(),
				'platform' => $platform,
				'ip_address' => common::getIp()
			];
			$CI->commonDatabase->add("tp_share_stats",$data);
		}
	}
	static function getIp() {
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
	static function userTime(){
		if (isset($_SESSION['user'])) {
			$CI = &get_instance();
			$data = [
				'state_time' => time()
			];
			$user = $_SESSION['user']->id;
			$CI->commonDatabase->update("tp_users",$data,'usr_id',$user);
			common::uCountries();
			return true;
		}else{
			return false;
		}
	}
	static function sendNotif($owner = false, $sender = false, $message = false, $type = false , $identifier = 0){
		$CI = &get_instance();
		$data = [
			'owner' => $owner,
			'sender' => $sender,
			'notification' => $message,
			'state' => 0,
			'type' => $type,
			'post_identifier' => $identifier,
			'time' => time()
		];
		$CI->commonDatabase->add("tp_notifications",$data);
	}
	static function uCountries(){
		if (isset($_SESSION['user'])) {
			 $CI = &get_instance();
			 $user = $_SESSION['user']->id;
			 $countries  = $CI->commonDatabase->get_cond("tp_countries","id order by name ASC");
			 $user_countries =  $CI->commonDatabase->get_data("tp_user_countries","id AND usr_id='$user'");
			 $sel = [];
			 if ($user_countries) {
 			 	foreach ($user_countries as $country) {
				 	 array_push($sel, $country['country']);
				 }
 			 }			 
			 if ($countries) {
             foreach ($countries as $country) {          
                if (!in_array($country['id'],$sel)) {
                  $data = [
                    'country' => $country['id'],
                    'usr_id' => $_SESSION['user']->id,
                    'state' => 1
                  ];
                $CI->commonDatabase->add("tp_user_countries",$data); 
                }
             }
          }
		}
	}
	static function isEditor(){
		if (isset($_SESSION['user'])) {
			$CI = &get_instance();
			if (in_array($_SESSION['user']->email, $CI->config->item("editors"))) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function getNotifs($article = false){
		$CI = &get_instance();
		$notifs = $CI->commonDatabase->get_data("tp_notifications",false,false,'type','notif','sender','70','post_identifier',$article);
		if ($notifs) {
			$r = '';
			foreach ($notifs as $notif) {
				$r .= '
						<div class="comms-item">
							'.$notif["notification"].'
							<div class="comms-date">'.(date("d-m-Y",$notif["time"])).'</div>
						</div>
				';
			}
			return $r;
		}else{
			return '<div class="flow-text center">No notifications sent</div>';
		}
	}
	static function addNotify($post = false){
		if ($post) {
			$CI = &get_instance();
			$post =$CI->commonDatabase->get_data("tp_post",1,false,"identifier",$post);
			if ($post) {
				$user = $post[0]['usr_id'];
				$followers = $CI->commonDatabase->get_data("tp_circles",false,false,"u_id_added", $user);
				if ($followers) {
					foreach ($followers as $follower) {
						if ($follower['usr_id_adding'] != $user) {
							common::sendNotif($follower['usr_id_adding'], $user, "Published an article", "post",$post[0]['identifier']);
						}
					}
				}
			}
		}
	}
	static function isSupport(){
		if (isset($_SESSION['user'])) {
			$CI = &get_instance();
			if (in_array($_SESSION['user']->email, $CI->config->item("support"))) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function renderSupport_($support = false){
		if (isset($_SESSION['user'])) {
			$CI = &get_instance();
			$message = $support;
			if ($message['m_from'] == 70) {
				return '<div class="support-mess support-out">						
				<div class="mess-text">
				'.$message['m_text'].'
				</div>	
				<div class="support-mess-head">
				<div class="mess-u-name">
				<div class="mess-u author-name">TalkPoint Support<div class="mess-time right">'.(date('d-m-Y',$message['m_date'])).'</div></div>
				</div>
				<a>
				<img src="'.base_url("uploads/system/about_white.png").'" alt="item" class="support-prof">				
				</a>					
				</div>				
				</div>';
			}else{
				$user = $CI->commonDatabase->get_data("tp_users",1,false,'usr_id',$message['m_from']);
					if ($user) {
						return '
						<div class="support-mess support-in">
						<div class="support-mess-head">
						<img src="'.(base_url("uploads/profile/".$_SESSION['user']->profile)).'" alt="item" class="support-prof">
						<div class="mess-u-name">
						<div class="mess-u author-name">@'.$user[0]['u_name'].'<div class="mess-time right">'.(date('d-m-Y',$message['m_date'])).'</div></div>
						</div>
						</div>
						<div class="mess-text">
						'.$message['m_text'].'
						</div>
						</div>
						';
					}
			}
		}
	}
	static function returnSupport($support = false){
		if ($support) {
			$CI = &get_instance();
			 $user = $CI->commonDatabase->get_data("tp_users",1,false,'usr_id',$support['m_from']);
			 if ($user) {
			 	 $prof = base_url("uploads/profile/".$user[0]['u_photo_name']);
			 	 $date = date('d-m-Y',$support['m_date']);
			 	 $handle = "@".$user[0]['u_name'];
			 	 $id = $user[0]['usr_id'];

			 	 $count = $CI->commonDatabase->get_cond("tp_support","m_from='$id' AND m_state= '0'");
			 	 if ($count) {
			 	 	 $count = common::format_number(sizeof($count));
			 	 }else{
			 	 	$count = 1;
			 	 }

			 	 if (time() < ($user[0]['state_time'] + (60 * 2))) {
			 	 	 $state = "online";
			 	 }else{
			 	   $state = "offline";
			 	 }

			 	 return '
			 	 	<div class="support-user" data-item="'.$user[0]['usr_id'].'">
								<a href="'.base_url("writer/".$user[0]['u_name']).'" target="_blank">
									<img src="'.$prof.'" alt="" class="author-img support-img">
								</a>
								<div class="u-support">
									<div class="support-handle">'.$handle.'</div>
									<div class="support-date">'.$date.' 
										 <div class="support-count">'.$count.'</div>
									</div>
									<div class="support-status '.$state.'">'.$state.'</div>
								</div>
						</div>
			 	 ';
			 }
		}
		return "";
	}
}
?>