$(document).ready(function(){
	support_init();support_click();
})
var i_support = "";
support_init = function(){
	support_get = function(){
		$.ajax({
			url:base_url+"supportList",
			type:"POST",
			success:function(response){
				if (response.status) {
					if (response.m.length > 0) {
						$(".support-users").html(response.m);
					}else{
						$(".support-users").html('<div class="flow-text center">No quesries</div>');
					}
					support_click();
				}else{
					$(".support-users").html(response.m);
				}
			}
		})
	}
	support_get();
	setInterval(support_get, (5* 1000))

	support_click = function(){
		$(".support-user").each(function(){
			$(this).click(function(){
				var user = $(this).attr("data-item");
				if (user) {
					var handle = $(this).children(".u-support").children(".support-handle").html();
					$(".sup-text").html(handle);
					$(this).children(".u-support").children(".support-date").children().remove();
					$(".close-support").show();
					load_u_support(user,$(this));
				}
			})
		})
	}
	$(".close-support").click(function(){
		$(".sup-text").html("Click on a user to start chat");
		clearInterval(i_support);
		$(".support-d").html("");	
		$(".close-support").hide();	
		$(".support-user.active").removeClass("active");
	})
	load_u_support = function (user = false,removed = false){
		if (user && !$(".editor-loader").is(":visible")) {
			$(".editor-loader").show();
			$.ajax({
				url:base_url+"uSupport",
				type:"POST",
				data:{user:user},
				complete:function(){
					$(".editor-loader").hide();
				},
				success:function(response){
					if (response.status) {
						clearInterval(i_support)
						if (response.m.length > 0) {
							$(".support-d").html(response.m);
						}
						$(".support-user.active").removeClass("active");
						removed.addClass("active");
						load_support(user);
						i_support = setInterval(load_support,5000);
					}
				},
				error:function(){
					internet_error();
				}
			})
		}else{
			notify("Invalid user");
		}
	}
	load_support = function (user = false){
		if (!user) {
			user = $(".support-user.active").attr("data-item");
		}
		if (user) {			
			$.ajax({
				url:base_url+"uSupport",
				type:"POST",
				data:{user:user},				
				success:function(response){
					if (response.status) {
						if (response.m.length > 0) {
							$(".support-d").html(response.m);
						}						
					}
				}
			})
		}
	}
	$(".support-inp").on("keypress",function(e){
		if ( e.which == 13) {
			var text  = $(this).val();
			if (get_length(text) > 3) {				
				send_support(false,text);
			}else{
				notify("Message too short",5000,"warning");
			}
		}
	})
	$(".support-submit").click(function(){
		var text  = $(".support-inp").val();
		if (get_length(text) > 3) {
			send_support(false,text);
		}else{
			notify("Message too short",5000,"warning");
		}
	})
	send_support = function (user = false,data = false){
		if (!user) {
			user = $(".support-user.active").attr("data-item");
		}
		if (user && data) {
		$(".editor-loader").show();			
			$.ajax({
				url:base_url+"adminSupport",
				type:"POST",
				data:{user:user,text:data},		
				complete:function(){
					$(".editor-loader").hide();
				},
				success:function(response){					
					if (response.status) {
						if (response.m.length > 0) {
							$(".support-d").html(response.m);
						}		
						$(".support-inp").val("");				
					}else{
						notify(response.m,5000,"error");
					}
				},
				error:function(){
					internet_error();
				}
			})
		}
	}
}