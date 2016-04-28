<?php

check_player('4244');
function check_player($id) {
include 'library/config.php';
include 'library/opendb.php';

mysql_select_db($mysql);
$query = "SELECT * FROM players WHERE ID = '$id'";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

if(mysql_num_rows($result) == 0) {
        echo "going to open http://sports.yahoo.com/nba/players/$id  ";
        $player_url = "http://sports.yahoo.com/nba/players/$id";
        $player_html = file($player_url);
        $regex_name = "/player-name\">(.+)</";
        $regex_pos = "/position\">(.+)</";
        $regex_team = "/nba\/teams\/([a-z]+)\"/";

	foreach ($player_html as $line_num => $line) {
		if (preg_match($regex_name, $line, $matches)) {
			$name = $matches[1];
		}
		if (preg_match($regex_pos, $line, $matches)) {
			$pos = $matches[1];
		}
		if(preg_match($regex_team, $line, $matches)) {
			$team = $matches[1];
                }

	}
	}
	echo "\n<br><br>Trip for realz: $name, $pos, $team";
$insert = "INSERT into players (id, name, team, pos) VALUES ('$id', '$name', '$team', '$pos')";
mysql_query($insert) or die('Error, insert query failed');

$query = "FLUSH PRIVILEGES";

include 'library/closedb.php';
}
?>
