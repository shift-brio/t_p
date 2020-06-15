<div class="share-util util-item">
	<div class="share-cont">
		<div class="share-selector">
			<div class="selector-head">
				<button data-tooltip="Close" data-posistion="right" class="selector-tool tooltipped select-toggle material-icons">arrow_back</button>
				<h6 class="target-share">Select audiance 								
				</h6>
			</div>
			<div class="selector-body" data-item='timeline'>
				<div class="select-item">Share to timeline 
					<div value="true" class="right select-indic timeline-select">
						<i class="material-icons select-t">done</i>
					</div>
				</div>
				<div class="select-divider"></div>
				<div class="divider"></div>
				<?php
					if (isset($_SESSION['user'])) {
						$user = $_SESSION['user']->id;
					 	$followers = $this->commonDatabase->get_cond("tp_circles","u_id_added= '$user' AND usr_id_adding != '$user'");
					 	if ($followers) {
					 		foreach ($followers as $follower) {
					 			$f_data = $this->commonDatabase->get_data("tp_users",1,false,'usr_id', $follower['usr_id_adding']);
					 			if ($f_data && $f_data[0]['usr_id'] != $user) {
					 				$f_data = $f_data[0];
					 				echo '
					 					<div class="select-item">@'.$f_data['u_name'].' 
											<div value="true" data-follower="'.$f_data['u_name'].'" class="right select-indic user-select">
												<i class="material-icons select-t">done</i>
											</div>
										</div>
					 				';
					 			}
					 		}
					 	}
					 } 
				 ?>
			</div>
		</div>
		<div class="share-body">
			<button data-tooltip="Select audiance" data-position="right" class="select-toggle tooltipped sel-main material-icons">person_add</button>
			<div class="right share-title">Share to 
				<span class="talk-name">TalkPoint</span>
			</div>
			<div class="ch-info share-info">Say something about this article . . .</div>
			<div class="share-say" contenteditable="true"></div>
		</div>
	</div>	
	<br>			
	<div class="tools">
		<button class="close-util left util-btn material-icons">close</button>
		<button class="shere-go right util-btn material-icons">done</button>
	</div><br><br>
</div>