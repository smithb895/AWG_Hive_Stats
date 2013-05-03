<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

//session_start();
//require("session.php");
require("hive_connect.php");
require("functions.php");

if (isset($_GET['playerid'])) {
	if (strlen($_GET['playerid']) > 20) {
		die('Player ID too long.');
	}
	$playerid = preg_replace('#[^0-9a-z+]#i', '', $_GET['playerid']);
	$result = getPlayerDataID($playerid,$dbhandle);
	$result_string = parseResults($result);
} elseif (isset($_GET['playername'])) {
	if (preg_match('#[^a-z0-9 _\[\]\#\+\!\{\}\=\<\>\|\:\-\.\*\?\^\&\%\$()/,@;~`+]#i', $_GET['playername'])) {
		die('Invalid character in player name.  It must be valid ASCII characters and must not contain backslashes (\\), single quotes, or double quotes.');
	}
	if (strlen($_GET['playername']) > 255) {
		die('Player name too long. Max=255');
	}
	if (strlen($_GET['playername']) < 2) {
		die('Player name is too short.');
	}
	$playername = $_GET['playername'];
	$result = getPlayerDataName($playername,$dbhandle);
	$result_string = parseResults($result);
} elseif (isset($_GET['id'])) {
	$id = preg_replace('#[^0-9+]#', '', $_GET['id']);
	$result_string = getPlayerSkin($id,$dbhandle);
} else {
	die("<h2>No playerID or player name specified</h2>");
}


echo $result_string;

?>
