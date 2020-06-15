<?php $this->load->view("components/media_player"); ?>
<style>
	body{
		background:rgba(0,0,0,.6) !important;
	}
</style>
<div class="editors-portal row">
	<div class="col s12 m12 l1"></div>
	<div class="col s12 m12 l10 editors-cover">
		<button class="edit-hinge material-icons clickable">list</button>
		<div class="editor-head">
			<div class="editor-title">
				<img src="<?php echo base_url("logo.png"); ?>" alt="talkpoint" class="editor-logo">
				<span class="left">Editor's Portal ~ @<?php echo $_SESSION['user']->username; ?></span> 
				<div class="editor-loader">
					<div class="loader-1 center_"><span></span></div>
				</div>
			</div>
			<div class="editor-tools right">
				<button data-tooltip="Exit portal"  class="tooltipped material-icons editor-tool close-editor">home</button>
			</div>
		</div>
		<div class="editor-body row">
			<div class="col s12 m12 l3 edit-posts">
				<?php 
					$posts = $this->commonDatabase->get_cond("tp_post","state='0' order by id DESC");
					if ($posts) {					
						 foreach ($posts as $post) {
						 	 $user = $this->commonDatabase->get_data("tp_users",1,false,'usr_id',$post['usr_id']);
						 	 if ($user) {
						 	 	 echo '<div class="hot-item editor-item"  data-item="'.$post["identifier"].'">
												<a href="" class="in-link" data-type="editor" data-item="'.$post["identifier"].'">		
													<div>
														<span class="hot-item-title post-title">'.(mb_substr($post["title"], 0,25)).'</span>
														<div class="hot-item-data">@'.$user[0]["u_name"].' </div>
														<div class="hot-item-data">'.(date('d-m-Y',$post['added_at'])).' . '.$post["post_column"].'</div>
													</div>
												</a>
											</div>';
						 	 }
						 }
					}else{
						echo '<div class="flow-text center">No pending articles</div>';
					}
				 ?>												
			</div>
			<style type="text/css">
				.editor-body > .col {
					animation:zoomer .25s ease-out;
				}
				@media only screen and (max-width: 800px){
					.editor-m{
						display: none;
					}
				}
			</style>
			<div class="col s12 m12 l9 editor-m">				
				<div class="editor-comms col s12 m8 l4">
					<div class="comms-body">						
						<div class="flow-text center grey-text">Select article</div>
					</div>
					<div class="comms-tools">
						<input type="text" class="comms-box browser-default" placeholder="Type message then press enter">
					</div>
				</div>
				<div class="editor-contents">
					<div class="info-eye">No article selected</div>
					<div class="edit-pane">
						<div class="edit-group">
							<label class="edit-label">Article title</label>
							<input type="text" class="edit-in post-title browser-default" placeholder="Post title">
						</div>
						<div class="edit-group">
							<label class="edit-label">Article media</label><br>
							<img src="" alt="post" class="edit-image">
						</div>
						<div class="edit-group">
							<label class="edit-label">Article content</label>
							<textarea  id="edit-textarea" class="edit-textarea"></textarea>
							<script type="text/javascript">
								CKEDITOR.replace("edit-textarea");
							</script>
						</div>
						<div class="editor-ex">							
							<a target="_blank" class="right go-prof" href="">
								User Profile<i class="material-icons">launch</i>
							</a>
						</div>
					</div>
				</div>
				<div class="editing-tools">
					<button data-tooltip="Delete article" class="editing-tool tooltipped left red-text del-edit material-icons">delete</button>
					<button data-tooltip="Send notification"  class="tooltipped editing-tool send-notif-edit material-icons">comment</button>
					<button data-tooltip="Save edits"  class="tooltipped editing-tool material-icons save-edit">save</button>			
					<button data-tooltip="Publish article"  class="tooltipped editing-tool material-icons publish-edit">done</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col s12 m12 l1"></div>
</div>
<script src="<?php echo base_url("bootstrap/js/editors_portal.js").common::getTime(); ?>"></script>