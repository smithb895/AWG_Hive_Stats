<?php
//require("session.php");
require("config.php");

try {
	$dbhandle = new PDO("mysql:host=$hive_address;dbname=$hive_db", $hive_user, $hive_pass);
} catch (PDOException $err) {
	die($err->getMessage());
}
?>
