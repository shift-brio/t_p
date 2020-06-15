<link rel="stylesheet" href="<?php echo base_url("bootstrap/css/join.css") ?>">
<nav class="talk-nav center" data-expanded="false">
	<div class="loader">
		<div class="loader-indic">
			
		</div>		
	</div>
	<div class="talk-nav-left">
		<a href="<?php echo base_url(); ?>">
			<img src="<?php echo base_url("logo.png"); ?>" alt="TalkPoint Logo" class="talk-logo">
		</a>
	</div>
	
	<div class="talk-back right">
		<a class="sign-tag">Sign Up</a>	
	</div>
</nav>
<div class="sign-body">
	<div class="row">
		<div class="col s12 m12 l3"></div>
		<div class="col s12 m12 l6 sign-cont">
			<div class="sign-group">
				<label class="sign-label">First Name</label>
				<input type="text" class="first_name sign-in browser-default" placeholder="First Name">
			</div>
			<div class="sign-group">
				<label class="sign-label">Last Name</label>
				<input type="text" class="sign-in last_name browser-default" placeholder="Last Name">
			</div>
			<div class="sign-group">
				<label class="sign-label">Username</label>
				<input type="text" class="sign-in username browser-default" placeholder="Username">
			</div>
			<div class="sign-group">
				<label class="sign-label">Email</label>
				<input type="email" class="sign-in email browser-default" placeholder="Email">
			</div>
			<div class="sign-group">
				<label class="sign-label">Country</label>				
				<?php $this->load->view('countries'); ?>
			</div>
			<div class="sign-group">
				<label class="sign-label">Birth year</label>
				<select class="sign-sel birth-year browser-default" >
					<option value="">-- birth year --</option>
					<?php $this->load->view('years'); ?>
				</select>
			</div>
			<div class="sign-group">
				<label class="sign-label">Password</label>
				<input type="password"  class="sign-in password browser-default" placeholder="&#128274; Password">
			</div>
			<div class="sign-group">
				<label class="sign-label">Confirm password</label>
				<input type="password" class="sign-in password-confirm browser-default" placeholder="&#x2713;Confirm password">
			</div>
			<div class="sign-group">
				<p class="check_p">
			      <input value="false" type="checkbox" class="filled-in terms_check" id="terms">
			      <label for="terms">I Accept <a target="_blank" href="<?php echo base_url("legal/terms"); ?>">Terms of Service</a></label>
			    </p>
			</div>
			<div class="sign-group right">
				<button class="submit-sign">Submit</button>
			</div>
		</div>
		<div class="col s12 m12 l4"></div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url("bootstrap/js/join.js") ?>"></script>