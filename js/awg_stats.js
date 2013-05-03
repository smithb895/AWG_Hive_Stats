/*
 * -=[AWG]=- DayZ HIVE Stats
 * Author: Anzu
 * Description: This script fetches
 * player data from the HIVE and 
 * displays it on the web page.
 * Requires jQuery.
*/

/* Functions Begin */
function fetchSearchResults(postdata) {
	$.ajax({
		type: "GET",
		url: "get_player_data.php",
		data: postdata,
		success: function(response) {
			$("#player_search_results").html('<h2>Search Results</h2>');
			var result_rows_array = response.split('<~~~>');
			if (response.length < 4) {
				var num_results = 0;
			} else {
				if (result_rows_array.length > 49) {
					var num_results = 'over 50';
				} else {
					var num_results = result_rows_array.length;
				}
			}
			$("#player_search_results").append('<h3>Found '+ num_results +' results</h3><br />');
			var result_table = '<table id="table_playerdata"><tr id="row_fieldname"><th>Name</th><th>PlayerID</th><th>Dead?</th><th>Zombie Kills</th><th>Murders</th><th>Bandit Kills</th><th>Time Alive</th><th>Last Update</th><th>First Seen</th><th>Map</th><th>Humanity</th></tr>';
			var _count = 0;
			$.each(result_rows_array, function() {
				_count += 1;
				var row_array = this.split(',~,');
				var hours_alive = row_array[10];
				if (hours_alive > 24) {
					var days_alive = roundNumber(hours_alive / 24, 0);
					var hours_remainder = hours_alive - (days_alive * 24);
					if (hours_remainder < 1) {
						var time_alive = days_alive.toString() + "days";
					} else {
						var time_alive = days_alive.toString() + "days " + hours_remainder.toString() + "hrs";
					}
				} else {
					var time_alive = hours_alive + " hrs";
				}
				var is_dead = 'No';
				if (row_array[8] == 1) {
					is_dead = 'Yes';
				}
				var first_seen = row_array[6].substring(0,10);
				var _mapname = getMapName(row_array[9]);
				var humanity = row_array[11];
				var skin = row_array[12];
				result_table += '<tr class="row_playerdata">';
				//result_table += '<td><a href="" onClick=\'selectPlayer('+ row_array[0] +',"'+ row_array[1] +'","'+ row_array[2] +'",'+ row_array[3] +','+ row_array[4] +','+ row_array[5] +',"'+ time_alive +'","'+ row_array[7] +'","'+ first_seen +'","'+ humanity +'");return false;\' id="view_gear" alt="Select Player" title="Select Player">'+row_array[2]+'</a></td>';	// Player Name row_array[2]
				result_table += '<td><a href="" onClick=\'selectPlayer('+ row_array[1] +',"'+ skin +'");return false;\' id="view_gear" alt="Select Player">'+row_array[2]+'</a></td>';	// Player Name
				result_table += '<td>'+ row_array[1] +'</td>';	// Player ID
				result_table += '<td>'+ is_dead +'</td>';	// Dead?
				result_table += '<td>'+ row_array[3] +'</td>'; // Zombie kills
				result_table += '<td>'+ row_array[4] +'</td>';	// Murders
				result_table += '<td>'+ row_array[5] +'</td>';	// Bandit kills
				result_table += '<td>'+ time_alive +'</td>';	// Time alive
				result_table += '<td>'+ row_array[7] +'</td>';	// Last update
				result_table += '<td>'+ first_seen +'</td>';	// Date First Seen
				result_table += '<td>'+ _mapname +'</td>';
				result_table += '<td>'+ humanity +'</td>';
				result_table += '</tr>';
			});
			result_table += '</table><br />';
			$("#player_search_results").append(result_table);
			//$("#player_search_results").show();
			//$("#player_search_results").animate({
			//	opacity: 1,
			//	height: 'auto'
			//}, 600, function() {
				
			//});
		}
	});
}

function searchPlayers() {
	if ($("#player_id").val().length > 3) {
		var playerid = $("#player_id").val();
		var postdata = 'playerid='+ playerid;
	} else if ($("#player_name").val().length > 1) {
		var playername = $("#player_name").val();
		var postdata = 'playername='+ playername;
	} else {
		alert("Player name or ID too short or no name or ID specified");
		return false;
	}
	fetchSearchResults(postdata);
	showSearchResults();
	return false;
}

function showPlayerSelected() {
	var cssRules = {
			'visibility' : 'visible'
	}
	$("#current_player").css(cssRules);
}
/*
function selectPlayer(id,uid,name,kills,hkills,bkills,talive,lupdate,first_seen,humanity) {
	var postdata = 'id='+ id;
	//clear_inventory();
	$.ajax({
		type: "POST",
		url: "get_player_data.php",
		data: postdata,
		success: function(response) {
			//clear_inventory();
			var skin = response;
			var skin_image = getSkin(skin);
			$("#current_skin").html('<img src="images/skins/'+ skin_image +'" alt="Player Skin" id="player_skin_img"/>');
			hideSearchResults();
		}
	});
	$("#stats_player_name").html(name);
	$("#table_playerid").html(uid);
	$("#table_zkills").html(kills);
	$("#table_murders").html(hkills);
	$("#table_bkills").html(bkills);
	$("#table_talive").html(talive);
	$("#table_lupdate").html(lupdate);
	$("#table_first_seen").html(first_seen);
	$("#table_humanity").html(humanity);
	$("#current_id").html(id);
	showPlayerSelected();
	return false;
}
*/
function selectPlayer(uid,skin) {
	var postdata = 'uid='+ uid;
	var skin_image = getSkin(skin);
	//$("#current_skin").html('<img src="images/skins/'+ skin_image +'" alt="Player Skin" id="player_skin_img"/>');
	hideSearchResults();
	//clear_inventory();
	$.ajax({
		type: "GET",
		url: "get_player_profile.php",
		data: postdata,
		success: function(response) {
			//clear_inventory();
			var profile_array = response.split(',~,');
			var hours_alive = profile_array[4];
			if (hours_alive > 24) {
				var days_alive = roundNumber(hours_alive / 24, 0);
				var hours_remainder = hours_alive - (days_alive * 24);
				if (hours_remainder < 1) {
					var time_alive = days_alive.toString() + "days";
				} else {
					var time_alive = days_alive.toString() + "days " + hours_remainder.toString() + "hrs";
				}
			} else {
				var time_alive = hours_alive + " hrs";
			}
			var puid 					= profile_array[0]; // player UID
			var pname 					= profile_array[1]; // player name
			var humanity 				= profile_array[2];
			var survival_attempts 		= profile_array[3];
			var total_survival_time 	= time_alive;
			var total_survivor_kills 	= profile_array[5];
			var total_bandit_kills 		= profile_array[6];
			var total_zombie_kills 		= profile_array[7];
			var total_headshots 		= profile_array[8];
			$("#stats_player_name").html(pname);
			$("#table_playerid").html(puid);
			$("#table_zkills").html(total_zombie_kills);
			$("#table_murders").html(total_survivor_kills);
			$("#table_bkills").html(total_bandit_kills);
			$("#table_talive").html(total_survival_time);
			$("#table_headshots").html(total_headshots);
			$("#table_attempts").html(survival_attempts);
			$("#table_humanity").html(humanity);
		}
	});
	//$("#current_id").html(id);
	showPlayerSelected();
	return false;
}

function showSearchResults() {
	//$("#gear_strings").fadeOut(800);
	//$("#gear_preview").fadeOut(800);
	$("#player_search_results").animate({
		opacity: 1,
		height: 'auto'
	}, 600, function() {
		$("#player_search_results").fadeIn(600);
	});
	
}

function hideSearchResults() {
	$("#player_search_results").animate({
		opacity: 0,
		height: 0
	}, 600, function() {
		$("#player_search_results").hide();
		var cssRules = {
			'height' : 'auto',
			'opacity' : '1'
		}
		$("#player_search_results").css(cssRules);
	});
}

function fetch_top_players(orderby) {
	var postdata = 'orderby='+orderby;
	$.ajax({
		type: "GET",
		url: "get_top_players.php",
		data: postdata,
		success: function(response) {
			$("#player_search_results").html('<h2>Top Players</h2>');
			var result_rows_array = response.split('<~~~>');
			if (response.length < 10) {
				var num_results = 0;
			} else {
				var num_results = result_rows_array.length;
			}
			var result_table = '<table id="table_playerdata"><tr id="row_fieldname"><th>Name</th><th>PlayerID</th><th>Dead?</th><th>Zombie Kills</th><th>Murders</th><th>Bandit Kills</th><th>Time Alive</th><th>Last Update</th><th>First Seen</th><th>Map</th><th>Humanity</th></tr>';
			var _count = 0;
			$.each(result_rows_array, function() {
				_count += 1;
				var row_array = this.split(',~,');
				var hours_alive = row_array[10];
				if (hours_alive > 24) {
					var days_alive = roundNumber(hours_alive / 24, 0);
					var hours_remainder = hours_alive - (days_alive * 24);
					if (hours_remainder < 1) {
						var time_alive = days_alive.toString() + "days";
					} else {
						var time_alive = days_alive.toString() + "days " + hours_remainder.toString() + "hrs";
					}
				} else {
					var time_alive = hours_alive + " hrs";
				}
				var is_dead = 'No';
				if (row_array[8] == 1) {
					is_dead = 'Yes';
				}
				var first_seen = row_array[6].substring(0,10);
				var _mapname = getMapName(row_array[9]);
				var humanity = row_array[11];
				var skin = row_array[12];
				result_table += '<tr class="row_playerdata">';
				//result_table += '<td><a href="" onClick=\'selectPlayer('+ row_array[0] +','+ row_array[1] +',"'+ row_array[2] +'",'+ row_array[3] +','+ row_array[4] +','+ row_array[5] +',"'+ time_alive +'","'+ row_array[7] +'","'+ first_seen +'","'+ humanity +'");return false;\' id="view_gear" alt="Select Player">'+row_array[2]+'</a></td>';	// Player Name
				result_table += '<td><a href="" onClick=\'selectPlayer('+ row_array[1] +',"'+ skin +'");return false;\' id="view_gear" alt="Select Player">'+row_array[2]+'</a></td>';	// Player Name
				result_table += '<td>'+ row_array[1] +'</td>';	// Player ID
				result_table += '<td>'+ is_dead +'</td>';	// Dead?
				result_table += '<td>'+ row_array[3] +'</td>'; // Zombie kills
				result_table += '<td>'+ row_array[4] +'</td>';	// Murders
				result_table += '<td>'+ row_array[5] +'</td>';	// Bandit kills
				result_table += '<td>'+ time_alive +'</td>';	// Time alive
				result_table += '<td>'+ row_array[7] +'</td>';	// Last update
				result_table += '<td>'+ first_seen +'</td>';	// Date First Seen
				result_table += '<td>'+ _mapname +'</td>';
				result_table += '<td>'+ humanity +'</td>';
				result_table += '</tr>';
			});
			result_table += '</table><br />';
			$("#player_search_results").append(result_table);
		}
	});
	showSearchResults();
}

function getSkin(skin) {
	switch(skin) {
		case "Survivor1_DZ":
			var skin_image = "survivor1_dz.png";
			break;
		case "Survivor2_DZ":
			var skin_image = "survivor2_dz.png";
			break;
		case "Survivor3_DZ":
			var skin_image = "survivor3_dz.png";
			break;
		case "SurvivorW2_DZ":
			var skin_image = "survivorw2_dz.png";
			break;
		case "Bandit1_DZ":
			var skin_image = "bandit1_dz.png";
			break;
		case "Camo1_DZ":
			var skin_image = "camo1_dz.png";
			break;
		case "Rocket_DZ":
			var skin_image = "rocket_dz.png";
			break;
		case "Sniper1_DZ":
			var skin_image = "sniper1_dz.png";
			break;
		case "CamoWinter_DZN":
			var skin_image = "skin_camowinter_dzn.png";
			break;
		case "CamoWinterW_DZN":
			var skin_image = "skin_camowinter_dzn.png";
			break;
		case "Sniper1W_DZN":
			var skin_image = "sniper1_dz.png";
			break;
		default:
			var skin_image = "survivor1_dz.png";
			break;
	}
	return skin_image;
}

function getMapName(world_id) {
	switch(world_id) {
		case '1':
			return 'Chernarus';
			break;
		case '2':
			return 'Lingor';
			break;
		case '3':
			return 'Utes';
			break;
		case '4':
			return 'Takistan';
			break;
		case '5':
			return 'Panthera';
			break;
		case '6':
			return 'Fallujah';
			break;
		case '7':
			return 'Zargabad';
			break;
		case '8':
			return 'Namalsk';
			break;
		case '9':
			return 'Celle';
			break;
		case '10':
			return 'Taviana';
			break;
		default:
			return 'Unknown';
			break;
	}
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

/* Functions End */

/* After page loads, execute following */
$(document).ready(function() {
	fetch_top_players("zombie_kills");
	
	$("#find_player_btn").click(function() {
		searchPlayers();
		return false;
	});
});
