<?php 
	$time = "";
	if (isset($x_data)) {
		$data['data'] = $x_data;
		$loaded = $data['data']['loaded'];
		$d = $data;				
	}else{
		$d = $data;
		if (isset($d->loaded)) {
			$loaded = $data->loaded;
		}
	}
 ?>
<?php isset($_SESSION['user']) ? $this->load->view("components/country_tools") :""; ?>
<?php $this->load->view("components/image_viewer"); ?>
<?php $this->load->view("components/hot_items"); ?>
<?php $this->load->view("components/media_player"); ?>
<?php //$this->load->view("components/hot_items"); ?>
<?php $this->load->view("components/projector"); ?>
<?php isset($_SESSION['user']) ? $this->load->view("components/new_post") :""; ?>
<?php $this->load->view("components/utils"); ?>
<!-- rate us -->
<?php //$this->load->view("components/rater"); ?>
<!-- base article window -->
<?php $this->load->view("components/article"); ?>
<!-- base nav -->
<?php $this->load->view("components/nav"); ?>
<!-- TalkPoint -->
<div class="talk-point">
	<?php	
		if (isset($data) && isset($loaded)) {		    		
			$this->load->view($loaded,$x['data'] = $d); 
		}else{
			$this->load->view("components/home_main");
		}
	?>
</div>

<!-- footer -->
<!-- base login tools -->
<?php isset($_SESSION['user']) ? $this->load->view("components/unfollow") :""; ?>
<?php !isset($_SESSION['user']) ? $this->load->view("components/log_tabs") :""; ?>
<?php $this->load->view("components/u_options"); ?>
<!-- user menu popup window -->
<?php isset($_SESSION['user']) ? $this->load->view("components/popups") :""; ?>
<!-- Admin functions here -->
<!-- base tools -->
<?php $this->load->view("components/toolbar"); ?>