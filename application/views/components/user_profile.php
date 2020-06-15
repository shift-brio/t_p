<?php $data = json_decode(json_encode($data)); ?>
<div class="user-profile" style="<?php echo isset($data->data->banner) && $data->data->banner != "" ? "background-image:url(".base_url("uploads/banner/".$data->data->banner)."),url(".base_url("uploads/system/banner.png").");" : "";?>;">								
	<div class="profilers">
		<div class="prof-e">
			<?php 
				$src = "";							
				if( isset($data->data->u_photo_name) && $data->data->u_photo_name != ""){
					$src = base_url("uploads/profile/".$data->data->u_photo_name);
				}else{
					$src = base_url("uploads/profile/system/default_prof_white.png");
				} 
			 ?>
			<img  src="<?php echo $src ?>" alt="" class="left u-profile">
			<?php 					
			if (isset($_SESSION['user']) && strtolower($_SESSION['user']->username) == strtolower($data->user)) {
				echo '
				<button data-tooltip="Change profile avartar or banner" data-position="right" class="tooltipped material-icons prof-editor">add_a_photo</button>
				';
			}
			?>					
		</div>
		<div class="u-cov">
			<?php 					
			if (isset($_SESSION['user']) && strtolower($_SESSION['user']->username) == strtolower($data->user)) {
				echo '
				<button alt="done_all" data-tooltip="Account settings" data-position="top" class="tooltipped open-settings edit_prof ed-details material-icons">settings</button>
				';
			}
			?>											
			<button data-tooltip="Copy profile link" data-position="top" class="tooltipped edit_prof user-share material-icons">share</button>
			<?php 					
			if (isset($_SESSION['user']) && strtolower($_SESSION['user']->username) != strtolower($data->user)) {
				$id = $data->data->usr_id;
				$ch_follow = $this->commonDatabase->get_data("tp_circles",1,false,'usr_id_adding',$_SESSION['user']->id,'u_id_added',$id);
				if ($ch_follow) {
					$txt = "done";
					$t   = "Unfollow @".strtolower($data->data->u_name);
				}else{
					$txt = "person_add";
					$t   = "Follow @".strtolower($data->data->u_name);
				}
				echo '
				<button data-tooltip="'.$t.'" data-position="left" class="tooltipped follow-user material-icons">'.$txt.'</button>
				';
			}elseif (isset($_SESSION['user']) && strtolower($_SESSION['user']->username) == strtolower($data->user)){
				echo '
				<button data-tooltip="Me" data-position="left"   class="follow-user tooltipped material-icons me">done</button>
				';
			}
			?>
		</div>
		<div class="follow-div">
			
		</div>		
	</div>			
	<?php 
	if(isset($_SESSION['user']) && strtolower($_SESSION['user']->username) == strtolower($data->user)){
		$this->load->view("components/prof_change");
	}
	?>
	<div class="u-data">
		<div class="u-in"><?php echo isset($data->name) ? $data->name:"talkpoint"  ?></div>							
		<div class="u-in">@<?php echo isset($data->user) ? $data->user:"talkpoint"  ?></div>
		<div class="u-in u-bio">
			<?php 
				if (isset($data->data->bio) && $data->data->bio !="") {
					echo $data->data->bio;
				}else{
					echo "bio";
				}
			 ?>
		</div>				
		<div class="u-in">
			<?php 
				if (isset($data->data->country) && $data->data->country !="") {
					echo $data->data->country;
				}else{
					echo "country";
				}
			?>
		</div>										
	</div>
</div>
<div class="user-meta">
	<?php 
		if (isset($_SESSION['user'])) {
			$followers = $this->commonDatabase->get_data("tp_circles",false,false,'u_id_added',$data->data->usr_id);

			if ($followers) {				
				$follower_count = common::format_number((sizeof($followers)));
			}else{
				$follower_count = 0;
			}

			$posts = $this->commonDatabase->get_data("tp_post",false,false,'usr_id',$data->data->usr_id);

			if ($posts) {
				$posts_count = common::format_number(sizeof($posts));
			}else{
				$posts_count = 0;
			}
		}
	 ?>
	<button class="u-meta meta-follow">
		<span class="left">
			Followers
		</span>
		<div class="right u-meta-data">
			<?php echo isset($follower_count) && $follower_count >= 0 ? $follower_count : 0; ?>
		</div>
	</button>
	<button class="u-meta meta-posts">
		<span class="right">
			Articles
		</span>
		<div class="left u-meta-data">
			<?php echo isset($posts_count) ? $posts_count : 0; ?>
		</div>
	</button>
</div>