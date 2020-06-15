<?php 
	if (isset($article)) {
		$article = $data->article;
	}	
 ?>
 <?php //var_dump($article); ?>
<div class="talk-post" data-item="<?php echo isset($article->details->identifier) ? $article->details->identifier :""; ?>">
	<div class="talk-post-main">
		<div class="main-post-head">
			<div class="head-title">
				<a href="<?php echo base_url(); ?>">
					<img src="<?php echo base_url("logo.png"); ?>" alt="TalkPoint Logo" class="talk-logo">
				</a>
			</div>			
			<div class="right head-tools">
				<button data-position="left" data-tooltip="<?php echo isset($article) ? "Back home":"Exit post"; ?>"  class="tooltipped material-icons head-tool close-main-post"><?php echo isset($article) ? "home":"close"; ?></button>					
			</div>
		</div>		
		<div class="main-post-body">			
			<div class="row post-in-r">
				<div class="col m12 s12 l2"></div>
				<div class="col s12 m12 l8 post-in">
					<div class="post-head">
						<div class="left author-data">							
							<a href="<?php echo isset($article->details->author_clean) ? base_url("writer/".$article->details->author_clean) :""; ?>" class="main-in" data-type="user" data-item="<?php echo isset($article->details->author_clean) ? $article->details->author_clean :"talkpoint"; ?>" data-position="bottom" data-tooltip="View profile"  class="tooltipped user-link">
								<img tabindex="1" class="author-img main-author-img" src="<?php echo isset($article->details->author_img) ? $article->details->author_img : base_url("uploads/system/default_prof_white.png"); ?>" alt="author">
							</a>
							<div class="author-info">
								<?php 
									if (isset($article) && $article->user_following) {
										echo '<button data-position="left" data-tooltip="Unfollow writer"  class="tooltipped follow-post follow-main material-icons">done</button>';
									}else{
										echo '<button data-position="left" data-tooltip="Follow writer"  class="tooltipped follow-post follow-main material-icons">person_add</button>';
									}
								 ?>
								<div style="margin-right:45px;">
									<div class="author-name main-author-name">
										<?php echo isset($article->details->author_name) ? $article->details->author_name :"TalkPoint"; ?>
									</div>
									<div class="user-handle main-handle"><?php echo isset($article->details->author) ? $article->details->author :"@talkpoint"; ?>
									</div>		
									<div class="post-date main-date">
										<?php echo isset($article->details->date) ? $article->details->date : date("d-m-Y"); ?> 
										<span class="post-column">
											<?php echo isset($article->details->column) ? $article->details->column : "Politics & Leadership"; ?> 
										</span>
									</div>	
								</div>			
							</div>					
						</div>						
						<span class="trender-indic main-trending"></span>
						<div class="right post-options main-post-options">
							<?php 
								if (isset($_SESSION['user']) && isset($article)) {
									echo '
										<button data-position="topc" data-tooltip="Options" data-activates="dropdown_post" class="tooltipped material-icons dropdown-trigger option-btn">expand_more</button>
									';
									if ($article->owner) {
										echo '
											<ul id="dropdown_post" class="dropdown-content">			    
												<li class="share-with"><a><i class="material-icons">share_alt</i>TalkPoint</a></li>
												<li class="del-post"><a><i class="material-icons">delete</i>Delete</a></li>
											</ul>
										';
									}else{
										echo '
											<ul id="dropdown_post" class="dropdown-content">			    
												<li class="share-with"><a><i class="material-icons">share_alt</i>TalkPoint</a></li>
											</ul>
										';
									}
								}
							 ?>
						</div>						
					</div>
					<div class="post-main">
						<h5 class="post-title main-title"><?php echo isset($article->details->title) ? $article->details->title : "Article"; ?></h5>
						<?php 
							if (isset($article->details->attachment) && $article->details->attachment) {
								$attachment = $article->details->attachment;
								if ($attachment->type == "image") {
									echo '<img src="'.$attachment->src.'"  alt="post" class="post-image open-post">';
								}elseif($attachment->type == "yt"){
									echo '<img src="'.$attachment->src.'" alt="post" class="post-image yt-image" data-video="'.$attachment->meta.'">';
								}
							}else{
								echo '<img  alt="post" class="post-image open-post em">';
							}
						 ?>
						<div class="post-content main-content">
							<?php echo isset($article->details->content) ? $article->details->content : "Article content"; ?>
						</div>
					</div>
					<div class="post-tools">											
						<div class="center">							
							<a href="<?php echo isset($article->article_fb) ? $article->article_fb:""; ?>" data-position="top" data-tooltip="Share to Facebook"  class="tooltipped talk-share post-tool share-main main-fb">
								<img src="<?php echo base_url("uploads/system/facebook_.png") ?>" alt="facebook">							
							</a>	
							<a href="<?php echo isset($article->article_twitter) ? $article->article_twitter:""; ?>" data-position="top" data-tooltip="Share to Twitter"  class="tooltipped post-tool talk-share share-main main-twitter">
								<img src="<?php echo base_url("uploads/system/twitter_.png") ?>" alt="twitter">							
							</a>
							<a href="<?php echo isset($article->article_linked) ? $article->article_linked:""; ?>" data-position="top" data-tooltip="Share to LinkedIn"  class="tooltipped post-tool talk-share share-main main-linked">
								<img src="<?php echo base_url("uploads/system/linkedin_.png") ?>" alt="linkedin">	
							</a>									
							<a href="<?php echo isset($article->details->url) ? $article->details->url:""; ?>" data-position="top" data-tooltip="Copy link"  class="tooltipped post-tool link-copy share-main main-copy">
								<img src="<?php echo base_url("uploads/system/copy_.png") ?>" alt="clipboard">							
							</a>				
						</div>
					</div>
					<div class="comments">
						<div class="comment-add">
							<textarea placeholder="Leave a comment" name="comment-area" id="comment-area"  class="comment-area main-comment-area browser-default"></textarea>				
							<button data-position="top" data-tooltip="Submit comment"  class="tooltipped right send-comment material-icons">send</button><br>
							<div class="com-img">
								<label for="sel-comment">
									<span data-position="right" data-tooltip="Attach image to comment" class="material-icons tooltipped comm-img-sel">camera_alt</span>			
								</label>
								<input onchange="reader(this,'.comm-selected')" type="file" id="sel-comment" name="sel-comment" class="hidden sel-comment">
								<div class="right">
									<div class="comm-img-name"></div>
									<button data-position="right" data-tooltip="Remove image" class="material-icons right cancel-comm-img tooltipped">close</button>
								</div><br class="reply-br">
								<img src=""  alt="Selected image" class="comm-selected">
							</div>
						</div>						
						<div class="main-comments main-comments-body">			
							<?php 
								if (isset($article)) {
									$comments =common::_loadComments($article->details->identifier);
									if ($comments) {
										foreach ($comments as $comment) {
											echo common::renderComment($comment);
										}
									}else{
										echo '<div class="no-comments center">Be the first to comment.</div>';
									}
								}
							?>		
						</div>					
					</div>
				</div>
				<div class="col m12 s12 l2"></div>
			</div>
		</div>
		<div class="main-post-footer center">
			<button data-position="top" data-tooltip="Comments"  class="tooltipped post-tool main-comments">
				<img src="<?php echo base_url("uploads/system/comments.png") ?>" alt="comments">
				<div class="counter">
					<?php echo isset($article->comments) ? $article->comments:""; ?>
				</div>
			</button>
			<button data-position="top" data-tooltip="Like post"  class="tooltipped like-open post-tool liker">
				<?php
					if (isset($article->user_liked) && $article->user_liked) {
					 		echo '<img src="'.base_url("uploads/system/liked.png").'" alt="likes">';
					}else{
						echo '<img src="'.base_url("uploads/system/like.png").'" alt="likes">';
					} 
				 ?>
				<div class="counter">
				 <?php echo isset($article->likes) ? $article->likes:""; ?>
				</div>
			</button>
			<button data-position="top" data-tooltip="Views"  class="tooltipped post-tool main-views">
				<img src="<?php echo base_url("uploads/system/views.png") ?>" alt="views">
				<div class="counter right">
					<?php echo isset($article->views) ? $article->views:""; ?>
				</div>
			</button>
		</div>
	</div>	
</div>