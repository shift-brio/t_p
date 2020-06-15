<?php $data = json_decode(json_encode($data)); ?>
<div class="account-settings">
	<div class="row settings-row">
		<div class="col m5 s11 l3 settings-cont">
			<div class="settings-head">
				<div class="settings-title">Account settings</div>
				<button data-tooltip="Close" data-position="left" class="right clickable material-icons close-settings tooltipped">close</button>
			</div>
			<div class="settings-load">
				<div class="settings-load-inner infin-loader">
					
				</div>
			</div>
			<div class="settings-tabs">
				<div class="settings-tab active tooltipped" data-tooltip="General settings" data-position="right">
					General
				</div>
				<div class="settings-tab tooltipped" data-tooltip="Security settings" data-position="right">
					Security
				</div>
			</div>			
			<div class="settings-body">
				<div class="settings-general">
					<div class="ch-info">Edit details</div>
					<div class="general-group">
						<label class="gen-label">Bio</label>
						<div contenteditable="true" class="g-bio">
							<?php echo $data->data->bio; ?>							
						</div>
					</div>
					<div class="general-group">
						<label class="gen-label">First Name</label>
						<input value="<?php echo $data->data->f_name; ?>"  placeholder="First Name" type="text" class="gen-in gen-first browser-default">
					</div>
					<div class="general-group">
						<label class="gen-label">Last Name</label>
						<input value="<?php echo $data->data->l_name; ?>" placeholder="Last Name" type="text" class="gen-in gen-last browser-default">
					</div>
					<div class="general-group">
						<label class="gen-label">Email</label>
						<input value="<?php echo $data->data->u_email; ?>" placeholder="Email" type="email" class="gen-in gen-email browser-default">
					</div>
					<div class="general-group">
						<label class="gen-label">Phone Number</label>
						<input value="<?php echo $data->data->u_phone; ?>" placeholder="Phone" type="tel" class="gen-in gen-phone browser-default">
					</div><br>
					<div class="divider"></div>
					<div class="general-group save-pass-div">
						<label class="gen-label">Enter password to save</label>
						<input autocomplete="false" placeholder="Enter password to save" type="password" class="gen-in save-pass browser-default">
					</div>
					<div class="general-group">
						<button class="sett-btn gen-submit clickable right">Save</button>
					</div>
				</div>
				<div class="settings-security">
					<div class="ch-info">Change password</div>
					<div class="general-group">
						<label class="gen-label">Current password</label>
						<input placeholder="Current password" type="password" class="gen-in gen-pass-curr browser-default">
					</div>
					<div class="general-group">
						<label class="gen-label">New password</label>
						<input placeholder="New password" type="password" class="gen-in gen-pass-new browser-default">
					</div>
					<div class="general-group">
						<button class="sett-btn save-new clickable right">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>