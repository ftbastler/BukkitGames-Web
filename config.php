<?php
	if(!defined('BG_SECURITY')) die("Invalid access");
	
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);
	
	if(defined('BG_CONFIG')) return;
	define('BG_CONFIG', true);
	
	// DON'T CHANGE STUFF ABOVE THIS LINE
	
	
	
		//Add each server you want to ping to this list (ip and port)
		$serverlist = array(
			array('yourserver.com', 25565),
			array('127.0.0.1', 25565)
		);
		
		//Timeout for pinging the server (default: 2s)
		$timeout = 1;
		
		//SQL information
		$sql_host = 'hostname';
		$sql_user = 'username';
		$sql_pass = 'password';
		$sql_db = 'database';
		
		//A word that, when in the MOTD, will set the server as in progress
		$progress_motd = "progress";
		
		//Page title
		$page_title = "My HungerGames server";
	
		//Server sub title
		$servers_title = "Servers";
		
		//Stats sub title
		$stats_title = "Statistics";
		
		//Footer
		$footer_text = "© 2014 BukkitGames";
		$footer_text2 = "This is only a template. BukkitGames is not responsible for this site and its contents.";
		
		//Other
		$loading_text = "Loading...";
	
	
	
	// DON'T CHANGE STUFF UNDER THIS LINE
?>