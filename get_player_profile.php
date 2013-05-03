<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

//session_start();
//require("session.php");
require("hive_connect.php");
require("functions.php");

if (isset($_GET['uid'])) {
	if (strlen($_GET['uid']) > 20) {
		die('Player ID too long.');
	}
	$playerid = preg_replace('#[^0-9a-z+]#i', '', $_GET['uid']);
	$result = getPlayerProfile($playerid,$dbhandle);
	$result_string = parseResults($result);
} else {
	die("<h2>No playerID or player name specified</h2>");
}


echo $result_string;

?>
