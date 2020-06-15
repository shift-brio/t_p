<div class="popups">
	<div class="row pop-row">
		<div class="col s12 m12 l4"></div>				
		<div class="col s12 m12 l4 popup-area">
			<div class="popup-head">
				<div class="popup-tag left">
					<div class="tag-title"></div>
					<input placeholder="Search article" type="text" class="searcher browser-default">
				</div>
				<div class="right popup-tools">
					<button class="pop-close material-icons">
						close
					</button>
				</div>
			</div>
			<div class="search-body">
				<div class="search-loader">
					<div class="search-loader-in"></div>
				</div>
				<div class="search-head">
					<div class="search-tabs">
						<button class="search-tab active" data-name="article" data-type="articles">
							<i class="material-icons">library_books</i>
							Article
						</button>
						<button class="left search-tab" data-name="writer" data-type="users">
							<i class="material-icons">person</i>
							Writer
						</button>
					</div>								
				</div>				
				<div class="search-result">
					<div class="search-data">
						
					</div>
					<div class="search-inf center">
						Type to search
					</div>
				</div>
			</div>			
			<div class="support-body">
				<div class="support-data">
					<div class="support-mess support-in">
						<div class="support-mess-head">
							<img src="<?php echo base_url("uploads/system/about_white.png") ?>" alt="item" class="support-prof">
							<div class="mess-u-name">
								<div class="mess-u author-name">TalkPoint <div class="mess-time right"><?php echo date("d-m-Y"); ?></div></div>
							</div>
						</div>
						<div class="mess-text">
							Welcome to TalkPoint
						</div>
					</div>					
				</div>
				<div class="support-tools">
					<input placeholder="Ask about anything..." type="text" class="browser-default support-text">
					<button class="support-btn material-icons">send</button>
				</div>
				<div class="support-loader">
					<div class="support-loader-in"></div>
				</div>
			</div>
			<div class="popup-body notif-body pretty-scroll">
				<?php 
					if (isset($_SESSION['user'])) {
						$user = $_SESSION['user']->id;						
						$notifs = $this->commonDatabase->get_cond("tp_notifications","owner='$user' order by n_id DESC");
						if ($notifs) {
							foreach ($notifs as $notif) {
								if ($notif['state'] == '1') {
									$n_cl = "";
								}else{
									$n_cl = "active";
								}
								if ($notif['type'] == "") {
									 $notif['type'] = "post";
								}
								if ($notif['type'] == "post" || $notif['type']=='share') {
									if ($notif['type'] == "post") {
										$text = "Published an article.";
									}else{
										$text = "Shared an article with you.";
									}
									$post = $this->commonDatabase->get_data("tp_post",1,false,'identifier',$notif['post_identifier']);
									$user = $this->commonDatabase->get_data("tp_users",1,false,'usr_id',$notif['sender']);
									$u_url = base_url("writer/".$user[0]['u_name']);
									$u_prof = base_url("uploads/profile/".$user[0]['u_photo_name']);
									if ($post && $user) {
										$url = base_url("article/".$post[0]['link']);
										echo '<div class="g-item popup-item linked notif-item" data-notif="'.$notif['n_id'].'" data-type="post" data-item="'.$notif['post_identifier'].'" tabindex="1">
													<a class="in-link" data-type="post" data-item="'.$notif['post_identifier'].'"
													<div class="item-head left">
														<a href="'.$u_url.'" data-type="user" data-item="'.$user[0]['u_name'].'" class="in-link">
															<img src="'.$u_prof.'" alt="item" class="left item-img">
														</a>
														<div class="item-desc">
															<div class="item-title">
																'.(ucfirst($user[0]['f_name']." ".$user[0]['l_name'])).'
															</div>
															<div class="item-description">
																'.$text.'
																<div class="item-date">'.(timespan($notif['time'],time(),1)).'</div>
															</div>							
														</div>
													</div>
													<div class="right item-tools">
														<button class="item-open material-icons">
															launch
														</button>
													</div>';
									}
								}elseif($notif['type'] == "follow"){
									$u = $this->commonDatabase->get_data("tp_users",1,false,'usr_id',$notif['sender']);
									if ($u) {
										  $user = common::_getWriter($u[0]['u_name']);
										  $user['url'] = base_url("writer/".$user['username']);
											echo '
												<div class="g-item popup-item notif-item" tabindex="1">
												  <a href="'.$user['url'].'" data-type="user" class="in-link" data-item="'.$user['username'].'">
														<div class="item-head left">													
																<img src="'.$user['u_profile'].'" alt="item" class="left item-img">												
															<div class="item-desc linke">
																<div class="item-title">
																	'.$user['name'].' <small>~ '.(timespan($notif['time'],time(),1)).'</small>
																</div>
																<div class="item-description">	
																	Started following you							
																	<div class="item-date">'.$user['followers'].' Followers . '.$user['posts'].' Articles</div>
																</div>							
															</div>
														</div>														
													</a>
												</div>
											';
									}									
								}elseif($notif['type'] == "notif"){
									$u = $this->commonDatabase->get_data("tp_users",1,false,'usr_id',$notif['sender']);																	
									if ($u) {										
										  $user = common::_getWriter($u[0]['u_name']);
										  $user['url'] = base_url("writer/".$user['username']);
											$v =  '
												<div class="g-item popup-item notif-item" tabindex="1">
													<div class="item-head left">
														<a>
															<img src="'.$user['u_profile'].'" alt="item" class="left item-img">
														</a>
														<div class="item-desc linke">
															<div class="item-title">
																'.$user['name'].'
															</div>
															<div class="item-description">
																'.(mb_substr($notif['notification'], 0,40)).'
																<div class="item-date">'.(timespan($notif['time'],time(),1)).'</div>
															</div>							
														</div>
													</div>
													<div class="right item-tools">
														<button class="item-open material-icons">
															expand_more
														</button>
													</div>
													<div class="item-body pretty-scroll">
														'.$notif['notification'].'
													</div>
												</div>
											';
											echo $v;
									}
							  }else{

							  }
							}
						}else{
							echo '<div class="flow-text white-text center">
											No notifications yet
								  </div>';
						}
					}
				 ?>															
			</div>					
			</div>			
		</div>
		<div class="col s12 m12 l4"></div>	
	</div>
</div>