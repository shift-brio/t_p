<?php if(!isset($_SESSION['user'])){return "";} ?>
<div class="star_rate">
		<?php 
			$ch_rating = $this->commonDatabase->get_data("tp_ratings",1,false,'usr_id',$_SESSION['user']->id);
		 ?>
		<div class="raters">
			<?php 
				if ($ch_rating) {
					for ($i=0; $i < $ch_rating[0]['rating']; $i++) { 
						echo '<button class="material-icons rater-btn open" id="1">star</button>';
					}
					for ($i=0; $i < (5 - $ch_rating[0]['rating']); $i++) { 
						echo '<button class="material-icons rater-btn" id="1">star</button>';
					}
				}else{
					echo '
						<button class="material-icons rater-btn" id="1">star</button>
						<button class="material-icons rater-btn" id="2">star</button>
						<button class="material-icons rater-btn" id="3">star</button>
						<button class="material-icons rater-btn" id="4">star</button>
						<button class="material-icons rater-btn" id="5">star</button>
					';
				}
				$ratings = common::getRating();
			 ?>	
		</div>
		<div class="rating">
			<div class="rating-tag">Kindly rate us <img src="<?php echo base_url("uploads/system/smiley.png"); ?>" alt="Rate us" class="smiley-icon"></div>
		</div>
		<div class="rating-tools">
			<button class="left rating-tool close-rate material-icons">close</button>
			<div class="ratings rating-inf"><?php echo $ratings['rating'] ?> ~ <?php echo $ratings['count'] ?> people</div>
			<?php
				if (!$ch_rating) {
				 	 echo '<button class="right rating-tool send-rating material-icons">send</button>';
				 } 
			 ?>
		</div>
</div>