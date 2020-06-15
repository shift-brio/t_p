<?php 
  $this->load->library("common");
	$time = common::getTime();
	if (isset($x_data)) {
		$data = json_decode(json_encode($x_data));
		$loaded = $data->loaded;
		$d = $x_data;
	}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="page" content="<?php echo isset($data->page) ? $data->page : "home"; ?>">
	<meta name="active-item" content="false">
	<meta name="user-page" content="<?php echo isset($data->user_page) ? $data->user_page : ""; ?>">
	<base href="<?php echo base_url(); ?>" />
	<meta name="user" content="<?php echo isset($data->username) ? $data->username : ""; ?>">
	<meta name="theme-color" content="#8b0000">		
	<meta name="application-name" content="TalkPoint">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="icon" type="image/gif" href="<?php echo base_url('favicon.ico'); ?>">
	<?php 
		if (isset($data->page) && $data->page == "user") {
			echo '<meta property="og:url"           
																	content="'.base_url("writer/".$data->user).'"/>
						<meta property="og:type"          content="User Profile" />
						<meta property="og:title"         content="@'.$data->user.' on TalkPoint"/>   	         
						<meta property="og:image"         content="'.base_url("uploads/profile/".$data->data->u_photo_name).'"/>
						<meta name="description" content="TalkPoint columnist.">
						<link rel="canonical" 	href="'.base_url("writer/".$data->user).'">';
		}elseif (isset($data->page) && $data->page == "post" && isset($article)){
			$article = $data->article;
   		$post_content = preg_replace('/["]/', "'", $article->details->content);
			$p_content = '';
			if (mb_substr($post_content, 0,3) == "<p>") {
				$p_content = mb_substr($post_content, 3,strlen($post_content));
			}
			if (isset($p_content) && mb_substr($p_content, strlen($p_content) - 4,strlen($p_content)) == "</p>") {
				$p_content = mb_substr($p_content, 0,strlen($p_content) - 4);
			} 
			if (!isset($p_content)) {
				$p_content = $post_content;
			}
			$p_content =  htmlspecialchars_decode($p_content);
			$post_content = htmlspecialchars_decode($post_content);

			$desc = mb_substr(htmlspecialchars_decode($p_content), 0,250)."...";	 
			if (mb_substr($desc, 0,3) == "<p>") {
				$desc = mb_substr(htmlspecialchars_decode($desc), 3,strlen($desc));	 
			}
			//var_dump($article);
			if ($article->details->attachment) {
				$attachment = $article->details->attachment;
				if ($attachment->type == "image") {
					echo '<meta property="og:image"  content="'.$attachment->src.'"/>';
				}
			}
			echo '
				<meta property="og:url"           content="'.$article->details->url.'"/>
				<meta property="og:type"         content="article" />
				<meta property="og:title"        content="'.$article->details->title.'" />
				<meta property="og:description"   content="'.$desc.'">			
			  <link rel="canonical" 	href="'.$article->details->url.'"';
		}else{
			echo '
			<meta name="description" content="Talkpoint. The home of considered opinion on politics, leadership, business, health, life & style, inspiration, real estate and sports. TalkPoint articles are deeply researched, deep and considered.">	
			<meta name="application-name" content="Talkpoint.online">
			<meta name="keywords" content="TalkPoint|Talk|talkpoint.online">';
		}
	 ?>
	 <meta property="fb:app_id" content="2094489307503724">
	<title><?php echo isset($data->title) ? $data->title:"";  ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/materialize/css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/css/main.css').$time; ?>">
	<script type="text/javascript" src="<?php echo base_url('bootstrap/js/jquery.min.js'); ?>"></script> 
	<script type="text/javascript" src="<?php echo base_url('bootstrap/materialize/js/materialize.js'); ?>"></script>
	 <script type="text/javascript" src="<?php echo base_url('bootstrap/js/ckeditor/ckeditor.js'); ?>"></script>
	  <script type="text/javascript" src="<?php echo base_url('bootstrap/js/lazyload.js'); ?>"></script> 
	 <script type="text/javascript" src="<?php echo base_url('bootstrap/js/talkpoint_loader.js').$time; ?>"></script> 
	 <script type="text/javascript" src="<?php echo base_url('bootstrap/js/talkpoint_ui.js').$time; ?>"></script>		
		<!-- <style>
		.example_responsive_1 { width: 320px; height: 100px; }
			@media(min-width: 500px) {
				 .example_responsive_1 { 
				 	width: 468px; height: 60px;
					}
			}
			@media(min-width: 800px) { 
				.example_responsive_1 { 
					width: 728px; 
					height: 90px; 
				}
			} 
		}
			 </style> -->
	 <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	 <!-- <ins class="adsbygoogle example_responsive_1"
	 		     style="display:inline-block; height: 30px;width: 100%;position: fixed;bottom:0;z-index: -1;"
	 		     data-ad-client="ca-pub-5997472197924632"
	 		     data-ad-slot="8XXXXX1">
	 		</ins> -->
	 <script>
		  (adsbygoogle = window.adsbygoogle || []).push({
		    google_ad_client: "ca-pub-5997472197924632",
		    enable_page_level_ads: false
		  });
		</script> 			  
</head>
<body class="talk-body" style="margin-top: 0px !important;position: fixed;top: 0;z-index: 1080;">
<?php echo isset($echoed) ? $echoed:""; ?>	
<audio class="hidden click" src="<?php echo base_url("uploads/system/click.mp3"); ?>"></audio>