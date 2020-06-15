<div class="editors-portal row">
	<div class="col s12 m12 l1"></div>
	<div class="col s12 m12 l10 editors-cover">
		<div class="editor-head">
			<div class="editor-title">
				<img src="<?php echo base_url("logo.png"); ?>" alt="talkpoint" class="editor-logo">
				<img src="<?php echo base_url("uploads/system/about_white.png"); ?>" alt="talkpoint" class="editor-logo">
				<span class="left">Support ~ @<?php echo $_SESSION['user']->username; ?></span> 
				<div class="editor-loader">
					<div class="loader-1 center_"><span></span></div>
				</div>
			</div>
			<div class="editor-tools right">
				<a href="<?php echo base_url(); ?>">
					<button data-tooltip="Exit portal"  class="tooltipped material-icons editor-tool close-editor">home</button>
				</a>
			</div>
		</div>
		<div class="editor-body row">
			<div class="col s4 m4 l3 edit-posts support-users">
					<?php 
						$support = $this->commonDatabase->get_cond("tp_support","m_state='0' AND m_to='70' group by m_from order by m_date DESC");
						if ($support) {
							 foreach ($support as $support) {
							 	  echo common::returnSupport($support);
							 }
						}
					 ?>		
			</div>
			<div class="col s8 m8 l9 editor-m">								
				<div class="editor-contents support-div">
					<div class="info-eye support-eye">
						<span class="sup-text">Click on a user to start chat</span>
						<button data-position="left" data-tooltip="Close chat with user" class="right material-icons tooltipped close-support clickable">close</button>
					</div>
					<div class="support-d">
						
					</div>
				</div>
				<div class="editing-tool support-bottom">
					<input type="text" placeholder="Type message here..." class="browser-default support-inp">
					<button class="support-submit material-icons clickable">send</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col s12 m12 l1"></div>
</div>
<script src="<?php echo base_url("bootstrap/js/support.js"); ?>"></script>