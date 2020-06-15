<?php isset($_SESSION['user']) ? $this->load->view("components/country_tools") :""; ?>
<?php $this->load->view("components/image_viewer"); ?>
<?php $this->load->view("components/media_player"); ?>
<?php isset($_SESSION['user']) ? $this->load->view("components/new_post") :""; ?>
<?php $this->load->view("components/utils"); ?>
<!-- rate us -->
<!-- TalkPoint -->
<div class="talk-point">
	<style type="text/css">
		.talk-post{
			display: initial !important;
		}
	</style>
	<?php
	//var_dump($data);exit();
		$this->load->view('components/article'); 
	?>
</div>

<!-- footer -->
<!-- base login tools -->
<?php !isset($_SESSION['user']) ? $this->load->view("components/log_tabs") :""; ?>
<?php $this->load->view("components/u_options"); ?>
<?php isset($_SESSION['user']) ? $this->load->view("components/unfollow") :""; ?>
<!-- user menu popup window -->
<!-- Admin functions here -->
<!-- base tools -->