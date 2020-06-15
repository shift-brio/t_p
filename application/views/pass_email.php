<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/materialize/css/materialize.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/css/main.css'); ?>">
<div class="row">
	<div class="col s12 m12 l3"></div>
	<div class="col s12 m12 l6">
		<div style='width:auto;padding:10px;margin-top:55px;text-align: center;border-radius:5px;background:#f2f2f2;box-shadow: 0px 0px 5px #000;'>
		<div class="post-title" style='font-weight:bold;color:#cd0000;'>
			<img style='max-height: 100px;border-radius: 5px;' class='l-logo' src='<?php echo base_url("favicon.png"); ?>' alt='logo'><br><h3>Password Recovery Code</h3>
		</div><br>		
		<div class="center">
			<p>
				Password recovery code for <span style="color:red;"><?php echo isset($email) ? $email:"email-address"; ?></span> is 
				<span style='color:blue;font-size: 1.3em;'><?php echo isset($code) ? $code : "5TRZU7"; ?></span>. 
				If you did not request for the code report this  
				<a href='mailto:info@talkpoint.online?Subject=Suspicious%20password%20recovery%20request'>Here
				</a>
			</p>
		</div>
	</div>
	</div>
	<div class="col s12 m12 l3"></div>
</div>