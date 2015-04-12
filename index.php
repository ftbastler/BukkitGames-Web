<?php
	define("BG_SECURITY", true);
	
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);
	
	require __DIR__ . '/config.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/metro-bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/metro-bootstrap-responsive.min.css">
        <script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.widget.min.js"></script>
        <script src="assets/js/metro.min.js"></script>
		<style>
			html, body {
				height: auto !important;
			}
		</style>
    </head>
    <body class="metro">
       <div class="container">
        <div class="margin20 nrm nlm">
            <div class="clearfix">
                <a class="place-left" href="#" title="">
                    <h1><?php echo $page_title; ?></h1>
                </a>
            </div>
		</div>
		<div class="main-content clearfix">
			<div class="grid">
				<div class="row">
					<div class="span4">
						<h2><?php echo $servers_title; ?></h2><br>
						<div class="listview" id="ajax" style="margin-top: 10px;">
							<p class="text-center">
								<i class="icon-busy" style="font-size: 2em;"></i><br>
								<?php echo $loading_text; ?>
							</p>
						</div>
					</div>
					<div class="span10">
						<h2><?php echo $stats_title; ?></h2><br>
						<div id="ajax2">
							<p class="text-center">
								<i class="icon-busy" style="font-size: 2em;"></i><br>
								<?php echo $loading_text; ?>
							</p>
						</div>
					</div>
				</div>
			</div>
          </div>
		  
		  <footer>
            <div class="bottom-menu-wrapper">
                <ul class="horizontal-menu compact">
                    <li><?php echo $footer_text; ?></li>
                    <li><?php echo $footer_text2; ?></li>
                </ul>
            </div>
        </footer>
		  
		</div>
				
		<script>
			$(document).ready(function() {
				$("#ajax").load("ping.php", function() {$(".progress-bar").hint();});
				window.setInterval(function() { $("#ajax").load("ping.php", function() {$(".progress-bar").hint();}); }, 10000);
				
				$("#ajax2").load("stats.php");
				window.setInterval(function() { $("#ajax2").load("stats.php"); }, 30000);
			});
		</script>
    </body>
</html>
