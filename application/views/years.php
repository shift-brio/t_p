<?php 
	$year = date('Y') - 8;	
	while ($year >= 1900) {
		echo "<option>".$year."</option>";
		$year = $year - 1;
	}	
 ?>