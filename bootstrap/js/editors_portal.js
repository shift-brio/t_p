$(document).ready(function(){
	editors_portal();
	if (isMobile()) {
		$(".edit-hinge").show();
	}
	$(".edit-hinge").click(function(){
		toggle_side();
	})
})
var edit_loader = $(".editor-loader");
editors_portal = function(){
	var send_notif_edit = $(".send-notif-edit");
	var delete_edit = $(".del-edit");
	var save_edit = $(".save-edit");
	var edit_publisher = $(".publish-edit");
	var edit_title = $(".edit-in.post-title");
	var edit_content = CKEDITOR.instances['edit-textarea'];
	var editor_comm = $(".editor-comms");
	var edit_notif = $(".comms-box");
	var edit_loader = $(".editor-loader");
	var edit_item = $(".editor-item");

	edit_item.each(function(){
		$(this).click(function(){			
			$(".editor-item.active").removeClass("active");
			$(this).addClass("active");
		})
	})	
	send_notif_edit.click(function(){
		if (editor_comm.is(":visible")) {
			editor_comm.hide();
			$(this).html("comment");
		}else{			
			item = $(".editor-item.active").attr("data-item");
			if (item) {
				edit_loader.show();
				$.ajax({
					url:base_url+"getNotifs",
					type:"POST",
					data:{item:item},
					complete:function(){
						edit_loader.hide();
					},
					success:function(response){
						if (response.status) {											
						  $(".comms-body").html(response.m);
						}else{
							notify(response.m, 5000, "warning");
						}
					},
					error:function(){
						internet_error();
					}
				})
			}
			editor_comm.show();
			$(this).html("close");
		}
	})
	edit_notif.on("keypress",function(e){
		if (e.which == 13) {
			if ($(this).val().length > 3) {
				edit_notify($(this).val());
			}else{
				alert("Type in something first...")
			}
		}
	})
	delete_edit.click(function(){
		var conf = confirm("Delete article ?");
		item = $(".editor-item.active > a").attr("data-item");
		if (conf && item) {				
			delete_edit_(item);
		}
	})
	save_edit.click(function(){
		save_edits(false);
	})
	edit_publisher.click(function(){
		save_edits(true);
	})
	$(".prof-go").click(function(){		
		var u_name = $(".editor-item.active").attr("user");
		load_item(u_name,"user");
	})
	reset_edit = function(){
		edit_title.val("");
		edit_content.setData("");
		$(".edit-image").attr("src","");
		$(".edit-pane").hide();
		$(".info-eye").show();
		$(".go-prof").attr("href","");
	}
	save_edits = function(publish = false){
		var save_title = edit_title.val();
		var save_content = edit_content.getData();
		var edited = $(".editor-item.active > a").attr("data-item");
		var item = $(".editor-item.active").attr("data-item");

		if (publish == true) {
			con = confirm("Publish article?");
		}else{
			con = true;
		}		
		if (save_title.length > 0 && con && save_content.length > 15 && edited && item) {
			edit_loader.show();
			$.ajax({
				url:base_url+"saveEdits",
				type:"POST",
				data:{title:save_title,content:save_content,publish:publish,post:item},
				complete:function(){
					edit_loader.hide();
				},
				success:function(response){
					if (response.status) {
						if (publish) {
							reset_edit();
							notify("Saved and Published");
							var removing = $(".editor-item.active > a[data-item='"+edited+"']").parent();
							removing.slideUp("fast");
							setTimeout(function(){
								removing.remove();
							},1000)
							reset_edit();
						}else{							
							notify("Saved");							
						}
					}else{
						notify(response.m, 5000, "warning");
					}
				},
				error:function(){
					internet_error();
				}
			})
		}else{
			if (!con) {
				notify("Invalid data");
			}
		}
	}
	$(".close-editor").click(function(){
		location.href = base_url;
	})
}
toggle_side = function(){
	if (isMobile()) {
		$(".edit-posts").toggle();
		$(".editor-m").toggle();
		if ($(".edit-posts").is(":visible")) {		
			$(".edit-hinge").html("list")				
		}else{				
			$(".edit-hinge").html("close");									
		}
	}
}
edit_notify = function(text = false){
	item = $(".editor-item.active").attr("data-item");			
	if (text && item) {		
		edit_loader.show();
		$.ajax({
			url:base_url+"editNotify",
			type:"POST",
			data:{text:text,item:item},
			complete:function(){
				edit_loader.hide();
			},
			success:function(response){
				if (response.status) {
					notify("sent");	
					$(".comms-box").val("");				
				  $(".comms-body").html(response.m);
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
delete_edit_ = function(item = false){	
	if (item) {
		edit_loader.show();
		$.ajax({
			url:base_url+"delete",
			type:"POST",
			data:{item:item,type:"post"},
			complete:function(){
				edit_loader.hide();
			},
			success:function(response){
				if (response.status) {
					removed = $(".editor-item.active");
					$(removed).slideUp('fast');
					setTimeout(function(){
						$(removed.remove());
					},250);					
					notify("Deleted");
					reset_edit();
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