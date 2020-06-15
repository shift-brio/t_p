<div class="new-post">
	<div class="main-post-head new-post-head">
		<div class="head-title">
			<img src="<?php echo base_url("logo.png"); ?>" alt="TalkPoint Logo" class="talk-logo">
		</div>			
		<div class="right head-tools">
			<button data-position="left" data-tooltip="Close"  class="tooltipped material-icons head-tool new-close">close</button>					
		</div>
	</div>
	<div class="new-post-body">
		<div class="row new-row">
			<div class="col s12 m12 l3"></div>
			<div class="col s12 m12 l6 new-post-area">
				<div class="new-post-title">Publish new article</div>
				<div class="new-post-main">
					<div class="add-group right">
						<label class="add-label">Select column</label>
						<select class="browser-default add-col add-sel">
							<option value="">~ select column ~</option>
							<option value="Politics">Politics & Leadership</option>
							<option value="Money">Money & Business</option>
							<option>Health</option>  
							<option>Education</option>			    			
			    			<option value="Life">Life & Style</option> 	
			    			<option>Sports</option>		
			    			<option>Inspiration</option>
			                <option value="estate">Real Estate</option>
						</select>
					</div><br><br><br>
					<div class="add-group">
						<label class="add-label">Article title</label>
						<input type="text" placeholder="Article title" name="p_title" class="add-in p_title browser-default">
					</div>
					<div class="add-group">
						<label class="add-label">Article body</label>
						<textarea name="post_content" class="browser-default post_content add-content" placeholder="Article"></textarea>
						<script type="text/javascript">
							CKEDITOR.replace("post_content");
						</script>
					</div>
					<div class="add-group">
						<label class="add-label">Attach video/image</label>
						<select class="browser-default add-attach add-sel">
							<option value="sel">~ select ~</option>
							<option value="upload">Upload Image</option>							
							<option value="youtube">Paste YouTube link</option>
						</select>
					</div>					
					<div class="link-preview">
						<div class="link-preview-body">
							<iframe src="" style="width: 100%; height: 100%;" frameborder="0" class="new-iframe"></iframe>
						</div>
						<div class="link-preview-head">
							<button data-position="top" data-tooltip="Remove video" class="preview-tool rm-video tooltipped material-icons">
								delete
							</button>
							<button data-position="top" data-tooltip="Close" class="preview-tool close-preview tooltipped material-icons">
								close
							</button>
						</div>
					</div>
					<div class="add-group add-link-group">
						<label class="add-label">Paste YouTube video link here</label>
						<button class="prev-btn open-preview">
							<img src="<?php echo base_url("uploads/system/preview.png") ?>" class="prev-img" alt="Preview">
						</button>
						<input data-position="top" data-tooltip="Go to YouTube, right click on video and click 'copy video link' to copy, then paste the video link here." type="text" placeholder="Paste YouTube video link here" name="p_link" class="add-in p_link browser-default tooltipped">
					</div>					
					<div class="upload-preview">
						<div class="upload-preview-head">
							<button data-tooltip="Remove selected file" data-position="left" class="preview-tool tooltipped material-icons  rm-upload">delete</button>
							<button data-tooltip="Close" data-position="left" class="preview-tool tooltipped material-icons close-upload">close</button>							
						</div>
						<div class="upload-preview-body">
							<div class="center">
								<button data-tooltip="Change file" data-position="right" class="material-icons tooltipped swap-upload">swap_horiz</button>
							</div>
							<div class="center view-upload">
								<img onerror="this.style.display='none'" src="" alt="Upload image" class="upload-prev">
							</div>
						</div>						
					</div>
					<div class="add-group upload-group">
						<label class="add-label">Upload Image</label>
						<button class="prev-btn open-uploaded">
							<img src="<?php echo base_url("uploads/system/upload.png") ?>" class="prev-uploaded" alt="Preview">
						</button>
						<input  type="file" onchange="reader(this,'.upload-prev')"  id="upload-file" embeded="" placeholder="Paste link" name="upload-file" class="add-in upload-file p_file browser-default">
						<button data-position="top" data-tooltip="Remove selected image"  class="tooltipped rem-file material-icons">close</button>
					</div>
					<div class="add-group right">
						<button data-position="left" data-tooltip="Submit post"  class="tooltipped submit-post">Submit</button>
					</div>
				</div>
			</div>
			<div class="col s12 m12 l3"></div>
		</div>
	</div>
</div>