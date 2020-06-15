<div class="display_change">
	<div class="ad-top">
		<div class="ad-top-head">
			<button data-tooltip="Remove avartar/banner" data-position="top" class="left tooltipped ad-btn material-icons rm-prof">delete</button>	
			<div class="del-select left">
				<button data-tooltip="Remove banner" data-position="top" class="del-option tooltipped" del-item="banner">
					Banner
				</button>
				<button data-tooltip="Remove avartar" data-position="top" class="del-option tooltipped" del-item="prof">
					Avartar
				</button>
			</div>		
			<button class="right close-change ad-btn material-icons">close</button>
		</div>
		<div class="ad-b center">
			<label for="prof-change">
				<img class="prof_edit" src="<?php echo base_url("uploads/system/prof_edit.png") ?>" alt="prof_edit">
			</label>
			<input onchange="read_prof(this)" type="file" id="prof-change" class="prof-change hidden">
			<div class="s--s">
				<button data-tooltip="Set as banner" data-position="top"  class="s-ban s-p tooltipped left">Banner</button>
				<button data-tooltip="Set as profile image" data-position="top"  class="s-prof tooltipped s-p right">Profile</button>
			</div>
		</div>
	</div>
</div>