<?php
	// Edit this ->
		
		//Add each server you want to ping to this list (ip and port)
		$serverlist = array(
			array('firstserver.com', 25565),
			array('172.0.0.1', 25565)
		);
		
		//Timeout for pinging the server (default: 2s)
		$timeout = 2;
		
		//A word that, when in the MOTD, will set the server as in progress
		$progress_motd = "progress";
		
		//Page title
		$page_title = "My HungerGames server list";
	
	// Edit this <-
	
	// DO NOT EDIT THING BELOW HERE
	Error_Reporting( E_ALL | E_STRICT );
	Ini_Set( 'display_errors', true );
	
	require __DIR__ . '/assets/php/MinecraftServerPing.php';
	require __DIR__ . '/assets/php/MinecraftColors.php';
	
	$Timer = MicroTime( true );
	$servers = array();
	
	foreach($serverlist as $server) {
		$Info = false;
		$Query = null;
		
		try
		{
			$Query = new MinecraftPing($server[0], $server[1], $timeout);
			
			$Info = $Query->Query();
			
			if($Info === false)
			{
				$Query->Close();
				$Query->Connect();
				
				$Info = $Query->QueryOldPre17();
				$Info['players'] = array('max' => $Info['MaxPlayers'], 'online' => $Info['Players']);
				$Info['description'] = $Info['HostName'];
				$Info['favicon'] = "";
			}
			
			$Info['cdescription'] = MinecraftColors::clean($Info['description']);
			
		}
		catch( MinecraftPingException $e )
		{
			$Exception = $e;
		}
		
		if( $Query !== null )
		{
			$Query->Close( );
		}
		
		array_push($servers, array($server[0], $Info));
	}
	
	$Info = null;
	$Query = null;
	$Timer = Number_Format( MicroTime( true ) - $Timer, 4, '.', '' );
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/assets/css/metro-bootstrap.min.css">
		<link rel="stylesheet" href="/assets/css/metro-bootstrap-responsive.min.css">
        <script src="/assets/js/jquery.min.js"></script>
		<script src="/assets/js/jquery.widget.min.js"></script>
        <script src="/assets/js/metro.min.js"></script>
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
			<div class="listview">
			
			<?php if(isset($Exception)) { ?>
				<div class="panel">
					<div class="panel-header bg-lightRed fg-white">
						<?php echo htmlspecialchars( $Exception->getMessage( ) ); ?>
					</div>
					<div class="panel-content">
						<?php echo nl2br( $e->getTraceAsString(), false ); ?>
					</div>
				</div>
			<?php } else { 
				foreach($servers as $s) { ?>
				<a href="#" class="list<?php if(strpos($s[1]['cdescription'], $progress_motd) === false) echo " selected"; ?>">
                    <div class="list-content">
                        <img width="64" height="64" class="icon" src="<?php echo Str_Replace("\n", "", $s[1]['favicon']) ?>">
                        <div class="data">
                            <span class="list-title" style="white-space: normal;"><strong><?php echo $s[0]; ?></strong></span>
                            <div class="progress-bar small" data-hint="Players|<?php echo $s[1]['players']['online']; ?>/<?php echo $s[1]['players']['max']; ?>" data-hint-position="right" data-role="progress-bar" data-value="<?php echo ($s[1]['players']['online'] == 0 ? 0 : ($s[1]['players']['online'] / $s[1]['players']['max'])*100); ?>"><div class="bar bg-cyan" style="width: <?php echo ($s[1]['players']['online'] == 0 ? 0 : ($s[1]['players']['online'] / $s[1]['players']['max'])*100); ?>%;"></div></div>
                            <span class="list-remark" style="white-space: normal;"><?php echo $s[1]['cdescription']; ?></span>
                        </div>
                    </div>
                </a>
			<?php } } ?>
		</div>
            </div>
			</div>
		</div>
    </body>
</html>