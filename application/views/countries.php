<select name="country" id="country" style="border-color: #006566;" class="browser-default form-group sign_up_input u-in sign-sel country">    	
	<?php 
		echo '<option value="">-- select country --</option>';
		foreach ($this->config->item('countries') as $country) {				
			echo "<option value='".$country."'>".$country."</option>";
		}
	 ?>
</select>