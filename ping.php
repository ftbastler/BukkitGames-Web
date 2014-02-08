<?php
	if(!defined('BG_SECURITY')) define("BG_SECURITY", true);
	
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);
	
	require __DIR__ . '/assets/php/MinecraftServerPing.php';
	require __DIR__ . '/assets/php/MinecraftColors.php';
	require __DIR__ . '/config.php';
	
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
				$Info['version'] = array('name' => $Info['Version'], 'protocol' => $Info['Protocol']);
				$Info['description'] = $Info['HostName'];
				$Info['favicon'] = "";
				
				unset($Info['MaxPlayers']);
				unset($Info['Players']);
				unset($Info['HostName']);
				unset($Info['Version']);
				unset($Info['Protocol']);
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
	$Timer = Number_Format( MicroTime( true ) - $Timer, 2, '.', '' );
	
	/* This can be used for AJAX requests later
		if(isset($Exception))
			echo json_encode(array('exception' => $Exception->getMessage(), 'trace' => $Exception->getTraceAsString(), 'time' => $Timer), JSON_PRETTY_PRINT);
		else
			echo json_encode(array('servers' => $servers, 'time' => $Timer), JSON_PRETTY_PRINT);
	*/
?>


<?php if(isset($Exception)) { ?>
	<div class="panel">
		<div class="panel-header bg-lightRed fg-white">
			<?php echo htmlspecialchars( $Exception->getMessage( ) ); ?>
		</div>
		<div class="panel-content">
			<p><?php echo nl2br( $e->getTraceAsString(), false ); ?></p>
		</div>
	</div>
<?php } else { 
	foreach($servers as $s) { ?>
	<a href="#" class="list<?php if(strpos($s[1]['cdescription'], $progress_motd) === false) echo " selected"; ?>">
		<div class="list-content">
			<img width="64" height="64" class="icon" src="<?php echo ($s[1]['favicon'] == null || $s[1]['favicon'] == "" ? "data:image/jpg;base64,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" : Str_Replace("\n", "", $s[1]['favicon'])) ?>">
			<div class="data">
				<span class="list-title" style="white-space: normal;"><strong><?php echo $s[0]; ?></strong></span>
				<div class="progress-bar small" data-hint="Players|<?php echo $s[1]['players']['online']; ?>/<?php echo $s[1]['players']['max']; ?>" data-hint-position="right" data-role="progress-bar" data-value="<?php echo ($s[1]['players']['online'] == 0 ? 0 : ($s[1]['players']['online'] / $s[1]['players']['max'])*100); ?>"><div class="bar bg-cyan" style="width: <?php echo ($s[1]['players']['online'] == 0 ? 0 : ($s[1]['players']['online'] / $s[1]['players']['max'])*100); ?>%;"></div></div>
				<span class="list-remark" style="white-space: normal;"><?php echo $s[1]['cdescription']; ?></span>
			</div>
		</div>
	</a>
<?php } } ?>