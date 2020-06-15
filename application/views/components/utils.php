<div class="row talk-utils">
	<div class="col m12 s12 l4"></div>
	<div class="col m12 s12 l4">
		<div class="post-utilities">
			<div class="prompt-util util-item">
				<div class="prompt-div">
					<div class="prompt-title">Enter password to continue</div>
					<input  type="password" placeholder="Password" class="browser-default pass-prompt">
				</div><br>				
				<div class="tools">
					<button class="close-util left util-btn material-icons">close</button>
					<button class="copy-link right util-btn material-icons">lock_open</button>
				</div><br><br>
			</div>
			<div class="reply-util util-item">
				<textarea placeholder="Reply" name="comment-area" id="comment-area"  class="comment-area reply-comment  browser-default"></textarea><br>
				<div class="reply-img">
					<label for="sel-reply">
						<span data-position="right" data-tooltip="Attach image to reply" class="material-icons tooltipped reply-img-sel">camera_alt</span>			
					</label>
					<input onchange="reader(this,'.reply-selected')" type="file" id="sel-reply" name="sel-reply" class="hidden sel-reply">
					<div class="right">
						<div class="reply-img-name"></div>
						<button data-position="right" data-tooltip="Remove image" class="material-icons right cancel-reply-img tooltipped">close</button>
					</div><br class="reply-br">
					<img src="" alt="Selected image" class="reply-selected">
				</div>
				<div class="tools">
					<button class="close-util left util-btn material-icons">close</button>
					<button data-position="left" data-tooltip="Send reply" class="send-reply tooltipped right util-btn material-icons">send</button>
				</div><br><br>
			</div>
			<div class="del-util util-item">
				<div class="del-info">
					Are you sure you want to delete this item?
				</div>							
				<div class="tools">
					<button class="close-util left util-btn material-icons">close</button>
					<button class="del-item right util-btn material-icons">delete</button>
				</div><br><br>
			</div>
			<div class="copy-util util-item">
				<div class="center copy_de">
					Copy Link
					<a href="" target="_blank" class="material-icons copy-go">launch</a>
				</div>
				<div class="copy-div">
					<input value="" spellcheck="false" placeholder="Link" type="text" class="browser-default link-hold">
				</div><br>				
				<div class="tools">
					<button class="close-util left util-btn material-icons">close</button>					
					<button class="copy-link right util-btn material-icons">link</button>
					<div class="right copied-text">copied</div>
				</div><br><br>
			</div>						
			<?php 
				if(!isset($_SESSION['user'])){
					$this->load->view("components/logins");
				} 
			?>
			<?php 
				if(isset($_SESSION['user'])){
					$this->load->view("components/share_util");
				} 
			?>
		</div>
	</div>
	<div class="col m12 s12 l4"></div>
</div>