<?php

?>
<!doctype html>
<html lang="en">
	<head>
    	<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=visualization"></script>
		<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/headjs/0.99/head.load.min.js"></script>
		
		<script type="text/javascript">
			head.js("assets/js/jquery-1.10.1.min.js", "assets/js/underscore-min.js", "assets/js/backbone-min.js", "assets/js/interface.js", "assets/js/d3.v3.min.js", function() {});
		</script>
		<link rel="stylesheet" href="assets/css/pure-min.css"/>
		<link rel="stylesheet" href="assets/css/interface.css"/>
		
	</head>
	
	<body>
	
	<div class="pure-menu pure-menu-open pure-menu-horizontal">
	    <a href="#" class="pure-menu-heading">ffdb visualization (v1.0)</a>
	    <ul>
	        <li class="pure-menu-selected"><a href="#">Home</a></li>
	        <li><a href="#">Settings</a></li>
	        <li><a href="#">Help</a></li>
	    </ul>
	</div>


	<div id="map">
	
	</div>



	</body>

</html>


	