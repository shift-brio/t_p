function transfer(id){
    var id_=id.value;
    $("#delete_btn").val(id_);
 }
function deletePost(id){
    var id_=id.value;
    var url_=$("#btn_didmiss").val();
    $("#loader_delete").removeClass('hidden');
    $.ajax({
            url:url_, 
            type:'POST',
            data:{identifier:id_}, 
            success: function (result) {
                $("#deletePost").modal('hide');
                ajaxRefresh();
                $("#loader_delete").addClass('hidden');
                ocation.reload();
            }
        })
    };
function transferpost(id){
    var id_=id.value;
    $("#repost_btn").val(id_);
}
function repostPost(id){
    var id_=id.value;
    var url_=$("#btn_didmiss_repost").val();
    $("#loader_repost").removeClass('hidden');
    $.ajax({
            url:url_, 
            type:'POST',
            data:{identifier:id_}, 
            success: function (result) {
                $("#repostPost").modal('hide');
                ajaxRefresh();
                $("#loader_repost").addClass('hidden');
                ocation.reload();
            }
        })
    };
function submitComment(id)
 {
    var id_=id.value;
    var identifier=$("#post_id_comment"+id_).val();   
    var url=$("#url_submit_comment").val();
    var content=$("#comment_textarea"+id_).val();
    if (content!='') {
        $("#loader_image_comm").removeClass( "hidden" );
    $.ajax({
        url:url,
        type:'POST',
        data:{comment_data : content , identifier : identifier},
        success:function (data) {
           getComments(identifier);
           $("#comment_textarea"+id_).val("");
           $("#loader_image_comm").addClass( "hidden" );
           ajaxRefresh();
           ajaxCall()              
        }
    });
   };
 }
 function getComments(id) {
        var div= "add_post_div";
        var p_id=id.value;                
        var main_div=div+p_id;
        var url=$("#get_").val();        
        $.ajax({                
                url:url,
                type:'POST',
                data:{post:p_id},
                success:function (data) {
                 $("#"+main_div).html(data);                 
                }
        });
 }
 $(document).ready(function () {
        $("#search_box_post").on('keyup',function () {
            var key = $(this).val();
            $("#loader_image_post").removeClass( "hidden" );
            $.ajax({
                url:$("#post_search_url").val(),
                type:'GET',
                data:'keyword='+key.split("%"),
                success:function (data) {
                    $("#post_result").html(data);
                    $("#post_result").slideDown('fast');
                    $("#loader_image_post").addClass( "hidden" );
                }
            });
        });
    });

