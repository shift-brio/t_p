<div class="u-options">	
	<?php 
		if (isset($_SESSION['user'])) {
			echo '
				<button class="u-option me" data-me="'.$_SESSION['user']->username.'">
					<i class="material-icons right">person</i>
					<span class="left">Profile</span>
				</button>
				<button class="u-option country-open">
					<i class="material-icons right">expand_less</i>
					<span class="left">Countries</span>
				</button>';
				if (common::isEditor()) {
					echo '
						<button class="u-option editor-open" href="'.base_url("editor").'">
							<i class="material-icons right">edit</i>
							<span class="left">Editor</span>
						</button>
					';
				}
				if (common::isSupport()) {
					echo '
						<button class="u-option editor-open" href="'.base_url("support").'">
							<i class="material-icons right">group</i>
							<span class="left">Support</span>
						</button>
					';
				}				
				echo '<button class="u-option" href="'.base_url("logout").'">
					<i class="material-icons right">lock</i>
					<span class="left">Log Out</span>
				</button>
			';
		}else{
			echo '
				<button class="u-option login-href">
					<i class="material-icons right">lock_open</i>
					<span class="left">Log In</span>
				</button>
			';
		}
	 ?>
	<div class="option-arrow"></div>
</div>