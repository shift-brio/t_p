$(document).ready(function(){
$('.dropdown-trigger').dropdown();
notify = function(text = false,time = false,type = false,sound = false){
	var t = 5000;
	var cls = "info";
	if (text) {
		if (type == 'warning') {
			var cls = "warning";
		}else if(type == 'error'){
			var cls = "error";
		}else{
			var cls = ""
		}				
		if (time) {
			t = time;
		}else{
			t = 5000;
		}
		Materialize.toast(text,t,cls);
	}
	if (sound) {

	}			
}
	//toast("Lorem ipsum dolor sit amet",30000,'error')
	var base_url = $("base").attr("href");		
	function get_length(val){
		return val.replace(/\s/g, '').length;
	}
	function validateInput(input,type = 'email'){
		if (type === 'phone') {
			var exp = /[+]+[0-9]+[0-9]+[0-9]+[7]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]/;
			var exp1 = /[0]+[7]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]/;

			if (exp.test(input) || exp1.test(input)) {
				return true;
			}else{
				return false;
			}
		} else if(type === 'email'){
			var exp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		}else{
			return false;
		}

		return exp.test(input);
	}
	alert = function(text){
		notify(text);
	}
	loading = function(state = false,wid = false){			
		var loader = $(".loader-indic");
		var loader_cover = $(".loader");
		var animation_text = "animation: progress_infin 1s infinite";
		if (state && wid) {
			loader_cover.show();
			loader.attr("style","width:"+wid+"%;");				
		}else if(state){
			loader_cover.show();
			loader.attr("style",""+animation_text+";");
		}else{
			loader.attr("style","animation:none !important;");
			loader.attr("style","width:0%;");
			loader_cover.hide();
		}
	}	
	var verify_username = function(username = ""){
		var unwanted = ["!","@","#","`","&","/","|",'"'];
		var regex = /[a-zA-Z\-0-9]|\~|\_|\-|\./;			
		var username = username.split("");			
		for (var i = 0; i < username.length; i++) {
			if (!regex.test(username[i])) {					
				return false;
			}
		}
		return true;
	}
	sign_up = function(){
		var err_class = "er-in";
		$("."+err_class).removeClass(err_class);			

		var first_name_	  = $(".first_name");
		var last_name_ 	  = $(".last_name");
		var username_ 	  = $(".username");
		var email_ 		    = $(".email");
		var country_	    = $(".country");
		var birth_ 		    = $(".birth-year");
		var password_ 	  = $(".password");
		var confirm_pass_ = $(".password-confirm");
		var terms_        = $(".terms_check");

		var first_name 	 = first_name_.val();
		var last_name 	 = last_name_.val();
		var username 	   = username_.val();
		var email 		   = email_.val();
		var country 	   = country_.val();
		var birth 		   = birth_.val();
		var password 	   = password_.val();
		var confirm_pass = confirm_pass_.val();
		var terms        = terms_.attr("value");			

		var first_valid     = get_length(first_name) > 0 ? true : false;
		var last_valid      = get_length(last_name)  > 0 ? true : false; 			
		var username_avail  = check_username(username);	
		var username_valid  = verify_username(username) && username_avail && (username.length > 0) ? true : false;
		var email_valid     = validateInput(email,'email') ? true : false;
		var country_valid   = country != "" ? true : false;
		var birth_valid     = birth.length == 4 ? true : false;
		var pass_valid      = password.length > 4 ? true : false;
		var pass_match		  = pass_valid && (password == confirm_pass) ? true : false;
		var terms_valid     = terms == "true" || terms == true ? true : false;


		if (!first_valid) {
			notify("Enter a valid First Name");
			first_name_.addClass("er-in");
		}
		if (!last_valid) {
			notify("Enter a valid Last Name");
			last_name_.addClass("er-in");
		}
		if (!username_avail) {				
			notify("The username has already been taken.");
		}else{
			if (!username_valid) {
				if (!verify_username(username)) {
					notify("Your username contains characters that cannot be used in a username", 8000, "warning");
				}else{
					notify("Enter a valid Username");
				}
			}				
		}	
		if (!email_valid) {
			email_.addClass("er-in");
			notify("Enter a valid Email");
		}	
		if (!pass_match) {
			password_.addClass("er-in")
			if (!pass_valid) {					
				notify("Enter a valid Password : more than 4 characters");
			}else{
				confirm_pass_.addClass("er-in")
				notify("Your passwords do not match");
			}
		}	
		if (!birth_valid) {
			alert("Enter a valid Year of Birth");
		}
		if (!country_valid) {
			alert("Enter a valid Country");
		}
		if (!terms_valid) {
			notify("You must agree to terms before proceeding.", 5000, "error");
		}
		if (first_valid && last_valid && username_valid && email_valid && country_valid && birth_valid && pass_match && terms_valid) {
			loading(true);
			$.ajax({
				type:"POST",
				url:base_url+"/save_details",
				data:{first_name:first_name,last_name:last_name,username:username,email:email,country:country,birth:birth,password:password},
				complete:function(){
					loading(false);
				},
				success:function(response){
					if (response.status) {
						location.reload();
					}else{
						notify(response.m,5000,"warning");
					}
				},
				error:function(){
					internet_error();
				}
			})
		}else{
			notify("Maker sure all your details are correct.",10000,"warning");
		}
	}
	$(".password,.password-confirm").on("keyup",function(){
		if (match_pass()) {
			$(".password-confirm").addClass("pass-match");
		}else{
			$(".password-confirm").removeClass("pass-match");
		}
	})
	$("input[type='checkbox']").on("change",function(){
		var v = $(this).attr("value");
		if ( v == "false" || v == false) {
			$(this).attr("value","true");
		}else{
			$(this).attr("value","false");
		}
	})
	internet_error = function(){
		notify("Internet error, check your connection and try again.",5000,"error");
	}
	match_pass = function(){
		var password 	  = $(".password").val();
		var confirm_pass  = $(".password-confirm").val();

		if (password == confirm_pass && password.length > 4) {
			return true
		}
		return false;
	}
	check_username = function(name = false){
		return true;
	}	
	$(".submit-sign").click(function(){
		sign_up();
	})
	load = function(){
		console.log($($.data_loader({},"/legal/terms")));			
	}					
})