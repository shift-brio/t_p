<div class="row posts-body">
	<div class="col s12 m12 l3 hide-on-med-and-down left-col">
		<div class="follow-suggests trending-left left-div">
			<div class="side-tag">Trending now</div>
			<div class="side-inner">
				<?php 
							$ors = common::user_ors();
						 if (!$this->agent->mobile()) {
				 	    $type = "trends";				 	    
						  $trends = common::returnHot($type, true);
						  if ($trends) {
						  	$t_count = 0;
	  			    	foreach ($trends as $trend) {
	  			    		if (isset($trend->id)) {
	  			    			$identifier =  $trend->id;
	  			    			$position = $trend->position;			    		
		  			    		
		  			    		$post = common::getArticle($identifier);
		  			    		if ($post) {
		  			    			if (strlen($post['details']["title"]) > 40) {
		  			    				$title = mb_substr($post['details']["title"], 0,40); 
		  			    			}else{
		  			    				$title = $post['details']["title"];
		  			    			}
		  			    			$t_count += 1;
		  			    			echo '<div class="hot-item">
		  			    					<a href="'.$post['details']["url"].'" class="in-link" data-type="post" data-item="'.$post['details']["identifier"].'">
														<div class="left position">#'.$t_count.'</div>
														<div>
															<div class="hot-item-title post-title">'.$title.'</div>
															<div class="hot-item-data">'.$post['details']["author"].' . '.$post['details']["column"].'</div>
															<div class="hot-item-data">'.$post['details']["date"].' . '.$post["views"].' Views</div>
														</div>
														<div class="right launch-trend">
															<i class="material-icons">launch</i>
														</div>
													</a></div>
		  			    			';
		  			    		}
	  			    		}
	  			    	}
	  			    }	
				 	  }	    
				 ?>
			</div>				
		</div>
		<div class="follow-suggests left-div suggest">
			<div class="side-tag red-text">Most Popular</div>
			<div class="side-inner">	
				<?php 
					  if (!$this->agent->mobile()) {
	  		    	$type = "popular";
						  $trends = common::returnHot($type, true);
						  if ($trends) {
						  	$t_count = 0;
	  			    	foreach ($trends as $trend) {
	  			    		if (isset($trend->id) || isset($trend['id'])) {
	  			    			$identifier =  isset($trend->id) ? $trend->id : false;
	  			    			if (!$identifier) {
						    			$identifier =  isset($trend['id']) ? $trend['id'] : false;
						    		} 			    					    		
		  			    		
		  			    		$post = common::getArticle($identifier);
		  			    		if ($post) {
		  			    			$t_count += 1;
		  			    			if (strlen($post['details']["title"]) > 40) {
		  			    				$title = mb_substr($post['details']["title"], 0,40); 
		  			    			}else{
		  			    				$title = $post['details']["title"];
		  			    			}
		  			    			echo '<div class="hot-item">
		  			    					<a href="'.$post['details']["url"].'" class="in-link" data-type="post" data-item="'.$post['details']["identifier"].'">
														<div class="left position">#'.$t_count.'</div>
														<div>
															<div class="hot-item-title post-title">'.$title.'</div>
															<div class="hot-item-data">'.$post['details']["author"].' . '.$post['details']["column"].'</div>
															<div class="hot-item-data">'.$post['details']["date"].' . '.$post["views"].' Views</div>
														</div>
														<div class="right launch-trend">
															<i class="material-icons">launch</i>
														</div>
													</a></div>
		  			    			';
		  			    		}
	  			    		}
	  			    	}
	  			    }
	  		    }		    
				 ?>						
			</div>
		</div>
	</div>
	<div class="col s12 m12 l6 posts-area pretty-scroll">
		<?php
			$column = isset($_SESSION['column']) ? $_SESSION['column'] :"politics";			
			$time = time();
			if ($column == "feeds") {
				if (isset($_SESSION['user'])) {
					$posts  = common::getFeeds($_SESSION['user']->id);
				}else{
					$column = 'politics';
					$_SESSION['column'] = 'politics';
					$posts  = $this->commonDatabase->get_cond("tp_post","post_column='$column' AND added_at <= '$time' AND state='1' order by added_at DESC limit 10");
				}			
			}else{
				if ($column == "videos") {
						$posts  = $this->commonDatabase->get_cond("tp_post","added_at <= '$time' AND attached > 0 AND state='1' ".$ors." order by added_at DESC limit 10");							
						$ps = false;					
						if ($posts) {
							$ps = [];
							foreach ($posts as $post) {
								$attach = $this->commonDatabase->get_data("tp_attachments",1,false,"id",$post['attached']);
								if ($attach && $attach[0]['type'] == "yt") {
									array_push($ps, $post);
								}
							}
						}
						$posts = $ps;
				}else{
					$posts  = $this->commonDatabase->get_cond("tp_post","post_column='$column' AND added_at <= '$time' AND state='1' ".$ors." order by added_at DESC limit 10");
				}
			}
			if (!$posts) {
				$posts = [];
				if ($column == 'feeds') {
					echo '<div class="all-posts center flow-text">
						Welcome to TalkPoint @'.$_SESSION["user"]->username;
						$this->load->view("components/new_user");
						unset($_SESSION['new_user']);
					echo'</div>';
				}else{
					echo '<div class="all-posts center flow-text">All articles</div>';					
				}
			}
			foreach ($posts as $post) {
				echo common::renderArticle($post,'col',$column);
			}
		 ?>
	</div>
	<div class="col s12 m12 l3 page-right hide-on-med-and-down">
		<?php $this->load->view("components/talk_about"); ?>
	</div>
</div>