<?php 
  $d = $data;
	$data = json_decode(json_encode(['data' => $data]));
 ?>
<?php 
	isset($_SESSION['user']) ? $this->load->view("components/account_settings",['data' => $d]) :""; 
?>
<div class="row user-cover talk-main-body user-body pretty-scroll">
	<div class="col s12 m12 l3 profiler">
		<?php $this->load->view("components/user_profile"); ?>
	</div>
	<div class="col s12 m12 l6 posts-area pretty-scroll">
		<?php						
			$time = time();						
			
			$user = $data->data->data->usr_id;
			$posts  = $this->commonDatabase->get_cond("tp_post","usr_id='$user' AND added_at <= '$time' AND state='1' order by id DESC limit 10");
			if (!$posts) {
				echo '<div class="all-posts center flow-text">All articles</div>';
				$posts = [];
			}
			foreach ($posts as $post) {
				echo common::renderArticle($post,'col','feeds');
			}
		 ?>
	</div>
	<div class="col s12 m12 l3 page-right hide-on-med-and-down">
		<?php $this->load->view("components/talk_about"); ?>
	</div>
</div>