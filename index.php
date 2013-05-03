<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php
$pagetitle = '<title>[AWG] DayZ HIVE Stats';
if (isset($_GET['uid'])) {
	$uid = preg_replace('#[^0-9a-z+]#i', '', $_GET['uid']);
	$playername = "";
	$pagetitle .= " - $playername";
}
$pagetitle .= "</title>";
echo $pagetitle;
?>
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<?php include("js.php"); ?>
<div id="player_search_div">
	<!--<h3>Find Player</h3>-->
	<form method="get" onClick="return false;">
		<div>
			<div class="left">
				<h5>Name</h5>
				<input id="player_name" name="player_name" type="text" size="15" />
			</div>
			<div id="leftmargin" class="right">
				<h5>ID</h5>
				<input id="player_id" name="player_id" type="text"/>
			</div>
		</div>
		<button id="find_player_btn" type="submit" value="">Search</button>
	</form>
</div>
<div id="links">
	<ul id="menu-bar">
		<li><a href="#" onClick="fetch_top_players('zombie_kills');return false;" title="Show top zombie killers">Zombie Killers</a></li>
		<li><a href="#" onClick="fetch_top_players('survivor_kills');return false;" title="Show top survivor killers">Murderers</a></li>
		<li><a href="#" onClick="fetch_top_players('bandit_kills');return false;" title="Show top bandit killers">Bandit Killers</a></li>
		<li><a href="#" onClick="fetch_top_players('start_time');return false;" title="Show longest surviving players">Oldest</a></li>
		<li><a href="#" onClick="fetch_top_players('heroes');return false;" title="Show top heroes">Heroes</a></li>
		<li><a href="#" onClick="fetch_top_players('bandits');return false;" title="Show top bandits">Bandits</a></li>
	</ul>
</div>
<div id="banner">
	<img src="images/logo.png" width="300" height="145"/>
</div>
<div id="player_selection"></div>
<div id="current_player">
	<div id="stats_header">
		<h2>Player Totals</h2>
		<h2 id="stats_player_name">None</h2>
	</div>
	<table id="table_playerdata">
		<tr id="row_fieldname">
			<th>PlayerID</th>
			<th>Zombie Kills</th>
			<th>Murders</th>
			<th>Bandit Kills</th>
			<th>Time Alive</th>
			<th>Headshots</th>
			<th>Survival Attempts</th>
			<th>Humanity</th>
		</tr>
		<tr class="row_playerdata">
			<td id="table_playerid"></td>
			<td id="table_zkills"></td>
			<td id="table_murders"></td>
			<td id="table_bkills"></td>
			<td id="table_talive"></td>
			<td id="table_headshots"></td>
			<td id="table_attempts"></td>
			<td id="table_humanity"></td>
		</tr>
	</table>
</div>
<div id="player_search_results">


</div>




