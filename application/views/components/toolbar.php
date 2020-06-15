<footer class="talk-bottom <?php echo isset($_SESSION['user']) ? "online": "offline" ?>" data-toolbar="true">	
	<div class="left">				
		<?php 
			if (isset($_SESSION['user'])) {
				echo '
					<button data-position="top" data-tooltip="Ask support"  class="tooltipped foot-tool about t-support">
						<img src="'.base_url("uploads/system/about_white.png").'" alt="about">
					</button>
					<button data-position="top" data-tooltip="Search user/article"  class="tooltipped foot-tool search-tool">
						<img src="'.base_url("uploads/system/search_white.png").'" alt="search">
					</button>
					<div class="u-bottom"><a href="'.base_url("writer/".$_SESSION['user']->username).'">@'.$_SESSION['user']->username.'</a></div>
				';
			}
		 ?>				
	</div>
	<div class="posts-loader">
		<div class="loader-1 center_"><span></span></div>
	</div>	
	<div class="right">			
		<?php
			if (isset($_SESSION['user'])) {
				$ch_notif = $this->commonDatabase->get_data("tp_notifications",false,false,'owner',$_SESSION['user']->id,'state','0');
				if ($ch_notif) {
					$style = "display:initial;";
				}else{
					$style = "display:none;";
				}
			 	echo '			 		
			 		<button data-position="top" data-tooltip="Notifications"  class="tooltipped notif-btn foot-tool">
						<div style="'.$style.'" class="notif-indic"></div>
						<img src="'.base_url("uploads/system/notif_white.png").'" alt="notifications">
					</button>
			 	';
			 } 
		 ?>
		<button data-position="top" data-tooltip="Menu"  class="tooltipped foot-tool account-tool">			
			<img src="<?php echo isset($_SESSION['user']) ? base_url("uploads/profile/".$_SESSION['user']->profile) : base_url("uploads/system/user__.png"); ?>" alt="user">
		</button>
		<button class="foot-tool close-tool">
			<img src="<?php echo base_url("uploads/system/close_white.png"); ?>" alt="menu">
		</button>
	</div>
</footer>