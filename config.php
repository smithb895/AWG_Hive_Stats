<?php
/*--------------------
 *     -={AWG}=-
 *    HIVE Stats
 *--------------------
 * Author: Anzu
 * Desc: Config settings for HIVE Stats
 * MySQL user only needs SELECT privileges!
 * Grant them like so (SQL):
		GRANT SELECT ON `dayz_hive`.* TO `stats_user`@`1.2.3.4` IDENTIFIED BY 'stats_password';
 * Just replace "1.2.3.4" with the IP address 
 * of the webserver which will be running this 
 * stats page, and insert your user/password/database name.
*/

$hive_address 	= 'hive.example.com';			// IP address or hostname of HIVE MySQL server
$hive_user 		= 'stats_user';					// Username for HIVE
$hive_pass 		= 'stats_password';				// Password for HIVE
$hive_db 		= 'dayz_hive';					// Name of HIVE database


?>