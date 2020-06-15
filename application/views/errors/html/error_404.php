<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>404 Page Not Found</title>
	<link rel="icon" type="image/gif" href="../favicon.ico">
	<link rel="stylesheet" type="text/css" href="https://www.talkpoint.online/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="https://www.talkpoint.online/bootstrap/bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="https://www.talkpoint.online/bootstrap/bootstrap/css/bootstrap.css">	
	<link rel="stylesheet" type="text/css" href="https://www.talkpoint.online/bootstrap/css/_talk.css">
	<script type="text/javascript" src="https://www.talkpoint.online/bootstrap/js/jquery.min.js"></script>    
    <script type="text/javascript" src="https://www.talkpoint.online/bootstrap/js/bootstrap.min.js"></script>  
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
	<?php
	$base = "https://www.talkpoint.online";
	$CI =& get_instance();	
	$data['n'] = '';	
	if (isset($_SESSION['userid'])) {
		$url = $base."/profiles/".$_SESSION['url'];	
		$prof = $base."/uploads/profile/".$_SESSION['profile'];
		echo '
		<nav class="navbar navbar-inverse  navbar-fixed-top">
		<div class="visible-phone m-nav">
		<img style="display:inline;position:absolute;margin-top:8px;"  class="nav-image" src="'.$base.'/logo.png"> 				
		<div class="nav-right">								
		<img data-trigger="hover" style="width:50px;height:50px;border-radius:100%;float:right;margin-right:10%;"  data-toggle="dropdown"   class="dropdown-toggle user-tool" src="'.$prof.'">			
		<ul  class="dropdown-menu pull-right u-mu" >		   	  
		<li>
		<a  href="'.$url.'"><span class="icon-user"></span> Profile</a>
		</li>
		<li>
		<a   href="'.$base.'/logout" ><span class="icon-lock" ></span> Log out</a>
		</li>
		<li>
		<a   href="'.$base.'/" ><span class="icon-home" ></span> Home</a>
		</li>						   	  
		</ul>				   			
		</div>
		</div>
		<script>
		function c_se(){
			$("#user_result").hide()
		}
		</script>
		<input value="1" class="hidden mouseover">
		<input class="hidden refresh-counter">
		<div class="container-fluid visible-desktop">
		<div class="" id="myNavbar">
		<ul class="nav navbar-nav navbar-left">
		<img  class="nav-image" src="'.$base.'/logo.png">
		</ul>	
		</div>	
		<ul class="nav navbar-nav navbar-center visible-desktop">						
		</ul>	
		<ul class="nav navbar-nav navbar-right">		
		<li class="user-tools" >
		<img  value="btn-upop" onmouseover="toggle_(this)"  data-trigger="hover"  data-placement="right" data-toggle="dropdown"   class="btn-upop dropdown-toggle user-tools" src="'.$prof.'">
		<ul class="dropdown-menu pull-right">
		<li><a href="'.$url.'"><span class="icon-user"></span> Profile</a></li>
		<li><a href="'.$base.'/logout" ><span class="icon-lock"></span> Log out</a></li>
		<li>
		<a   href="'.$base.'/" ><span class="icon-home" ></span> Home</a></li>	  
		</ul>	
		</li>
		</ul>
		</div>
		</nav> 
		';
	} 
	?>
	<div id="container">
		<h1><?php echo $heading.'<h1 style="display:inline;">Talk Point</h1>'; ?></h1>
		<?php echo'<p style="margin-left:1%;">The page you requested  is not available. Check the url submitted.</p>'; ?>		
	</div>
	<style>
		1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #fafafa;
	box-shadow: 0 0 8px #D0D0D0;
	margin-top: 80px;
	padding: 10px;
	background: #fff;
}

p {
	margin: 12px 15px 12px 15px;
}
::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }
	</style>
</body>
</html>