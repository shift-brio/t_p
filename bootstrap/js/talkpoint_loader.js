const columns = ["talkpoint",'feeds','politics','money','health','education','life','sports','inspiration','estate','people','user'];
const base_url = $("base").attr("href");
const curr_page = $("meta[name='page']").attr('content');
const apk = "AIzaSyBlZL-wwwJSAh4d6cV6fvtimrpRZlueJSM";

init_vars = function(){
		
}

function load_column(column = false, start_id = false,page = false ,user  =false, activate = false){	
	if (column && $.inArray(column,columns) && column != 'talkpoint') {						
		if (start_id) {
			data = {col:column,start_id:start_id,user:user,page:page};
		}else{
			data = {page:page,col:column,user:user};
		}
		if (!activate && !window.isRefreshing) {
			$(".posts-loader").show();
		}else{
			if (!window.isRefreshing) {
				loading(true);
			}			
		}
		$.ajax({
			url:base_url+"api/getColumn",
			type:"POST",
			data:data,
			complete:function(){
				loading(false);
				$(".swiper").hide();
				window.isRefreshing = false;
				$(".posts-loader").hide();
			},
			success:function(response){
				if (activate) {
					$(".column.active").removeClass("active");
					activate.addClass("active");
					$(".posts-area").html("");
				}				
				if (response.status) {
					if (response.m.length > 0) {
						$posts = [];	
						var html = $(".posts-area").html(); 
						$(".posts-area").html(html);					
						$(".posts-area").append(response.m);
						post_inits();									
					}else{
						$(".posts-area").append('<div class="center all-posts flow-text">All articles</div>');
					}
					$(".dropdown-content").hide();
					drop_down();
					$(".author-img").each(function(){
							$(this).on("error",function(){
								var default_s = base_url+"uploads/system/default_prof_white.png";
								$(this).attr("src",default_s);
							})
					})	
					linker();
					share_with();
					post_del();
					clicker();
				}else{
					notify(response.m, 5000,"warning");
				}
			},
			error:function(){
				internet_error();
			}

		})
	}
}
follow_user =  function(target = false, item_loader = false,v = false){
	if (target) {
		if (item_loader) {
			item_loader.show();
		}		
		$.ajax({
			url:base_url+"follow",
			type:"POST",
			data:{target:target},
			complete:function(){
				if (item_loader) {
					item_loader.hide();
				}
				var unfollower = $(".unfollower");
				var name = 'Unfollow <span data-removed="" data-user="talkpoint" class="unf-handle">@talkpoint</span>?';
				var name_ = $(".unf-name");
				unfollower.hide();
			  name_.html(name);
			},
			success:function(response){
				if (response.status) {					
					if (response.m.status == "followed") {
						v.html("done")
						notify("Following @"+response.m.username);
						v.attr("data-tooltip","Unfollow @"+response.m.username)
					}else{
						notify("You unfollowed @"+response.m.username);
						v.html("person_add")
						v.attr("data-tooltip","Follow @"+response.m.username)						
					}
					tool_tip();
				}else{
					notify(response.m,5000,"warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
load_item = function(item = false,type = false, loader = true){
	if (item && type && !window.item_loading) {
		window.item_loading = true;
		if (loader) {
			loading(true);
		}
		if (type == "editor") {
			$(".editor-loader").show();
		}
		$.ajax({
			url:base_url+"api/getItem",
			type:"POST",
			data:{item:item, type:type,comments:true},
			complete:function(){
				if (loader) {
					loading(false);
				}
				$(".swiper").hide();
				window.isRefreshing = false;
				window.item_loading = false;
				$(".editor-loader").hide();
			},
			success:function(response){
				if (response.status) {
					$(".dropdown-content").hide();
					if (type == "post") {
						renderer(response.m,'main_post');
						share_with();
						if (response.m.article_comments && response.m.article_comments.length > 0) {
							$(".no-comments").hide();
							$(".main-comments-body").html("")
							var comments = '';
							for (var i = 0; i < response.m.article_comments.length; i++) {
								comments += renderer(response.m.article_comments[i],'article_comments');								
							}
							if (comments !='') {
								$(".main-comments-body").html(comments);
							}else{
								$(".main-comments-body").html('<div class="no-comments center">Be the first to comment.</div>');
							}							
							commenting();
							image_viewer();														
						}else{
							$(".main-comments-body").html('<div class="no-comments center">Be the first to comment.</div>');
						}
						post_opener(true);
					}else if(type == "user"){

					}else if(type == "editor"){						
						post = response.m.details;
						$(".info-eye").hide();
						$(".edit-pane").show();
						$(".edit-in.post-title").val(post.title);
						$(".go-prof").attr("href",base_url+"writer/"+post.author_clean);
						if (post.attachment) {
							$(".edit-image").removeClass("yt-image")
							$(".edit-image").show();
							if (post.attachment.type == "image") {
								$(".edit-image").attr("src",post.attachment.src);
							}else if(post.attachment.type == "yt"){
								$(".edit-image").attr("src",post.attachment.meta);
								$(".edit-image").attr("data-yt",post.attachment.src);
								$(".edit-image").addClass("yt-image")
								$(".yt-image").click(function(){
									var embed = "https://www.youtube.com/embed/"+$(this).attr("data-yt");
									media_player("yt",embed);
								})
							}
						}else{
							$(".edit-image").hide();
						}												
						CKEDITOR.instances['edit-textarea'].setData(post.content);
						setTimeout(function(){
							toggle_side();
						},150);
					}
					$(".log-tabs").fadeIn("slow");
					$(".main-post-footer").fadeIn("slow");
				}else{
					notify(response.m,5000,"warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
like_post = function(article = false, post_open = false){
	if (article) {
		var p_target = $("[data-post='"+article+"']");		
		btn = p_target.children(".post-tools").children(".left").children(".liker");
		comments = p_target.children(".post-tools").children(".left").children(".post_comments"); 
		views = p_target.children(".post-tools").children(".left").children(".views"); 
		if (!post_open) {			
			btn_ = false;			
		}else{			
			btn_ = $(".like-open");			
			views_ = $(".main-views");
			comments_ = $(".main-comments");
		}			
		var liked = base_url+"uploads/system/liked.png";
		var like = base_url+"uploads/system/like.png";
		$.ajax({
			url:base_url+"likePost",
			type:"POST",
			data:{article:article},
			success:function(response){
				if (response.status) {
					views.children(".counter").html(response.m.view_count);
					comments.children(".counter").html(response.m.comments_count);
					if (btn_) {
						views_.children(".counter").html(response.m.view_count);
						comments_.children(".counter").html(response.m.comments_count);
					}
					if (response.m.kind == "liked") {
						btn.children("img").attr("src",liked);
						btn.children(".counter").html(response.m.likes_count);
						if (btn_) {
							btn_.children("img").attr("src",liked);
							btn_.children(".counter").html(response.m.likes_count);
						}
						notify("Liked")
					}else{
						if (btn_) {
							btn_.children("img").attr("src",like);
							btn_.children(".counter").html(response.m.likes_count);
						}
						btn.children(".counter").html(response.m.likes_count);
						btn.children("img").attr("src",like);
						notify("Unliked")
					}
				}else{
					notify(response.m,5000,"warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
load_trends = function(type = false){
	if (type) {
		var cont = $(".hot-posts-body");
		loading(true);
		$.ajax({
			url:base_url+"api/getHot",
			type:"POST",
			data:{type:type},
			complete:function(){
				loading(false);
				if (type == "popular") {
					$.ajax({
						url:base_url+"api/propPop"
					})
				}
			},
			success:function(response){
				if (response.status) {
					trends = '';
					if (response.m.length > 0) {
						for (var i = 0; i < response.m.length; i++) {
							trends += renderer(response.m[i],'hot');
						}
						$(".hot-posts-body").html(trends);
					}else{
						$(".hot-posts-body").html('<div class="center flow-text">No articles</div>');
					}
					linker();
				}else{
					$(".hot-posts-body").html('<div class="center flow-text">No articles</div>');
					notify(response.m,5000,"warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
send_comment = function(d = false,type = 'comment'){
	if (d) {
		loading(true);
		$.ajax({
			url:base_url+"comment",
			type:"POST",
			data:d,	
			contentType: false,       
      cache: false,             
      processData:false,		
			complete:function(){
				loading(false);
			},
			success:function(response){
				if (response.status) {
					$(".reply-util").attr("data-replied","");
					if (d.type == "comment") {
						comments ="";
						for (var i = 0; i < response.m.length; i++) {
								comments += renderer(response.m[i],'article_comments');								
						}						
						if (comments !='') {
							$(".main-comments-body").html(comments);
						}else{
							$(".main-comments-body").html('<div class="no-comments center">Be the first to comment.</div>');
						}	
						commenting()
						image_viewer();
						$(".main-comment-area").val("");
						$(".cancel-comm-img").click();
					}else{
						replies ="";
						p = $(".comment[data-item='"+d.comment+"']").children(".comment-replies");
						p.children(".reply-loader-in").hide();
						$(".comment-area.reply-comment").val("");
						$(".cancel-reply-img").click();
						$(".reply-util").hide();						
						//load_replies(d.comment)						
						image_viewer();
						$(".main-comment-area").val("");
						$(".cancel-comm-img").click();
					}
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
load_replies = function(item = false){
	if (item) {
		var p = $(".comment[data-comment='"+item+"']");
		p.children(".comment-replies").html("");
		p.children(".comment-replies").prepend('<div class="reply-loader">'+
																		'<div class="reply-loader-in"></div>'+
																	'</div>');
		p.children(".comment-replies").children(".reply-loader").show();		
		$.ajax({
			url:base_url+"api/loadReplies",
			type:"POST",
			data:{comment:item},
			complete:function(){
				p.children(".comment-replies").children(".reply-loader").hide();		
			},
			success:function(response){
				if (response.status) {		
				var target = $(".main-comments > .comment[data-comment='"+item+"']").children(".comment-replies");			
					if (response.m.data.length > 0) {												
						var replies = " ";						
						target.html("");						
						for (var i = 0; i < response.m.data.length; i++) {
							replies  += renderer(response.m.data[i],'reply');
						}						
						target.html(replies);
						replying();
						image_viewer();
						$(".author-img").each(function(){
							$(this).on("error",function(){
								var default_s = base_url+"uploads/system/default_prof_white.png";
								$(this).attr("src",default_s);
							})
						})						
					}else{

						target.html('<div class="no-comments center">Be the first to reply.</div>');
					}
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
delete_item = function(item = false,type = false, removed = false){
	if (item && type) {
		loading(true);		
		$.ajax({
			url:base_url+"delete",
			type:"POST",
			data:{item:item, type:type},
			complete:function(){
				loading(false);
				$(".del-util").hide();
			},
			success:function(response){
				if (response.status) {
					if (removed) {
						$(removed).slideUp('fast');
						setTimeout(function(){
							$(removed.remove());
						},250);
					}
					if (type == "post" && $(".talk-post").is(":visible")) {
						$(".talk-post").hide();
					}
					notify("Deleted");
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
share_post = function(data = false){
	if (data) {
		loading(true);
		$.ajax({
			url:base_url+"shareWith",
			type:"POST",
			data:data,
			complete:function(){
				loading(false);
			},
			success:function(response){
				if (response.status) {
					$(".share-util").hide();					
					$(".share-say").html("");
					notify("Article shared.");
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}		
		})
	}
}
read_notif = function(notif = false){
	if (notif) {		
		$.ajax({
			url:base_url+"readNotif",
			type:"POST",
			data:{notif:notif},
			complete:function(){
				loading(false);
			},				
		})
	}
}
search_items = function(text = false,type = "articles"){
	if (text) {
		$(".search-loader-in").show();
		$(".search-inf").removeClass("error");
		$(".search-data").html();
		$(".search-inf").html("Searching...")
		$.ajax({
			url:base_url+"searcher",
			type:"POST",
			data:{key:text,type:type},
			complete:function(){
				$(".search-loader-in").hide();
			},
			success:function(response){
				if (response.status) {					
						$(".search-data").html(response.m);					
						$(".item-img").each(function(){
							$(this).on("error",function(){
								var default_s = base_url+"uploads/system/default_prof_white.png";
								$(this).attr("src",default_s);
							})
						})
						$(".search-item > a").each(function(){
							$(this).click(function(e){			
								e.preventDefault();
								var type = "post";
								var item = $(this).attr("data-item");													
								load_item(item,type);
							})		
						})						
				}else{
					$(".search-inf").addClass("error");
					$(".search-inf").html(response.m, 5000, "warning");
				}
			},
			error:function(){
				$(".search-inf").addClass("error");
				$(".search-inf").html("Internet error, check your connection.")
			}			
		})
	}
}
ask_support = function(text = false){
	if (text) {		
		loading(true);
		$.ajax({
			url:base_url+"askSupport",
			type:"POST",
			data:{message:text},
			complete:function(){
				loading(false);
			},
			success:function(response){
				if (response.status) {
					/*results here*/
					if (response.m != 'none') {
						$(".support-data").html(response.m);
					}
					$(".support-text").val("");						
				}else{					
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				notify("Internet error, check your connection.",5000, "error");
			}			
		})
	}
}
load_support = function(user = false){
	$.ajax({
		url:base_url+"loadSupport",
		type:"POST",	
		data:{user:user},
		success:function(response){
			if (response.m != 'none') {
				$(".support-data").html(response.m);
			}			
		}		
	})
}
send_rating = function(rating = false){
	if (rating && rating > 0 && rating < 6) {
		$.ajax({
			url:base_url+"rateUs",
			type:"POST",
			data:{rating:rating},
			success:function(response){
				if (response.status) {
					$(".rating-inf").html(response.m);
					$(".send-rating").remove();					
					notify("Your feedback is valued.");
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
logger = function(email = false, password = false){
	if (email && password) {
		loading(true);
		$.ajax({
			url:base_url+"logger",
			type:"POST",
			data:{email:email,password:password},
			complete:function(){
				loading(false);
			},
			success:function(response){
				if (response.status) {
					location.reload();
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
get_code = function(email = false){
	if (email) {
		loading(true);
		$.ajax({
			url:base_url+"getCode",
			type:"POST",
			data:{email:email},
			complete:function(){
				loading(false);
			},
			success:function(response){
				if (response.status) {
					notify("Recovery code has been sent. Check your email after a few minutes.")
					$(".change-code").hide();
					$(".rec-change").show();	
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
change_password = function(code = false,password = false,email = false){
	if (code && password && validateInput(email,'email')) {		
		loading(true);		
		$.ajax({
			url:base_url+"recoverAccount",
			type:"POST",
			data:{code:code,password:password,email:email},
			complete:function(){
				loading(false);
			},
			success:function(response){
				if (response.status) {
					notify("Password changed successfully.");
					$(".login-util").show();
					$(".code-util").hide();
				}else{
					notify(response.m, 5000, "warning");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
yt_fetch = function(id = false){
	if (id && id.length == 11) {
		var prev = $(".open-preview > img");		
		var curr_img = base_url+"uploads/system/preview.png";
		l_in  = $(".p_link");		
		prev.attr("src",base_url+"uploads/system/ajax-posts.gif");		
		prev.parent().show();
		prev.parent().css("height","16px");
		prev.parent().css("width","16px");
		$.ajax({
	      url:"https://www.googleapis.com/youtube/v3/videos?id="+id+"&"+
	      			"key="+apk+"&part=status,snippet",
	      type:"GET",
	      complete:function(){	
	      	prev.attr("src",curr_img);      	
	      	prev.parent().css("height","35px");
					prev.parent().css("width","35px");			
	      },
	      success:function(response){
	      	if (response.items.length > 0) {
	      		var emb = response.items[0].status.embeddable;
		      	var privacy  = response.items[0].status.privacyStatus;
		      	var upload_status = response.items[0].status.uploadStatus;
		      	var thumbnail = response.items[0].snippet.thumbnails.standard.url;

		      	if (emb) {
		      		if (privacy == "public") {
		      			if (upload_status == "processed") {
		      				$(".p_link").attr("embeded", id);
		      				$(".p_link").attr("thumbnail", thumbnail);
		      				$(".p_link").addClass("vid-okay");
		      			}
		      		}else{
		      				$(".p_link").attr("thumbnail", "");
		      				$(".p_link").removeClass("vid-okay");
		      			  prev.parent().hide();
		      			 	notify("The video has not been made public.",5000,"error")
		      			  l_in.val("");
		      		}
		      	}else{
		      		$(".p_link").attr("thumbnail", "");
		      		$(".p_link").removeClass("vid-okay");
		      		l_in.val("");
		      		prev.parent().hide();
		      		notify("The video cannot be embedded.",5000,"error")
		      	}
	      	}else{	
	      		l_in.val("");      			      		
	      		prev.parent().hide();
		      	notify("The video is not available.",5000,"error")
	      	}
	      },
	      error:function(){
	      	l_in.val("");
	      	prev.parent().hide();
	      	internet_error();
	      }
		})
	}
}
post_article = function(data = false){
	if (data) {
		loading(true);
		$.ajax({
			url:base_url+"postArticle",
			type:"POST",
			data:data,
			contentType: false,       
      cache: false,             
      processData:false,
      complete:function(){
      	loading(false);
      },
      success:function(response){
      	if (response.status) {
      		$(".new-post").hide();
      		reset_add();
      		notify("Your article has been submitted for editing, it will be published in due time.")
      	}else{
      		notify(response.m, 5000, "error");
      	}
      },
      error:function(){
      	internet_error();
      }
		})
	}else{
		log_console({errorType:"runtime"});
	}
}
load_countries = function(){
	$(".country-load-in").show();
	$.ajax({
		url:base_url+"api/loadCountries",
		type:"POST",		
		complete:function(){
			$(".country-load-in").hide();
		},
		success:function(response){
			if (response.status) {				
				$(".countries-main").html(response.m);
				if (response.all) {
					$(".country-indic-all").attr("value","true");
					$(".country-indic-all").children("i").removeClass("hidden");
				}else{
					$(".country-indic-all").attr("value","false");
					$(".country-indic-all").children("i").addClass("hidden");
				}
				countries();
			}else{
				notify(response.m, 5000, "error");
			}
		},
		error:function(){
			internet_error();
		}
	})
}
save_countries = function(countries = false){	
	if (countries && countries.length > 0 || countries == "all") {		
		$(".country-load-in").show();
		$.ajax({
			url:base_url+"saveCountries",
			type:"POST",
			data:{countries:countries},
			complete:function(){
				$(".country-load-in").hide();
			},
			success:function(response){
				if (response.status) {
					notify("Saved");
					$(".country-tools").hide();
					$(".talk-tab.active").click();
				}else{
					notify(response.m, 5000, "error");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}else{
		notify("You must select atleast 1 country.",2000,'warning');
	}
}
remove_profs = function(type = false){
	if (type) {
		loading(true);
		$.ajax({
			url:base_url+"removeProfs",
			type:"POST",
			data:{type:type},
			complete:function(){
				loading(false);
			},
			success:function(response){
				if (response.status) {					
					if (type == "banner") {
						var src = base_url+"uploads/system/banner.png";	
						$(".user-profile").attr("style","background-image:url("+src+");");											
					}else if(type == "prof"){
						var src = base_url+"uploads/profile/default_prof_white.png";
						$(".u-profile").attr("src",src);
					}
				}else{
					notify(response.m, 5000, "error");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
save_details = function(data = false){
	if (data) {
		$(".settings-load-inner").show();
		$.ajax({
			url:base_url+"saveDetails",
			type:"POST",
			data:data,
			complete:function(){
				$(".settings-load-inner").hide();
			},
			success:function(response){
				if (response.status) {		
					notify("Details saved.")	
					$(".account-settings").hide();	
					location.reload();
				}else{
					notify(response.m, 5000, "error");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
change_pass = function(data){
	if (data) {
		$(".settings-load-inner").show();
		$.ajax({
			url:base_url+"changePass",
			type:"POST",
			data:data,
			complete:function(){
				$(".settings-load-inner").hide();
			},
			success:function(response){
				if (response.status) {		
					notify("Password changed.")	
					$(".gen-pass-curr").val("");
					$(".gen-pass-new").val("");										
				}else{
					notify(response.m, 5000, "error");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
save_prof = function(data = false, type = "prof"){
	if (data) {
		loading(true);
		$.ajax({
			url:base_url+"saveProf",
			type:"POST",
			data:data,
			contentType: false,       
      cache: false,             
      processData:false,
			complete:function(){
				loading(false)
			},
			success:function(response){
				if (response.status) {		
						if (type == "banner") {
							var src = base_url+"uploads/banner/"+response.m.data;							
							$(".user-profile").attr("style","background-image:url("+src+");");
						}else if(type == "prof"){
							var src = base_url+"uploads/profile/"+response.m.data;
							$(".u-profile").attr("src",src);														
						}
						$(".prof-change").val("");
						var default_s = base_url+"uploads/system/prof_edit.png";
						$(".prof_edit").attr("src",default_s);											
				}else{
					notify(response.m, 5000, "error");
				}
			},
			error:function(){
				internet_error();
			}
		})
	}
}
set_col = function(col = false){
	if (col) {		
		$.ajax({
			url:base_url+"api/setCol",
			type:"POST",
			data:{col:col},						
		})
	}
}
user_time = function(col = false){
	$.ajax({
		url:base_url+"user/userTime",
		type:"POST",									
	})
}
load_comments = function(post = false, start_id = false){
	if (post) {
		if (start_id) {
			data = {identifier:post,start_id:start_id}
		}else{
			data = {identifier:post}
		}
		loading(true);		
		$.ajax({
			url:base_url+"api/loadComments",
			type:"POST",
			data:data,
			complete:function(){				
					loading(false);							
			},
			success:function(response){				
				if (response.status) {					
					if (response.m.length > 0) {						
						$(".no-comments").hide();
						comments = '';
						var c_s = $(".main-comments-body").html();
						$(".main-comments-body").html("");														
						for (var i = 0; i < response.m.length; i++) {							
							comments += renderer(response.m[i],'article_comments');								
						}						
						if (comments !='') {
							$(".main-comments-body").html(c_s+comments);							
						}							
						commenting();
						image_viewer();
					}else{
						$(".main-comments-body").append('<div style="width:50%;margin-left:25%;" class="all-cs all-posts center">All comments</div>');	
					}						
				}else{
					$(".main-comments-body").append('<div style="width:50%;margin-left:25%;"  class="all-cs all-posts center">All comments</div>');	
				}	
				$(".all-cs:not(:last-child)").remove();			
			},
			error:function(){
				internet_error();
			}
		})
	}
}
user_time();
setInterval(user_time, (10* 1000));