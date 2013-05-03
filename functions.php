<?php
/*  -={AWG}=- HIVE Admin
 *  Author: Anzu
 *  Desc: Functions for fetching
 *  and parsing data from HIVE.
*/

function getPlayerDataID($playerid,$dbhandle) {
	$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.unique_id LIKE ? ORDER BY last_updated DESC LIMIT 50");
	$qry->execute(array('%'.$playerid.'%'));
	$result = $qry->fetchAll(PDO::FETCH_NUM);
	return $result;
}

function getPlayerDataName($playername,$dbhandle) {
	$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE p.name LIKE ? ORDER BY last_updated DESC LIMIT 50");
	$qry->execute(array('%'.$playername.'%'));
	$result = $qry->fetchAll(PDO::FETCH_NUM);
	return $result;
}

function getPlayerProfile($playerid,$dbhandle) {
	$qry = $dbhandle->prepare("SELECT p.unique_id, p.name, p.humanity, p.survival_attempts, p.total_survival_time, p.total_survivor_kills, p.total_bandit_kills, p.total_zombie_kills, p.total_headshots FROM profile p WHERE p.unique_id=? LIMIT 1");
	$qry->execute(array($playerid));
	$result = $qry->fetchAll(PDO::FETCH_NUM);
	return $result;
}

function getPlayerSkin($id,$dbhandle) {
	$qry = $dbhandle->prepare("SELECT model FROM survivor WHERE id=? LIMIT 1");
	$qry->execute(array($id));
	//$result = $qry->fetch(PDO::FETCH_NUM);
	//$result_string = '';
	//$result_string = $result[0].',~,'.$result[1].',~,'.$result[2];
	$result_string = $qry->fetchColumn();
	return $result_string;
}

//  Top Players
function getTopPlayers($orderby,$dbhandle) {
	switch ($orderby) {
		// Order by zombie kills
		case "zombie_kills":
			$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.is_dead=0 AND (DATEDIFF(last_updated,now()) > -7) ORDER BY s.zombie_kills DESC LIMIT 10");
			break;
		case "survivor_kills":
			$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.is_dead=0 AND (DATEDIFF(last_updated,now()) > -7) ORDER BY s.survivor_kills DESC LIMIT 10");
			break;
		case "bandit_kills":
			$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.is_dead=0 AND (DATEDIFF(last_updated,now()) > -7) ORDER BY s.bandit_kills DESC LIMIT 10");
			break;
		case "start_time":
			$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.is_dead=0 AND (DATEDIFF(last_updated,now()) > -7) ORDER BY hours_old DESC LIMIT 10");
			break;
		case "bandits":
			$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.is_dead=0 AND (DATEDIFF(last_updated,now()) > -7) ORDER BY p.humanity LIMIT 10");
			break;
		case "heroes":
			$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.is_dead=0 AND (DATEDIFF(last_updated,now()) > -7) ORDER BY p.humanity DESC LIMIT 10");
			break;
		default:
			$qry = $dbhandle->prepare("SELECT s.id, s.unique_id, p.name, s.zombie_kills, s.survivor_kills, s.bandit_kills, s.start_time, s.last_updated, s.is_dead, s.world_id, timestampdiff(hour, s.start_time, s.last_updated) as hours_old, p.humanity, s.model FROM profile p LEFT JOIN survivor s ON p.unique_id = s.unique_id WHERE s.is_dead=0 AND (DATEDIFF(last_updated,now()) > -7) ORDER BY s.zombie_kills DESC LIMIT 10");
			break;
	}
	$qry->execute();
	$result = $qry->fetchAll(PDO::FETCH_NUM);
	return $result;
}

function parseResults($results) {
	$_count = 0;
	$result_string = '';
	foreach($results as $row) {
		$_count++;
		if ($_count != 1) {
			$result_string .= '<~~~>';
		}
		$_count2 = 0;
		foreach($row as $column) {
			$_count2 = $_count2 + 1;
			if ($_count2 == 1) {
				$result_string .= $column;
			} else {
				$result_string .= ',~,'.$column;
			}
		}
	}
	return $result_string;
}

function stringSplitSQL($inputString, $columnName) {
	$safe = '';
	$validchars = '#[^0-9a-z_ \.,\-+]#i';
	if (preg_match($validchars, $inputString)) {
		$stringsplit = preg_split($validchars, $inputString);
		for ($i=0; $i<count($stringsplit); $i++) {
			if ($i != 0) {
				$safe .= ' AND '.$columnName.' LIKE %'.$stringsplit[$i].'%';
			} else {
				$safe = '%'.$stringsplit[$i].'%';
			}
		}
	} else {
		$safe = '%'.$inputString.'%';
	}
	
	return $safe;
}


?>
