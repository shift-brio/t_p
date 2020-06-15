<nav class="talk-nav" data-expanded="false" style="margin-top: 0px !important;">		
	<div class="loader">
		<div class="loader-indic">
			
		</div>
		<button class="right cancel-load material-icons">close</button>
	</div>
	<img style="display: none;" src="<?php echo base_url("uploads/system/swiper.svg"); ?>" alt="swipe" class="swiper">
	<div class="talk-nav-left">
		<a href="<?php echo base_url(); ?>">
			<img  src="<?php echo base_url("logo.png"); ?>" alt="TalkPoint Logo" class="talk-logo">
			<button class="home-btn clickable material-icons">arrow_back</button>
		</a>
	</div>
	<div class="talk-nav-cols">
		<button class="close-cols material-icons">close</button>
		<?php 			
			if (isset($_SESSION['user'])) {
				echo '
					<a data-column="feeds" class="column talk-tab '.(isset($_SESSION['column'])  && $_SESSION['column'] == 'feeds' ? 'active':'').'" href="'.(base_url("feeds")).'">Home</a>
				';
			}
		 ?>
		<a data-column="politics" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'politics'){echo "active";} ?>" href="<?php echo base_url("politics"); ?>">Politics & Leadership</a>
		<a data-column="money" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'money'){echo "active";} ?>" href="<?php echo base_url("money"); ?>">Money & Business</a>
		<a data-column="health" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'health'){echo "active";} ?>" href="<?php echo base_url("health"); ?>">Health</a>
		<a data-column="education" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'education'){echo "active";} ?>" href="<?php echo base_url("education"); ?>">Education</a>
		<a data-column="life" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'life'){echo "active";} ?>" href="<?php echo base_url("life"); ?>">Life & Style</a>
		<a data-column="sports" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'sports'){echo "active";} ?>" href="<?php echo base_url("sports"); ?>">Sports</a>
		<a data-column="inspiration" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'inspiration'){echo "active";} ?>" href="<?php echo base_url("inspiration"); ?>">Inspiration</a>
		<a data-column="estate" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'estate'){echo "active";} ?>" href="<?php echo base_url("estate"); ?>">Real Estate</a>
		<a data-column="videos" class="column talk-tab <?php if(isset($_SESSION['column'])  && $_SESSION['column'] == 'videos'){echo "active";} ?>" href="<?php echo base_url("people"); ?>">Videos</a>
	</div>
	<div class="talk-nav-tools right">	
		<button data-type="trends" data-position="left" data-tooltip="Trending articles"  class="tooltipped nav-tool nav-ex">
			<img src="<?php echo base_url("uploads/system/trending_white.png"); ?>" alt="trending">
		</button>
		<button data-type="popular" data-position="left" data-tooltip="Most popular articles"  class="tooltipped nav-tool nav-ex">
			<img src="<?php echo base_url("uploads/system/popular_white.png"); ?>" alt="popular">
		</button>		
		<?php
			echo isset($_SESSION['user']) ? '<button data-position="left" data-tooltip="New article"  class="tooltipped material-icons new-article nav-tool">edit</button>' : "";			
		 ?>
		<button data-position="left" data-tooltip="Columns"  class="tooltipped material-icons nav-tool menu-toggle">library_books</button>	
	</div>
</nav>