<?php 
	$time = $this->config->item("updated_at");
	if ((strtotime(date('d-m-y')) - strtotime($time)) <= (60 * 60 * 24)) {
		$t = "?".time();
	}else{
		$t = "";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="description" content="Talkpoint. The home of considered opinion on politics, leadership, business, health, life & style, inspiration, real estate and sports. TalkPoint articles are deeply researched, deep and considered.">
	<meta name="keywords" content="Talkpoint|Talk|Talkpoint.online">	
	<meta name="application-name" content="Talkpoint.online">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Talkpoint</title> 
	<link rel="icon" type="image/gif" href="<?php echo base_url('favicon.ico'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/bootstrap/css/bootstrap.css'); ?>">		
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/materialize/css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/css/bootstrap.css'); ?>">	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/css/_talk.css').$t; ?>">
	<script type="text/javascript" src="<?php echo base_url('bootstrap/js/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/materialize/js/materialize.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/js/bootstrap.min.js'); ?>"></script>  
    <script type="text/javascript"  src="<?php echo base_url('bootstrap/js/_login.js').$t; ?>"></script>
    <script type="text/javascript"  src="<?php echo base_url('bootstrap/js/_rec.js'); ?>"></script>
     <script type="text/javascript"  src="<?php echo base_url('bootstrap/js/comm.js').$t; ?>"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="<?php echo base_url('bootstrap/js/in.js'); ?>" type="text/javascript"> lang: en_US</script>
    <meta name="theme-color" content="#cd0000">
    <script type="text/javascript">
    $(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
	});
	$(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();       
 	});
 	$(document).ready(function() {
	    $('select').material_select();
	  });
	</script>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	  (adsbygoogle = window.adsbygoogle || []).push({
	    google_ad_client: "ca-pub-7635737580491920",
	    enable_page_level_ads: true
	  });
	</script>
</head>
<body style="width: 100%;margin:0px;">
<?php $this->load->view("alert"); ?>