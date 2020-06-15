$(document).ready(function(){	
	login();forgot();tabs();init_vars();opener();linker();follower();liker();trends_pop();main_post();
	replying();commenting(true);utils();post_del();share_with();link_copier();toolbars();
	tool_tip();drop_down();rater();new_post();image_viewer();user_page();
	post_loading();send_rep();share_func();unfollowing();pull_refrsh();
})
window.removed_user = "";
window.item_loading = false;
window.isRefreshing = false;
pull_refrsh = function(){
	var pStart = {x: 0, y:0};
	var pStop = {x:0, y:0};	

	function swipeStart(e) {
	    if (typeof e['targetTouches'] !== "undefined"){
	        var touch = e.targetTouches[0];
	        pStart.x = touch.screenX;
	        pStart.y = touch.screenY;
	    } else {
	        pStart.x = e.screenX;
	        pStart.y = e.screenY;
	    }
	}

	function swipeEnd(e){
	    if (typeof e['changedTouches'] !== "undefined"){
	        var touch = e.changedTouches[0];
	        pStop.x = touch.screenX;
	        pStop.y = touch.screenY;
	    } else {
	        pStop.x = e.screenX;
	        pStop.y = e.screenY;
	    }

	    swipeCheck();
	}

	function swipeCheck(){
	    var changeY = pStart.y - pStop.y;
	    var changeX = pStart.x - pStop.x;
	    if (isPullDown(changeY, changeX)) {
	        if (!isRefreshing) {	        	
	        	if ($(".talk-post").is(":visible")) {
	        		var item = $(".talk-post").attr("data-item");
	        		type = "post";
	        		load_item(item,type);	
	        		$(".swiper").show();
	        		window.isRefreshing = true;        		
	        	}/*else if($("meta[name='page']").attr("content") == 'home'){
	        		var page = $(".talk-tab.active").attr("data-column");				
			      	var p = $("meta[name='curr_page']").attr("content");
			      	var a = $("meta[name='user']").attr("content");	        		 
	        		load_column(page,false,p,a,false);
	        		$(".swiper").show();
	        		window.isRefreshing = true;
	        	}*/else{
	        		location.reload();
	        	}
	        }       
	    }
	}

	function isPullDown(dY, dX) {
	    // methods of checking slope, length, direction of line created by swipe action 
	    return dY < 0 && (
	        (Math.abs(dX) <= 100 && Math.abs(dY) >= 250)
	        || (Math.abs(dX)/Math.abs(dY) <= 0.3 && dY >= 60)
	    );
	}

	document.addEventListener('touchstart', function(e){ swipeStart(e); }, false);
	document.addEventListener('touchend', function(e){ swipeEnd(e); }, false);
}
post_inits = function(){	
	$(".post-link").click(function(){
		$(".in-link").each(function(){
			$(this).click(function(e){			
				e.preventDefault();
				if ($(this).hasClass("post-link")) {	
					  var type = $(this).attr("data-type");
						var item = $(this).attr("data-item");
						if (type == "post") {																		
							load_item(item,type);
						}	
				}
			})		
		})
	})
	liker();
	link_copier();
	image_viewer();
	follower();
	drop_down();
	tool_tip();
	post_loading();
}
drop_down = function(){
	$('.dropdown-trigger').each(function(){
			$(this).dropdown(
			{
				inDuration: 0,
	      outDuration: 200,
			}
		);
	})
}
tool_tip = function(){	
	$('.tooltipped').tooltip({delay: 50,html:true});
}
internet_error = function(){
	notify("Network error, check your connection and try again.",5000,"error");
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
console_log = function(v){
	console.log(v);
}
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
		Materialize.toast(text,t,cls)
	}
	if (sound) {

	}	
	/*setTimeout(function(){
		$("body").click(function(){
			$(".toast").hide();
		})
	},100)*/	
}
alert = function(text){
	notify(text);
}	
isMobile = function(){
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) 
	{
		return true;
	}
	else{
		return false;
	}	
}	
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
in_array = function(item = '',array = []){
	for (var i = 0; i < array.length; i++) {
		if (item == array[i]) {
			return true;
		}
		return false
	}
}
home_btn = function(state = false){
	if (isMobile() && $("meta[name='page']").attr("content") != 'home') {
		if (state) {
			$(".home-btn").show();			
		}else{
			$(".home-btn").fadeOut("slow");			
		}
	}
}
init_events_main = function(){	
	tabs = function(){		
		var tab = $(".talk-tab");
		var cols = $(".talk-nav-cols");

		if ($("meta[name='page']").attr("content") != 'home' && isMobile()) {
				home_btn(true);
		}		
		tab.each(function(){
			$(this).click(function(e){				
				e.preventDefault();						
				var page = $(this).attr("data-column");				
      	var p = $("meta[name='curr_page']").attr("content");
      	var a = $("meta[name='user']").attr("content");	          	
				if (curr_page == 'home' || curr_page == 'user') {
					if (isMobile && $(".close-cols").is(":visible")) {
						cols.toggle();
					}									
					if (curr_page == "user") {
						set_col(page);
						location.href = base_url;
					}else{
						load_column(page,false,p,a,$(this));
					}					
				}else{
					console_log(curr_page);
					//location.href = base_url+"/"+page;
				}
			})
		})
		$(document).on("mouseover",function(){
			if (!$(".menu-toggle").is(":visible")) {
				cols.show();
			}
		})
	}		
}
init_events_main();
opener = function(){
	//console.log(",..s.")
	var tab_opener = $(".menu-toggle");
	var cols = $(".talk-nav-cols");
	closer = $(".close-cols");
	tab_opener.click(function(){
		if (isMobile) {
			cols.toggle();
		}	
	})
	closer.click(function(){
		cols.hide();
	})
}
linker = function(){
	$(".in-link").each(function(){	
		$(this).click(function(e){			
			e.preventDefault();
			if ($(this).hasClass("post-link")) {	
					var isReposted = $(e.target).is(".reposted") || $(e.target).is(".reposted-link") || $(e.target).is(".rep-data")  || $(e.target).is(".rep-data")  || $(e.target).is(".reposted-image") || $(e.target).is(".rep-text") || $(e.target).is(".rep-headline")  || $(e.target).is(".rep-meta");								
					if ($(e.target).hasClass("post-image")) {	
					  console_log($(e.target).attr("data-yt"));					
						if (!$(e.target).attr("data-yt")) {
							var p_src = $(e.target).attr("src");
							if (p_src != "") {							
								image_viewer(p_src);
							}
						}else{
							var embed = "https://www.youtube.com/embed/"+$(e.target).attr("data-yt");
							media_player("yt",embed);
						}
					}else if(isReposted){
						var repo = $(this).parent().children(".post-main").children("a");
						var type =  "post";
						if (type == "post") {											
							var item = repo.attr("data-item");
							/*do something with internal link*/
							if (!$(".talk-post").is(":visible")) {
								load_item(item,type);
							}						
						}												
					}else{
						var type = $(this).attr("data-type");
						var item = $(this).attr("data-item");
						if (type == "post") {					
							if (!$(".talk-post").is(":visible")) {
								load_item(item,type);
							}						
						}												
						/*do something with internal link*/						
					}
			}else{
				var type = $(this).attr("data-type");
				if (type == "post") {									
					var item = $(this).attr("data-item");
					/*do something with internal link*/
					if (!$(".talk-post").is(":visible")) {
						load_item(item,type);
					}				
				}
				if (type == "user") {
					$(".talk-post").hide();
					$(".left-pop").hide();
					var item = $(this).attr("data-item");
					var href = base_url+"writer/"+item;
					location.href = href;
				}	
				if (type == "editor") {
				  var item = $(this).attr("data-item");					
					load_item(item,"editor");
				}			
			}
			$(".popups").hide();
		})
	})	
	$(".post_comments,.views").each(function(){
		$(this).click(function(){
			var item  = $(this).parent().parent().parent().attr("data-post");
			var type = "post";		
			if (!$(".talk-post").is(":visible")) {
				load_item(item,type);
			}			
		})
	})
	$(".u-profile").on("error",function(){
		var default_p = base_url+"uploads/system/default_prof_white.png";
		$(".u-profile").attr("src",default_p);
	})
	$(".user-profile").on("error",function(){
		var default_s = base_url+"uploads/system/banner.png";
		$(".u-profile").attr("style","background-image:url("+default_s+");");
	})
	$(".author-img").each(function(){
		$(this).on("error",function(){
			var default_s = base_url+"uploads/system/default_prof_white.png";
			$(this).attr("src",default_s);
		})
	})
}
post_opener = function(state = false){
	if (state) {
		$(".talk-post").show();
	}else{
		$(".talk-post").hide();
	}
}
follower = function(){
	$(".follow_").each(function(){
		$(this).click(function(){
			var p = $(this).parent().parent();
			target = p.attr("data-item");
			var item_loader = p.children(".follow-prog");
			if ($(this).html() == "done") {
		  	 $(".unfollower").show();
		  	 window.removed_user = $(this);
		  	 $(".unf-name").html('Unfollow <span data-user="'+target+'" class="unf-handle">@'+target+'</span>?');
		  }else{
		  	 follow_user(target,item_loader);
		  }						
		})
	})	
	$(".follow-post:not(.follow-main)").click(function(){
		var p = $(this).parent();
		target = p.children("a").attr("data-item");	
		var p = $(this).parent().parent();								
	  if ($(this).html() == "done") {
	  	 $(".unfollower").show();
		  	 window.removed_user = $(this);
		  	 $(".unf-name").html('Unfollow <span data-user="'+target+'" class="unf-handle">@'+target+'</span>?');
	  }else{
	  	 follow_user(target,false,$(this));
	  }
	})
	$(".follow-user:not(.me)").each(function(){
		$(this).click(function(){			
			 target = $("meta[name='user-page']").attr("content");				 
		  if ($(this).html() == "done") {
		  	  $(".unfollower").show();
		  	 window.removed_user = $(this);
		  	 $(".unf-name").html('Unfollow <span data-user="'+target+'" class="unf-handle">@'+target+'</span>?');
		  }else{
		  	 follow_user(target,false,$(this));
		  }
		})
	})
}
liker = function(){
	$(".liker:not(.like-open)").each(function(){
		$(this).click(function(){
			if ($(".talk-post").is(":visible")) {
				var article = $(".talk-post").attr("data-item");			
			}else{
				var article = $(this).parent().parent().parent().children(".post-main").children("a").attr("data-item");			
			}
			like_post(article);
		})
	})	
};
post_del = function(){
	$(".del-post").each(function(){
		$(this).click(function(){
			if ($(".talk-post").is(":visible")) {
				var item = $(".talk-post").attr("data-item"); 
			}else{
				var item = $(this).parent().parent().parent().parent().attr("data-post");							
			}
			prompt_del(item,'post',"Delete post?","[data-post='"+item+"']");
		})
	})
}
trends_pop = function(){
	$(".nav-ex").each(function(){
		$(this).click(function(){
			var main = $(".left-pop");			
			main.toggle();
			type = $(this).attr("data-type");
			if (type == "popular") {
				$(".hot-posts-head-title").html("Most popular");
			}else{
				$(".hot-posts-head-title").html("Trending now");
			}			
			load_trends(type);
		})
	})
	$(".hot-posts-head-tool").click(function(){
		$(".left-pop").hide();
	})
};
main_post = function(){
	var closer = $(".close-main-post");
	$(".item-img").each(function(){
		$(this).on("error",function(){
			var default_s = base_url+"uploads/system/default_prof_white.png";
			$(this).attr("src",default_s);
		})
	})
	clicker = function(){
		$("button").each(function(){
			$(this).click(function(){
				if (isMobile()) {
					$(".click")[0].pause();
					setTimeout(function(){
						$(".click")[0].play();
					},10)
					$(".click")[0].volume = 0.2;
				}
			})
		})
	}
	clicker();
	$(".post-in-r").scroll(function(){			
	  	if(($(this).scrollTop()) >= ($(this)[0].scrollHeight - 600)) {
          var start_id = $(".main-comments-body > :last-child").attr("data-comment");
          var post = $(".talk-post").attr("data-item");

          if (!$(".loader").is(":visible")) {
          	if (start_id) {
          		load_comments(post,start_id);
          	}
          }        
      }else{
      	$(".posts-loader").hide();
      }		    
	})	
	var last_scroll = 0;
	var scrolled = 0;
	$(".posts-body, .user-cover,.post-in-r").scroll(function(){
		if (isMobile()) {
			var st = $(this).scrollTop();
			if (st > last_scroll) {
				scrolled += 1;
				if (st > 0) {
					$(".log-tabs").fadeOut("slow");
					home_btn(false);
					$(".main-post-footer").fadeOut("slow");
				}
			}else{
				scrolled -= 1;
				if (st == 0) {
					home_btn(true);
					$(".main-post-footer").fadeIn("slow");
					$(".log-tabs").fadeIn("slow");
				}
			}
			last_scroll = st;
		}
	})	
	$(".country-open").click(function(){
		$(".country-tools").show();
		load_countries();
	})
	$('.save-country').click(function(){
		if ($('.country-indic-all').attr("value") == "true") {
			var count_ries = "all";
		}else{
			count_ries = [];
			$('.countries-main > .country-item:not(.country-all)').each(function(){
				if ($(this).children(".country-indic").attr("value") == "true") {
					count_ries.push($(this).attr("data-country"));					
				}
			})
		}
		save_countries(count_ries);
	})
	closer.click(function(){
		if (curr_page != "post") {
			post_opener(false)
		}else{
			var url_reload = base_url;			
			location.href = url_reload;
		}
	})
	$(".close-player").click(function(){
		$(".media-player").hide();
		$(".yt-iframe").attr("src","");
	})
	$(".cancel-load").click(function(){
		loading(false);		
	})
	$(".like-open").each(function(){
		$(this).click(function(){
			if ($(".talk-post").is(":visible")) {
				var article = $(".talk-post").attr("data-item");			
			}else{
				var article = $(this).parent().parent().parent().children(".post-main").children("a").attr("data-item");			
			}
			like_post(article,true);
		})
	})	
	$(".follow-main").click(function(){
		var p = $(this).parent().parent();
		target = p.children("a").attr("data-item");	
		var p = $(this).parent().parent();
		if ($(this).html() == "done") {
	  	  $(".unfollower").show();
		  	 window.removed_user = $(this);
		  	 $(".unf-name").html('Unfollow <span data-user="'+target+'" class="unf-handle">@'+target+'</span>?');
	  }else{
	  	 follow_user(target,false,$(this));
	  }							 
	})
}
log_console = function(v){
	console_log(v);
}
function reader(input = false,t = false, name = false) { 
  if (FileReader && input && input.files[0]) {
	    var file = new FileReader();	     	
	    file.onload = function () {	    	
	        $(t).attr("src",file.result);
	    }
	    file.readAsDataURL(input.files[0]);
  }
}
function read_prof(input  = false){ 	
	var t = ".prof_edit";
  if (FileReader && input && input.files[0]) {
	    var file = new FileReader();	     	
	    file.onload = function () {	    	
	        $(t).attr("src",file.result);
	    }
	    file.readAsDataURL(input.files[0]);
  }
}
commenting = function(new_ = false){
	var comment_text_box = $(".main-comment-area");
	namer = $(".comm-img-name");
	closer = $(".cancel-comm-img");
	preview = ".comm-selected";
	br = $(".com-img > br");
	btn = $(".send-comment")
	inp = $(".sel-comment");
	expand_comment = $(".view-reply");
	del_comment = $(".del-comment");

	closer.on("click",function(){
		$(".sel-comment").val("");
		namer.html("");
		closer.hide();
		br.hide();
		namer.hide();
		$(preview).hide();
		$(preview).attr("src","");
	})
	del_comment.each(function(){
		$(this).click(function(){
			var d_parent = $(this).parent().parent().parent();
			var item = d_parent.attr("data-comment");
			if (item) {
				prompt_del(item,'comment',"Delete comment?",".comment[data-comment='"+item+"']");
			}
		})
	})
	expand_comment.each(function(){
		$(this).click(function(){
			var c_parent = $(this).parent().parent().parent();
			var item = c_parent.attr("data-comment");
			var replies_box = c_parent.children(".comment-replies")
			$(".comment-replies").slideUp('fast');
			if (item) {						
				if (replies_box.is(":visible")) {
					replies_box.slideUp('fast');
				}else{
					replies_box.slideDown('fast');
					load_replies(item);	
				}
			}
		})
	})
	$(".author-img").each(function(){
		$(this).on("error",function(){
			var default_s = base_url+"uploads/system/default_prof_white.png";
			$(this).attr("src",default_s);
		})
	})
	if (new_) {
		btn.click(function(){
			var comment_data = comment_text_box.val();
			var data = new FormData();
			var article = $(".talk-post").attr("data-item");
		    	   
		    if (inp.val()!='') {	    	
				   jQuery.each(jQuery(inp)[0].files, function(i, file) {
				   data.append('file-'+i, file);
		      });
		    };
		    data.append("type","comment");
		    data.append("comment",comment_data);
		    data.append("article",article);	    
		    if (article) {	    	
		    	if (comment_data.length > 0) {
		    		send_comment(data,'comment');
		    	}else{
		    		if (inp.val() != "") {
		    			send_comment(data,'comment');
		    		}else{	    			
		    			notify("Say something...");
		    		}
		    	}
		    }
		})
	}
	inp.on("change",function(e){		
		if ($(this).val() != "" && FileReader) {							
			closer.show();
			$(preview).show();			
			namer.attr("style","display:inline-block;");	
			namer.show();			
			namer.html(e.target.files[0].name);
			br.show();
			//reader($(this),preview,namer);
		}else{			
			namer.html("");
			closer.hide();
			br.hide();
			namer.hide();
			$(preview).hide();
			$(preview).attr("src","");
		}
	});
	$(".reply-to").each(function(){
		$(this).click(function(){			
			util = $(".reply-util");
			util.attr("data-replied","");
			if (!util.is(":visible")) {
				$(".reply-util").show();
			}
			var comment = $(this).parent().parent().parent().attr("data-comment");	
			util.attr("data-replied",comment);
		})
	})		
}
prompt_del = function(item = false,type = false,text = false,removed = false){
	if (item && type) {
		var del_util = $(".del-util");
		var del_info = $(".del-info");
		
		del_util.show();
		if (text) {
			del_info.html(text);
		}
		del_util.attr("data-deleted",item);
		del_util.attr("data-type",type);
		$(".del-item").each(function(){
			$(this).click(function(){
				var item = del_util.attr("data-deleted");
				var type = del_util.attr("data-type");

				if (item && type) {
					if (removed) {
						delete_item(item,type,removed);
					}else{
						delete_item(item,type)
					}
					del_util.attr("data-deleted","");
					del_util.attr("data-type","");
				}
			})
		})
	}
}
replying = function(){
	reply_text_box = $(".comment-area.reply-comment");
	reply_namer = $(".reply-img-name");
	reply_closer = $(".cancel-reply-img");
	reply_preview = ".reply-selected";
	reply_br = $(".reply-img > br");	
	reply_inp = $(".sel-reply");
	
	delete_reply();
	
	reply_closer.on("click",function(){
		$(".sel-reply").val("");
		reply_namer.html("");
		reply_closer.hide();
		reply_br.hide();
		reply_namer.hide();
		$(reply_preview).hide();
		$(reply_preview).attr("src","");
	})
	reply_inp.on("change",function(e){
		if ($(this).val() != "" && FileReader) {			
			reply_closer.show();
			$(reply_preview).show();			
			reply_namer.attr("style","display:inline-block;");			
			reply_namer.html(e.target.files[0].name);
			reply_br.show();
			//reader($(this),preview,namer);
		}else{			
			reply_namer.html("");
			reply_closer.hide();
			reply_br.hide();
			reply_namer.hide();
			$(reply_preview).hide();
			$(reply_preview).attr("src","");
		}
	})	
}
send_rep  = function(){
	$(".send-reply").click(function(){		
		var reply_data = reply_text_box.val();
		var reply_comment = $(".reply-util").attr("data-replied");
		var data = new FormData();
		if (reply_inp.val()!='') {	    	
			   jQuery.each(jQuery(reply_inp)[0].files, function(i, file) {
			   data.append('file-'+i, file);
	      });
	    };
	    data.append("reply",reply_data);	
	    data.append("comment",reply_comment);	
	    data.append("type","reply");	        	    
	    if (reply_comment > 0) {
	    	if (reply_data.length > 0) {
	    		$(".comment[data-item='"+data.comment+"']").prepend('<div class="reply-loader">'+
																		'<div class="reply-loader-in"></div>'+
																	'</div>');
				$(".comment[data-item='"+data.comment+"']").children(".reply-loader").show();	    		
	    		send_comment(data,'reply');
	    	}else{
	    		if(reply_inp.val() != ""){	    			
	    			send_comment(data,'reply');
	    		}else{	    			   		
	    			alert("Say something...")
	    		}
	    	}
	    }
	})
}
delete_reply = function(){
	del_reply = $(".del-reply");

	del_reply.each(function(){
		$(this).click(function(){
			var d_parent = $(this).parent().parent().parent();
			var item = d_parent.attr("data-reply");
			if (item) {
				prompt_del(item,'reply',"Delete reply?","[data-reply='"+item+"']");
			}
		})
	})
}
utils = function(){
	var util_closer = $(".close-util");
	util_closer.click(function(){
		$(this).parent().parent().hide();
		$(".log-tabs").fadeIn("fast");
	})
}
share_with = function(){
	var share_util = $(".share-util");
	share_body = $(".share-body")	
	$(".share-with").each(function(){
		$(this).click(function(){		
			if ($(".talk-post").is(":visible")) {
				var post =  $(".talk-post").attr("data-item");
			}else{
				var post =  $(this).parent().parent().parent().parent().attr("data-post");
			}				
			share_selector = $(".share-selector");
			share_selector.attr("data-shared",post);
			share_util.show();			
		})
	})	
}
share_func = function(){
	$(".select-toggle").click(function(){
		if (share_selector.is(":visible")) {
			share_selector.hide();
			share_body.show();
		}else{
			share_body.hide();
			share_selector.show();
		}
	})
	$(".select-indic").each(function(){
		$(this).click(function(){			
			if ($(this).attr("value") == "true") {
				$(this).attr("value","false");
				$(this).children("i").html("");
				if ($(this).hasClass("user-select")) {
					user_any = 0;
					$(".user-select").each(function(){
						if ($(this).attr("value") == "true") {
							user_any += 1;
						}
					})
					if (user_any > 0) {
						$(".timeline-select").attr("value","false");
						$(".timeline-select").children("i").html("i");
					}					
				}
			}else{	
			  user_any = 0;
			  $(this).attr("value","true");
				$(this).children("i").html("done");
				$(".user-select").each(function(){
					if ($(this).attr("value") == "true") {
						user_any += 1;
					}
				})
				if (user_any == 0) {
					$(".timeline-select").attr("value","true");
					$(".timeline-select").children("i").html("done");
				}				
			}
		})
	})
	$(".timeline-select").click(function(){
		user_any = 0;
		$(".user-select").each(function(){
			if ($(this).attr("value") == "true") {
				user_any += 1;
			}
		})
		if ($(this).attr("value") == "true") {
			$(".select-indic").each(function(){
				$(this).attr("value","true");
				$(this).children("i").html("done");
			})
		}else{
			if (user_any == 0) {
				$(this).attr("value","true");
				$(this).children("i").html("done");
			}else{
				$(this).attr("value","false");
				$(this).children("i").html("i");
			}	
		}
	})
	$(".shere-go").click(function(){
		var followers = [];
		var share_util = $(".share-util");
		var share_body = $(".share-body");
		var share_text = $(".share-say").html();
		var share_selector = $(".share-selector");
		var post = share_selector.attr("data-shared");
		if (get_length(share_text) > 5) {
			user_any = 0;
			$(".user-select").each(function(){
				if ($(this).attr("value") == "true") {
					user_any += 1;
				}
			})			
			if ($(".timeline-select").attr("value") == "true") {
				followers.push("timeline");
			}else{
				if (user_any > 0) {
					$(".user-select").each(function(){
						if ($(this).attr("value") == "true") {
							followers.push($(this).attr("data-follower"))
						}
					})
				}
			}
			if (followers.length > 0) {
				$data = {followers:followers,text:share_text,post:post};
				share_post($data);
			}else{
				notify("Select audiance first.")
			}			
		}else{
			notify("Say something.")
		}
	})
}
$(document).on('mouseover',function() {
    $('.talk-share').click(function(e) {
        e.preventDefault();
        window.open($(this).attr('href'), 'TalkPoint Share Window', 'height=450, width=550, top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
        return false;
    });
});
link_copier = function(){	
	$(".link-copy").each(function(){
		$(this).click(function(e){
			e.preventDefault();
			$(".copy-go").attr("href","");
			var href = $(this).attr("href");
			$(".link-hold").val(href);
			$(".copy-go").attr("href",href);
			$(".copied-text").hide();
			$(".copy-util").show();
		})
	})
	$(".copy-link").click(function(){
		  var clipboardText = "";

	     clipboardText = $(".link-hold").val();
	     l = $(".link-hold");
	     l.removeClass("l-select")	     	    
	     l.select();
       document.execCommand("copy");
       l.addClass("l-select")
       $(".copied-text").show();
       l.blur();
       //alert("Link copied");
	})		
}
let images = document.querySelectorAll(".post-image");
lazyload(images);

toolbars = function(){
	var support_mon_int;
	var toolbar = $(".talk-bottom");
	var menu = $(".u-options");
	var util = $(".popups");
	var title  = $(".tag-title");
	var util_body = $(".popup-body");
	var searcher = $(".searcher");
	var support = $(".support-body");
	$(".close-tool").click(function(){
		if (toolbar.attr('data-toolbar') == "true") {
			toolbar.attr('data-toolbar',"false");
			menu.hide();
			$(this).children("img").attr("src",base_url+"uploads/system/menu.svg");
		}else{
			toolbar.attr('data-toolbar',"true");
			$(this).children("img").attr("src",base_url+"uploads/system/close_white.png");
		}
	})	
	$("body").click(function(e){
		if (!$(e.target).is(".account-tool") && !$(e.target).is(".account-tool > *")) {
			$(".u-options").hide();
		}
	})
	$(".account-tool").click(function(){		
		if (menu.is(":visible")) {
			menu.hide();
		}else{
			menu.show();
		}
	})
	$(".u-option").each(function(){
		$(this).click(function(){
			if (!$(this).hasClass("me") && !$(this).hasClass("country-open")  && !$(this).hasClass("login-href")) {
				location.href = $(this).attr("href")
			}else{
				if ($(this).hasClass("login-href")) {
					$(".login-util").show();
					$(".log-tabs").fadeOut("slow");
				}else{	
					if (!$(this).hasClass("country-open")) {
						var me = $(this).attr("data-me");
						var href = base_url+"writer/"+me;
						location.href = href;
					}
				}
			}
			menu.hide();
		})
	})	
	$(".notif-btn").click(function(){
		$(this).children(".notif-indic").hide();
		title.html("Notifications");
		title.show();
		searcher.hide();
		support.hide();
		$(".notif-body").show();
		$(".search-body").hide();
		read_notif("3");
		util.show();
	})
	$(".pop-close").click(function(){
		$(".popups").hide();
		clearInterval(support_mon_int);
	})
	$(".notif-item").each(function(){
		$(this).click(function(){
			var curr = $(this)
			if ($(this).hasClass("linked")) {
				var item = $(this).attr("data-item");
				var type = $(this).attr("data-type");				
				load_item(item,type);				
				$(this).removeClass("active");	
				util.hide();			
			}else{				
				if (curr.children(".item-body").is(":visible")) {
					$(".notif-item").children(".item-body").hide();										
				}else{
					curr.children(".item-body").show();
				}
			}
			read_notif($(this).attr("data-notif"));			
		})
	})	
	$(".search-tab").each(function(){
		$(this).click(function(){
			var text = $(".searcher");
			if (!$(this).hasClass("active")) {				
				text.attr("placeholder","Search "+$(this).attr("data-name"))
				text.click();
				text.select();
				$(".search-tab.active").removeClass("active");
				$(this).addClass("active");
				if (text.val().length > 2) {
					var type = $(this).attr("data-type");
					search_items(text.val(),type);
				}
			}
		})
	})
	$(".searcher").on("keyup", function(){
		var key = $(this).val();
		var type = $(".search-tab.active").attr("data-type");
		if (key.length > 2) {
			search_items(key,type);
		}
	})
	$(".search-tool").click(function(){
		util.show();
		$(".popup-body").hide();
		$(".search-body").show();		
		title.hide();		
		searcher.show();
		support.hide();		
	})
	$(".t-support").click(function(){
		util.show();
		support.show();
		title.html("TalkPoint Support <button data-position='right' data-tooltip='Rate us' class='tooltipped material-icons launch-rate'>star</button>");
		title.show();
		tool_tip();
		support_mon();
		support_mon_int = setInterval(support_mon, (5* 1000));
		searcher.hide();		
		$(".notif-body").hide();
		$(".search-body").hide();
		$(".launch-rate").each(function(){
			$(this).click(function(){
				$(".star_rate").show();
			})
		})
	})	
	 $(".support-text").on('keypress',function (e) {
       if(e.which == 13) {
          $(".support-btn").click();                               
       }           
  });
	$(".support-btn").click(function(){
		var text = $(".support-text").val();
		if (get_length(text) > 0) {
			ask_support(text);
		}else{
			notify("Type something first.",5000, "warning");
		}
	})
	support_mon = function(){
		load_support();
	}			
}
rater = function(){
	l = 5;
	$(".rater-btn").each(function(){
		$(this).click(function(){
			c = 0;
			for (var i = 1; i <= l; i++) {
				if($(this).is(":nth-child("+i+")")){
					c = i; 
				}
			}
			for (var i = 1; i <= c; i++) {
				$(".rater-btn:nth-child("+i+")").addClass("open");
			}
			for (var i = c + 1; i <= 5; i++) {
				$(".rater-btn:nth-child("+i+")").removeClass("open");
			}
		})
	})
	$(".close-rate").click(function(){
		$(".star_rate").hide();
	})
	$(".send-rating").click(function(){
		var rating = $(".raters").children(".open").length;
		send_rating(rating);		
	})
}
function nthIndex(obj_id){
    parent = $("#"+obj_id).parent();
    index = 1;  
    this_index = 0;  
    parent.children().each(function(){    	    	
    	if ($(this).attr("id") == obj_id) {
    		this_index == index;
    	}else{
    		index ++;
    	}
    })
    return this_index;
}
login = function(){
	$(".login-toggle").click(function(){
		$(".login-util").show();
		$(".log-tabs").fadeOut("slow");		
	})
	$(".login-btn").on("click",function(){		
		log_in();
	})
	$(".pass-in.u-pass").on("keypress",function(e){		
		if (e.which == 13) {
			log_in();
		}
	})
}
log_in = function(){
	var email_ = $(".pass-in.u-mail");
	var password_ = $(".pass-in.u-pass");
	if(get_length(email_.val()) < 2 && password_.val().length < 1){
		notify("Enter your email and password to log in.",5000, "warning");
	}
	if(get_length(email_.val()) < 2 && password_.val().length > 0){
		notify("Enter your email to log in.",5000, "warning");
	}
	if(password_.val().length < 1 && get_length(email_.val())){
		notify("Enter your password to log in.",5000, "warning");
	}
	if (get_length(email_.val()) > 1 && password_.val().length > 0) {
		logger(email_.val(),password_.val());
	}
}
forgot = function(){
	f_util = $(".code-util")
	$("a.forgot").click(function(){
		f_util.show();
		$(".login-util").hide();
		$(".log-tabs").fadeIn("slow");
	})
	$(".send-code").click(function(){
		var mail = $(".rec-email").val();
		if (mail.length > 0) {
			get_code(mail);
			$(".rec-mail_").val(mail)
		}else{
			notify("Enter email to recover password for.",5000, "warning");
		}
	})
	$(".change-password").click(function(){
		var code = $(".rec-code").val();
		var email = $(".rec-mail_").val();
		var new_pass = $(".rec-new").val()
		if (code.length == 5 && new_pass.length < 5) {
			notify("Enter a password ~ atleast 4 characters long.",5000, "error");
		}
		if (code.length != 5 && new_pass.length > 4) {
			notify("Enter a valid recovery code.",5000, "error");
		}
		if (code.length != 5 && new_pass.length < 5) {
			notify("Enter recovery code and new password to proceed.",5000, "error");
		}
		if (!validateInput(email,'email')) {
			notify("Enter valid email address.",5000, "warning");
		}
		if (code.length == 5 && new_pass.length > 4 && validateInput(email,'email')) {
			change_password(code,new_pass,email);
		}
	})
	$(".rec-resend").click(function(){
		$(".rec-change").hide();
		$(".change-code").show();
	})
	$(".enter-by").click(function(){
		$(".rec-change").show();
		$(".change-code").hide();
	})
}
new_post = function(){
	var new_util = $(".new-post");
	var new_btn  = $(".new-article");
	var new_close = $(".new-close");
	var media_switcher = $(".add-attach");	
	var upload_sect = $(".upload-group");
	var link_sect = $(".add-link-group");
	var link_inp = $(".p_link");
	var link_open = $(".open-preview");
	var link_preview = $(".link-preview");
	var link_iframe =$(".new-iframe");
	var upload = $(".rem-file");
	var upload_preview = $(".upload-preview");
	var file_uploader = $(".upload-file");
	var upload_previewer = $(".upload-prev");
	var close_upload = $(".close-upload");
	var upload_rem = $(".rm-upload");
	var submit_post = $(".submit-post");
	var post_col = $(".add-col");
	var post_title = $(".p_title");
	var post_content = CKEDITOR.instances['post_content'];

	new_btn.click(function(){
		new_util.show();
	})
	new_close.click(function(){
		new_util.hide();
	})
	media_switcher.on("change",function(){
		var sel_media = $(this).val();
		if (sel_media == "upload") {
			upload_sect.show();
			link_sect.hide();
			link_inp.val("");
			link_inp.attr("thumbnail", "");
			link_inp.attr("embeded","");
		}else if(sel_media == "youtube"){
			upload_sect.hide();
			link_sect.show();
			upload.val("")
			upload.click();
			file_uploader.val("");
		}else{
			file_uploader.val("");
			upload.click();
			upload_sect.show();
			link_sect.hide();
			link_inp.val("");
			link_inp.attr("embeded","");
			link_inp.attr("thumbnail", "");

			upload_sect.hide();
			link_sect.hide();
			upload.val("")

		}
	})
	process_video = function(url = false){
		if (url) {
			var embed_url = "https://www.youtube.com/embed/";
			var start_trim = 17;
			var id;

			if (url.length == 11) {
				id = url;
				var embeded = embed_url+url;
			}else{
				id = url.substr(17, url.length);
				var embeded = embed_url+id;
			}	
			if (embeded.length == 41) {					
				return {id:id,url:embeded};
			}else{			
				return false;
			}
		}		
		return false;
	}
	$(".close-preview").click(function(){
		$(".link-preview").hide()
	})
	$(".rm-video").click(function(){
		link_inp.val("");
		link_inp.attr("thumbnail", "");
		link_open.hide();
		link_inp.attr("embeded","");
		link_inp.removeClass("vid-okay");
		link_iframe.attr("src","");
		link_preview.hide();
	})
	link_open.click(function(){
		link_preview.show();
		upload_preview.hide();
		if (link_inp.attr("embeded") != "") {
			 link_iframe.attr("src",process_video(link_inp.attr("embeded")).url);
		}
	})	
	link_inp.on("keyup",function(){
		var v = $(this).val();
		if (!(v.length == 11 || v.length == 28)) {
			(this).attr("thumbnail", "");
			$(this).attr("embeded","");
			link_open.hide();
			$(this).removeClass("vid-okay");
		}
	})
	link_inp.on("focusout",function(){
		var val = $(this).val();
		if (val.length == 11 || val.length == 28) {
			var process_  = process_video(val);		
			if (process_) {
				var id = process_.id;
			}else{
				var id = false;
			}
			/*fetch video from YouTube*/
			if (process_ && id && id.length == 11) {
				var url = process_video(val).url;
				var exists = yt_fetch(id);
			}else{
				link_open.hide();				
				notify("Kindly enter a valid youtube video id or url.", 5000, "warning");	
			}
		}else{
			if (val.length != "") {
				notify("Kindly enter a valid youtube video id or url.", 5000, "warning");	
			}
		}
	})
	$(".swap-upload").click(function(){
		file_uploader.click();
	})
	$(".rm-upload,.rem-file").click(function(){
		$(".open-uploaded").hide();
		file_uploader.val("");		
		upload_previewer.hide();
		$(".rem-file").hide();
	})
	file_uploader.on("change",function(){
		if ($(this).val() != "") {
			upload_previewer.show();
			$(".rem-file").show();
			$(".open-uploaded").show();
		}else{
			$(".rem-file").hide();
			$(".open-uploaded").hide();			
			upload_previewer.hide();
		}
	})
	close_upload.click(function(){
		upload_preview.hide();
	})
	$(".open-uploaded").click(function(){
		upload_preview.show();
	})
	submit_post.click(function(){
		var column = post_col.val();		
		var ad_title = post_title.val();
		var ad_content = post_content.getData();
		var data = new FormData();
		var uploaded   = get_uploaded();

		if (uploaded.status) {
			 upload_st = true;
			 var upload_type = uploaded.type;
			 if (upload_type == "none") {
			 		data.append("upload_type","none");
			 }else if(upload_type == "link"){
			 	data.append("upload_type","link");
			 	data.append("link",uploaded.d.id);
			 	data.append("thumbnail",uploaded.d.thumbnail);
			 }else if(upload_type == "image"){
			 	 if (file_uploader.val() != '') {	    	
				   jQuery.each(jQuery(file_uploader)[0].files, function(i, file) {
				    	data.append('file-'+i, file);
		       });
				   data.append("upload_type","image");
		     }else{
		    	upload_st = false;
		    	notify("Invalid upload type",5000,"warning");
		    }
			 }else{
			 	upload_st = false;
			 	notify("Invalid link",5000,"error");
			 }

			 if (upload_st) {
			 	 if (get_length(ad_title) > 3) {
			 	 		data.append("title",ad_title);
			 	 		if (column.length > 0) {
			 	 				data.append("column",column);
			 	 			if (get_length(ad_content) >= 15) {
			 	 					data.append("content",ad_content);
			 	 					post_article(data);
			 	 			}else{
			 	 				notify("Article content should be atleast 15 characters.",5000,"warning");
			 	 			}
			 	 		}else{
			 	 			notify("Select a valid column",5000,"warning");
			 	 		}
			 	 }else{
			 	 	notify("Enter a valid article title",5000,"warning");
			 	 }
			 }else{
			 	notify("Kindly check the attachment")
			 }
		} 
	})
	reset_add = function(){
		link_inp.val("");
		post_title.val("");
		post_content.setData("");
		post_col.prop('selectedIndex',0);
		file_uploader.val("");
	}
}
get_uploaded = function(){
	var link  = $(".p_link"); 
	var image = $(".p_file");
	var m = "";
  var id = "";
	var type = "";
	if (link.val() != "" && image.val() == "") {
		var type = "link";
		var data = process_video(link.val());		
		if (data && $(".p_link").hasClass("vid-okay")) {
			var d = {id:data.id,thumbnail:$(".p_link").attr("thumbnail")};		
			var status = true;
		}else{
			var status = false;
			var m = "invalid";
		}

	}else if(link.val() != "" && image.val() != ""){
		var type = "image";
		var status = true;
	}else if(link.val() == "" && image.val() != ""){
		var type = "image";
		var status = true;
	}else{
		var type = "none";
		var status = true;
	}
	return {d:d,type:type,status:status,m:m};
}
image_viewer = function(s_rc = false){
	var image_loadable = $(".sub-img");
	var viewer = $(".image-viewer");
	var image_viewed = $(".viewed-image");
	var viewer_closer = $(".viewer-closer");
	var prof_image = $(".u-profile")
	post_image_open = $(".post-image.open-post");

	$(".edit-image").each(function(){
		$(this).click(function(){
			var src = $(this).attr("src");
			if (src != "") {
				viewer.show();
				image_viewed.show();			
				image_viewed.attr("src",src);
			}
		})
	})
	image_loadable.each(function(){
		$(this).click(function(){
			var src = $(this).attr("src");
			if (src != "") {
				viewer.show();
				image_viewed.show();			
				image_viewed.attr("src",src);
			}
		})
	})
	prof_image.each(function(){
		$(this).click(function(){
			var src = $(this).attr("src");
			if (src != "") {
				viewer.show();
				image_viewed.show();			
				image_viewed.attr("src",src);
			}
		})
	})
	post_image_open.each(function(){
		$(this).click(function(){
			var src = $(this).attr("src");
			if (src != "") {
				if ($(this).attr("data-yt")) {
					var embed = "https://www.youtube.com/embed/"+$(this).attr("data-yt");
					media_player("yt",embed);
				}
				else{
					viewer.show();
					image_viewed.show();			
					image_viewed.attr("src",src);
				}
			}
		})
	})
	viewer_closer.click(function(){
		viewer.hide();
		image_viewed.hide();			
		image_viewed.attr("src","");
	})
	if (s_rc) {
		viewer.show();
		image_viewed.show();			
		image_viewed.attr("src",s_rc);
	}
	viewer.click(function(e){
		if (!$(e.target).is(".viewed-image")) {
			viewer.hide();
			image_viewed.hide();			
			image_viewed.attr("src","");
		}
	})
}
countries = function(){
	var country_cont = $(".country-tools");
	var country_body = $(".country-list");
	var country_item = $(".country-indic");
	var country_all	 = $(".country-indic-all");
	var save_country = $(".save-country");

	country_item.each(function(){
		$(this).click(function(){
			var isAll  = $(this).hasClass("country-indic-all");
			var val_c  = $(this).attr("value")
			if (isAll) {
				country_item.each(function(){
					$(this).attr("value","true");
					$(this).children("i").removeClass("hidden");
				})
			}else{
				if (val_c == "true") {
					var p_rent = $(".country-list");
					var selected_countries = 0;
					$(this).attr("value","false");
					$(this).children("i").addClass("hidden");
					country_item.each(function(){
						if ($(this).attr("value") == "true" && !$(this).hasClass("country-indic-all")) {							
							selected_countries += 1;
						}
					})						
					if (selected_countries > 0) {						
						country_all.attr("value","false");
					 	country_all.children("i").addClass("hidden");
					}else{
						notify("You have to select atleast 1 country",500,"warning");
						country_item.each(function(){
							$(this).attr("value","true");
							$(this).children("i").removeClass("hidden");
						})						
					}					
				}else{
					var p_rent = $(this).parent().parent();
					$(this).attr("value","true");
					$(this).children("i").removeClass("hidden");
					var selected_countries = 0;
					country_item.each(function(){
						if ($(this).attr("value") == "true" && !$(this).hasClass("country-indic-all")) {
							selected_countries += 1;
						}
					})					
					if (selected_countries != (p_rent.children().length - 2)) {
						country_all.attr("value","true");
						country_all.children("i").removeClass("hidden");
					}else{
						country_all.attr("value","false");
					  country_all.children("i").addClass("hidden");
					}
				}
			}	
		})
	})	
	$(".close-country").click(function(){
		country_cont.hide();
	})	
}
post_loading = function(){
  if (isMobile()) {				
		$(".posts-body , .user-cover").scroll(function(){			
		  	if(($(this).scrollTop()) >= ($(this)[0].scrollHeight - 600)) {
	          if (!$(".posts-loader").is(":visible")) {	          	
	          	var p = $("meta[name='page']").attr("content");
	          	var a = $("meta[name='user-page']").attr("content");
	          	var it = $(".post:last-child").attr("data-post");	          	
	          	if (p != 'user') {
	          		var c  = $(".talk-tab.active").attr("data-column");
	          	}else{
	          		var c  = "user";
	          	}	          	
	          	if (it && c) {
	          		load_column(c,it,p,a);	          		
	          	}	          	
	         }          
	      }else{
	      	$(".posts-loader").hide();
	      }		    
		})	
	}else{
		$(".posts-area").scroll(function(){
		    if((($(this).scrollTop() + $(this).innerHeight()) - 200) >= ($(this)[0].scrollHeight - 200)) {
	         if (!$(".posts-loader").is(":visible")) {
	          	$(".posts-loader").show();
	          	var p = $("meta[name='page']").attr("content");
	          	var a = $("meta[name='user-page']").attr("content");
	          	var it = $(".post:last-child").attr("data-post");	          	
	          	if (p != 'user') {
	          		var c  = $(".talk-tab.active").attr("data-column");
	          	}else{
	          		var c  = "user";
	          	}	          	
	          	if (it && c) {
	          		load_column(c,it,p,a);	          		
	          	}	          	
	         }
	      }else{
	      	$(".posts-loader").hide();
	      }
		})
	}
}
user_page = function(){
	var user_copy = $(".user-share");
	var user_paged = $("meta[name='user-page']").attr("content")
	var prof_editor = $(".prof-editor");
	var prof_div = $(".display_change");
	var prof_input = $("input.prof-change");
	var prof_holder = $(".prof_edit")
	var settings = $(".account-settings")

	user_copy.click(function(){
		var user_uri = base_url+"writer/"+user_paged;	
		$(".copy-go").attr("href","");	
		$(".link-hold").val(user_uri);
		$(".copy-go").attr("href",user_uri);
		$(".copied-text").hide();
		$(".copy-util").show();
	})
	prof_editor.click(function(){
		if (prof_div.is(":visible")) {
			prof_div.hide();
		}else{
			prof_div.show();
		}
	})
	$(".close-change").click(function(){
		prof_div.hide();
		$(".del-select").removeClass("del_showing");
	})
	prof_input.on("change",function(){
		if ($(this).val() == "") {
			var default_src = base_url +"uploads/system/prof_edit.png";
			prof_holder.attr("src",default_src);
		}
	})
	$(".rm-prof").click(function(){
		if ($(".del-select").is(":visible")) {
			$(".del-select").removeClass("del_showing");
		}else{
			$(".del-select").addClass("del_showing");
		}
	})
	$(".s-p").each(function(){
		$(this).click(function(){
			if ($(this).hasClass("s-ban")) {
				var p_type = "banner";
			}else{
				var p_type = "prof";
			}
			if (prof_input.val() != "") {
				var data = new FormData();
				jQuery.each(jQuery(prof_input)[0].files, function(i, file) {
			    	data.append('file-'+i, file);
	       });
				data.append("type",p_type);
				save_prof(data,p_type);
			}else{
				notify("Select an image first");
			}
		})
	})
	$(".del-option").each(function(){
		$(this).click(function(){
			var type = $(this).attr("del-item");
			remove_profs(type);
		})
	})
	$(".close-settings").click(function(){
		settings.hide();
		$(".settings-tab.active").removeClass("active");
		$(".settings-tab:first-child").addClass("active");
		$(".settings-security").hide();
		$(".settings-general").show();
	})
	$(".open-settings").click(function(){
		settings.show();
	})
	$(".settings-tab").each(function(){
		$(this).click(function(){
			$(".settings-tab.active").removeClass("active");
			$(this).addClass("active");
			if ($(this).is(":nth-child(1)")) {
				$(".settings-security").hide();
				$(".settings-general").show();
			}else{
				$(".settings-security").show();
				$(".settings-general").hide();
			}
		})
	})
	$(".account-settings").click(function(e){		
		if ($(e.target).is(".settings-row")) {
			$(".close-settings").click();
		}
	})
	$(".gen-submit").click(function(){
		var first = $(".gen-first").val();
		var last = $(".gen-last").val();
		var g_email = $(".gen-email").val();
		var g_phone = $(".gen-phone").val();
		var g_pass = $(".save-pass").val();
		var g_bio = $(".g-bio").html();

		if ($(".save-pass-div").is(":visible")) {
			if (g_pass != "") {
				if (get_length(first) > 0) {
					if (get_length(last) > 0) {
						if (validateInput(g_email,"email")) {
							data = {bio:g_bio,password:g_pass,f_name:first,l_name:last,email:g_email,phone:g_phone};
								save_details(data);
						}else{
							notify("Enter a valid Email.",5000, "warning");
						}
					}else{
						notify("Enter a valid Last Name.",5000, "warning");
					}
				}else{
					notify("Enter a valid First Name.",5000, "warning");
				}
			}else{
				notify("Enter password to continue.",5000, "error")
			}
		}else{			
			$(".save-pass-div").show();
		}
	})
	$(".save-new").click(function(){
		var cur_pass = $(".gen-pass-curr").val();
		var new_pass = $(".gen-pass-new").val();
		if (cur_pass != new_pass) {
			if (cur_pass.length > 4) {
				if (new_pass.length > 4) {
					var data = {curr:cur_pass,new:new_pass};
					change_pass(data);
				}else{
					notify("Enter your new password : atleast 5 characters long.", 5000, "warning");
				}
			}else{
				notify("Enter your current password : atleast 5 characters long.", 5000, "warning");
			}
		}else{
			notify("Cannot change to current password.", 5000, "warning");
		}
	})	
}
renderer = function(data = false,type = false){
	if (data && type) {
		if (type == "post" || type == "main_post") {
			attached = "";			
			if (data.details.attachment) {
				var attached =  "";
				if (data.details.attachment.type == "image") {
					attached = '<img src="'+data.details.attachment.src+'" alt="post" class="post-image">';
				}else if(data.details.attachment.type == "yt"){
					attached = '<img data-yt="'+data.details.attachment.src+'" src="'+data.details.attachment.meta+'" alt="post" class="post-image yt-image">';
				}else{
					attached = "";
				}				
			}
			if (data.likes) {
				likes = data.likes;
			}else{
				likes = 0;
			}
			if (data.views) {
				views = data.views;
			}else{
				views = 0;
			}
			if (data.comments) {
				comments = data.comments;
			}else{
				comments = 0;
			}	
			if (data.user_liked) {
				l_src = base_url+"uploads/system/liked.png";
			}else{
				l_src = base_url+"uploads/system/like.png";
			}	
			if ($(".talk-tab.active").attr("data-column") == "feeds") {
				var col_class = "";
			}else{
				var col_class = "invinc";
			}
			drop_tools = '<button data-position="left" data-tooltip="Options" data-activates="dropdown_'+data.details.identifier+'" class="material-icons tooltipped dropdown-trigger option-btn">expand_more</button>';
			if (data.owner) {
				drop_tools += '<ul id="dropdown_'+data.details.identifier+'" class="dropdown-content">'+
											'<li class="share-with"><a><i class="material-icons">group</i>Share with</a></li>'+
											'<li class="del-post"><a><i class="material-icons">delete</i>Delete</a></li>'+
										'</ul> ';
			}else{
				if (data.isOnline) {
					drop_tools += '<ul id="dropdown_'+data.details.identifier+'" class="dropdown-content">'+
												'<li class="share-with"><a><i class="material-icons">group</i>Share with</a></li>'+												
											'</ul> ';
				}else{
					drop_tools = '';
				}
			}
			if (data.user_following) {
				f_text = "Unfollow "+data.details.author;
				f_icon = "done";
				f_class = '';
			}else{
				f_text = "Follow "+data.details.author;
				f_icon = "person_add";
				f_class = '';
			}		
			if (type == 'post') {				
				var post = '<div class="post" data-post="'+data.details.identifier+'">'+
								'<div class="post-head">'+
									'<div class="left author-data">'+
										'<a href="'+base_url+"writer/"+data.details.author_clean+'" data-type="user" data-item="'+data.details.author+'" data-position="bottom" data-tooltip="View profile"  class="tooltipped user-link">'+
											'<img tabindex="1" class="author-img" src="'+data.details.author_img+'" alt="author">'+
										'</a>'+
										'<button data-position="bottom" data-tooltip="'+f_text+'"  class="tooltipped '+f_icon+' follow-post material-icons following">'+f_icon+'</button>'+
										'<div class="author-info" style="display: inline-block;">'+											
											'<div style="margin-right:45px;">'+
												'<div class="author-name">'+data.details.author_name+'</div>'+
												'<div class="user-handle">'+data.details.author+'</div>'+
												'<div class="post-date">'+
													data.details.date +'<span class="post-column '+col_class+'">'+data.details.column+'</span>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+
									'<div class="right post-options">'+																			
										 drop_tools +
									'</div>'+
								'</div>'+
								'<div class="post-main">'+
									'<a  href="'+data.details.url+'" class="in-link post-link" data-type="post" data-item="'+data.details.identifier+'">'+
										'<h5 class="post-title">'+data.details.title+'</h5>'+
										attached +
										'<div class="post-content">'+data.details.content_min+'<div class="elipsis"></div></div>'+
									'</a>'+
								'</div>'+
								'<div class="post-tools">'+
									'<div class="left">'+
										'<button data-position="top" data-tooltip="Comments"  class="tooltipped post-tool post_comments">'+
											'<img src="'+base_url+'uploads/system/comments.png" alt="comments">'+
											'<div class="counter">'+comments+'</div>'+
										'</button>'+
										'<button data-position="top" data-tooltip="Likes"  class="liker tooltipped post-tool">'+
											'<img src="'+l_src+'" alt="likes">'+
											'<div class="counter right">'+likes+'</div>'+
										'</button>'+
										'<button data-position="top" data-tooltip="Views"  class="tooltipped post-tool views">'+
											'<img src="'+base_url+'uploads/system/views.png" alt="views">'+
											'<div class="counter right">'+views+'</div>'+
										'</button>'+
									'</div>'+
									'<div class="right">'+
										'<button class="post-tool dropdown-trigger"data-activates="share_'+data.details.identifier+'">'+
											'<img src="'+base_url+'uploads/system/share.png" alt="share">	'+
										'</button>'+
										'<ul id="share_'+data.details.identifier+'" class="dropdown-content">'+
											'<li>'+
												'<a class="talk-share" href=""><img src="'+base_url+'uploads/system/facebook_.png" class="drop-image" alt="facebook">Facebook</a>'+
											'</li>'+
											'<li>'+
												'<a class="talk-share" href=""><img src="'+base_url+'uploads/system/twitter_.png" class="drop-image" alt="twitter">Twitter</a>'+
											'</li>'+
											'<li>'+
												'<a  class="talk-share" href=""><img src="'+base_url+'uploads/system/linkedin_.png" class="drop-image" alt="LinkedIn">LinkedIn</a>'+
											'</li>'+
											'<li>'+
												'<a href="'+data.details.url+'" class="link-copy"><img src="'+base_url+'uploads/system/copy_.png" class="drop-image" alt="copy">Copy Link</a>'+
											'</li>'+
										'</ul>'+
									'</div>'+
								'</div>'+
							'</div>';
				return post;
			}else{
				$(".main-author-name").html(data.details.author_name);
				$(".main-handle").html(data.details.author);
				$(".main-date").html(data.details.date+'<span class="post-column">'+data.details.column+'</span>');
				$(".main-author-img").attr("src",data.details.author_img);
				$(".follow-main").html(f_icon);
				$(".follow-main").attr("data-tooltip",f_text);
				tool_tip();	
				$(".talk-post").attr("data-item",data.details.identifier);
				$(".main-title").html(data.details.title);
				$(".main-content").html(data.details.content);
				$(".main-in").attr("data-item",data.details.author_clean);
				$(".main-in").attr("href",base_url+"writer/"+data.details.author_clean);
				if (data.details.attachment) {
					$(".open-post").show();
					if (data.details.attachment.type == "image") {
						$(".open-post").removeClass("yt-image");
						$(".open-post").attr("src",data.details.attachment.src);
					}else{
						$(".open-post").addClass("yt-image");
						$(".open-post").attr("data-yt",data.details.attachment.src);
						$(".open-post").attr("src",data.details.attachment.meta);
					}			
				}else{
					$(".open-post").hide();
				}
				if (data.ower) {
					post_options = ''+
						'<button data-position="topc" data-tooltip="Options" data-activates="dropdown_post" class="tooltipped material-icons dropdown-trigger option-btn">expand_more</button>'+
						'<!-- Dropdown Structure -->'+
						'<ul id="dropdown_post" class="dropdown-content">'+
							'<li class="share-with"><a><i class="material-icons">share_alt</i>TalkPoint</a></li>'+
							'<li class="del-post"><a><i class="material-icons">delete</i>Delete</a></li>'+
						'</ul>';
				}else{
					post_options = ''+
						'<button data-position="topc" data-tooltip="Options" data-activates="dropdown_post" class="tooltipped material-icons dropdown-trigger option-btn">expand_more</button>'+
						'<!-- Dropdown Structure -->'+
						'<ul id="dropdown_post" class="dropdown-content">'+
							'<li class="share-with"><a><i class="material-icons">share_alt</i>TalkPoint</a></li>'+							
						'</ul>';
				}
				$(".main-post-options").html(post_options);
				drop_down();
				$(".main-comments").children(".counter").html(comments);
				if (data.user_liked) {
					l_src = base_url+"uploads/system/liked.png";
				}else{
					l_src = base_url+"uploads/system/like.png";
				}	
				$(".like-open").children("img").attr("src",l_src);
				$(".like-open").children(".counter").html(likes);
				$(".main-views").children(".counter").html(views);
				$(".main-fb").attr("href",data.article_fb);
				$(".main-twitter").attr("href",data.article_twitter);
				$(".main-linked").attr("href",data.article_linked);
				$(".main-copy").attr("href",data.details.url);
			}
		}else if(type == "article_comments"){						
			if (data.attachment) {
				sub_img = '<div class="sub-img-cover">'+
									'<img src="'+data.attachment.src+'" alt="Image" class="sub-img">'+
								'</div>';
			}else{
				sub_img = "";
			}
			if (data.isOwner) {
				comment_tools = '<div class="comment-tools">'+
													'<button data-position="top" data-tooltip="Reply to comment"  class="tooltipped material-icons comment-tool reply-to">reply</button>'+
													'<button data-position="top" data-tooltip="Delete comment"  class="tooltipped material-icons del-comment comment-tool">delete</button>'+
													'<button data-position="left" data-tooltip="View replies"  class="tooltipped material-icons right comment-tool view-reply">expand_more</button>'+
												'</div>';
			}else{
				if (data.isOnline) {
					comment_tools = '<div class="comment-tools">'+
														'<button data-position="top" data-tooltip="Reply to comment"  class="tooltipped material-icons comment-tool reply-to">reply</button>'+													
														'<button data-position="left" data-tooltip="View replies"  class="tooltipped material-icons right comment-tool view-reply">expand_more</button>'+
													'</div>';
					}else{
						comment_tools = '<div class="comment-tools">'+															
															'<button data-position="left" data-tooltip="View replies"  class="tooltipped material-icons right comment-tool view-reply">expand_more</button>'+
														'</div><br><br>';
					}
			}
			var comment = '<div class="comment" data-comment="'+data.id+'">'+
											'<div class="comment-data">'+
												'<div class="left author-data">'+
													'<a href="'+base_url+'writer/'+data.user.username+'" class="in-link" data-type="user" data-item="'+data.user.username+'">'+
														'<img class="author-img" src="'+data.user.u_profile+'" alt="author">'+
													'</a>'+
													'<div class="right author-info">'+
														'<div class="author-name">@'+data.user.username+'</div>'+
														'<div class="post-date">'+
															data.date+
														'</div>'+
													'</div>'+
												'</div>'+
												comment_tools +
											'</div>'+
												sub_img +
											'<div class="comment-text">'+
												data.content +
											'</div>'+
											'<div class="comment-replies" data-expended="false">'+
												
											'</div>'+
										'</div>';
				return comment;
		}else if(type == "reply"){			
			if (data.attachment) {
				sub_img = '<div class="sub-img-cover">'+
										'<img src="'+data.attachment.src+'" alt="Image" class="sub-img">'+
									'</div>';
			}else{
				sub_img = "";
			}
			if (data.isOwner) {
				comment_tools = '<div class="comment-tools">'+
													'<button data-position="top" data-tooltip="Delete reply"  class="tooltipped material-icons del-reply comment-tool">delete</button>'+
												'</div>';
			}else{
				comment_tools = "<br><br><br>";
			}			
			var reply = '<div class="comment" data-reply="'+data.id+'">'+
											'<div class="comment-data">'+
												'<div class="left author-data">'+
													'<a href="'+base_url+'writer/'+data.user.username+'" class="in-link" data-type="user" data-item="'+data.user.username+'">'+
														'<img class="author-img" src="'+data.user.u_profile+'" alt="author">'+
													'</a>'+
													'<div class="right author-info">'+
														'<div class="author-name">@'+data.user.username+'</div>'+
														'<div class="post-date">'+
															data.date+
														'</div>'+
													'</div>'+
												'</div>'+
												comment_tools +
											'</div>'+
												sub_img +
											'<div class="comment-text">'+
												data.content +
											'</div>'+											
										'</div>';
				return reply;
		}
		else if(type == "hot"){	
		  					
			if (data.position != "#14" && data.position != "#15") {
				if (data.details.details.title.length > 30) {
					data.details.details.title = data.details.details.title.substr(0, 30);
				}
				var hot = '<div class="hot-item">'+
				    					'<a href="'+data.details.details.url+'" class="in-link" data-type="post" data-item="'+data.details.details.identifier+'">'+
												'<div class="left position">'+data.position+'</div>'+
												'<div>'+
													'<span class="hot-item-title post-title">'+data.details.details.title+'</span>'+
													'<div class="hot-item-data">'+data.details.details.author+' . '+data.details.details.column+'</div>'+
													'<div class="hot-item-data">'+data.details.details.date+' . '+data.details.views+' Views</div>'+
												'</div>'+
												'<div class="right launch-trend">'+
													'<i class="material-icons">launch</i>'+
												'</div>'+
											'</a></div>';
				return hot;
			}else{
				return "";
			}
		}
	}
}
media_player =  function(type = false,link = false){
	if (type && link) {
		$(".media-player").show();
		$(".yt-iframe").attr("src","");
		$(".yt-iframe").hide();
		if (type == "yt") {
			$(".yt-iframe").show();
			$(".yt-iframe").attr("src",link);
		}
	}
}
unfollowing = function(){
	var follow_go = $(".unf-go");
	var cancel_unf = $(".unf-cancel");
	var unfollower = $(".unfollower");
	var name = 'Unfollow <span data-removed="" data-user="talkpoint" class="unf-handle">@talkpoint</span>?';
	var name_ = $(".unf-name");
	var handle = $(".unf-handle");

	cancel_unf.click(function(){
		unfollower.hide();
		name_.html(name);
	}) 
	unfollower.click(function(e){
		if ($(e.target).is(".unfollower")) {
			cancel_unf.click();
		}
	})	
	follow_go.click(function(){
		var user = $(".unf-handle").attr("data-user");		
		if (user != "talkpoint" && user != "") {								  
			  	follow_user(user,false,window.removed_user);			  
		}else{
			notify("Invalid user",5000,'warning');
		}
	})
}