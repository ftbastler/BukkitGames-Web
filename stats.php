<?php
	if(!defined('BG_SECURITY')) define("BG_SECURITY", true);
	
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);
	
	require __DIR__ . '/config.php';

	try	{
	
		$db = @mysql_connect($sql_host, $sql_user, $sql_pass);
		
		if(!$db)
			throw new Exception('Could not connect to database: '.mysql_error());
		
		$rv = @mysql_select_db($sql_db, $db);
		
		if(!$rv)
			throw new Exception('Could not select database: '.mysql_error());
		
		$query1 = "SELECT bg_players.NAME, COUNT(DEATH_REASON) AS WINS FROM bg_plays JOIN bg_players on bg_plays.REF_PLAYER = bg_players.ID WHERE bg_plays.DEATH_REASON = \"WINNER\" AND bg_players.NAME IS NOT NULL GROUP BY REF_PLAYER ORDER BY WINS DESC LIMIT 10;";
		$wins = @mysql_query($query1);

		if(!$wins)
			throw new Exception('Could not query database: '.mysql_error());
			
		$query2 = "SELECT bg_players.NAME, COUNT(DEATH_REASON) AS DEATHS FROM bg_plays JOIN bg_players on bg_plays.REF_PLAYER = bg_players.ID WHERE bg_plays.DEATH_REASON != \"WINNER\" AND bg_plays.DEATH_REASON != \"CRASH\" AND bg_players.NAME IS NOT NULL GROUP BY REF_PLAYER ORDER BY DEATHS DESC LIMIT 10;";
		$deaths = @mysql_query($query2);

		if(!$deaths)
			throw new Exception('Could not query database: '.mysql_error());
			
		$query3 = "SELECT bg_players.NAME, COUNT(REF_KILLER) AS KILLS FROM bg_plays JOIN bg_players on bg_plays.REF_KILLER = bg_players.ID WHERE bg_plays.DEATH_REASON != \"WINNER\" AND bg_plays.REF_KILLER != 0 AND bg_players.NAME IS NOT NULL GROUP BY REF_KILLER ORDER BY KILLS DESC LIMIT 10;";
		$kills = @mysql_query($query3);

		if(!$kills)
			throw new Exception('Could not query database: '.mysql_error());
		
		@mysql_close();
	} catch(Exception $e) {
		$Exception = $e;
	}
?>


<?php if(isset($Exception)) { ?>
	<div class="panel">
		<div class="panel-header bg-lightRed fg-white">
			<?php echo htmlspecialchars( $Exception->getMessage( ) ); ?>
		</div>
		<div class="panel-content">
			<p>Please go to your <span class="code-text">config.php</span> file and fix this error.</p>
		</div>
	</div>
<?php } else { 
	echo "<h3>Kills</h3>";
	echo "<table class=\"table hovered\"><thead><tr><td><i class=\"icon-award-fill\"></i></td><td>Player</td><td>Kills</td></tr></thead>";
	$i = 0;
	while($row = mysql_fetch_array($kills)) {
		$i++;
		echo "<tr><td>" . $i . "</td><td><img src=\"https://minotar.net/helm/". $row['NAME'] ."/20\" alt=\"\"> " . $row['NAME'] . "</td><td style=\"width: 30%;\">" . $row['KILLS'] . "</td></tr>";
	}
	echo "</table>";
	
	echo "<h3>Deaths</h3>";
	echo "<table class=\"table hovered\"><thead><tr><td><i class=\"icon-award-fill\"></i></td><td>Player</td><td>Deaths</td></tr></thead>";
	$i = 0;
	while($row = mysql_fetch_array($deaths)) {
		$i++;
		echo "<tr><td>" . $i . "</td><td><img src=\"https://minotar.net/helm/". $row['NAME'] ."/20\" alt=\"\"> " . $row['NAME'] . "</td><td style=\"width: 30%;\">" . $row['DEATHS'] . "</td></tr>";
	}
	echo "</table>";
	
	echo "<h3>Wins</h3>";
	echo "<table class=\"table hovered\"><thead><tr><td><i class=\"icon-award-fill\"></i></td><td>Player</td><td>Wins</td></tr></thead>";
	$i = 0;
	while($row = mysql_fetch_array($wins)) {
		$i++;
		echo "<tr><td>" . $i . "</td><td><img src=\"https://minotar.net/helm/". $row['NAME'] ."/20\" alt=\"\"> " . $row['NAME'] . "</td><td style=\"width: 30%;\">" . $row['WINS'] . "</td></tr>";
	}
	echo "</table>";
} ?>