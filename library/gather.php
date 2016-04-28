<?php
include 'config.php';
include 'opendb.php';

if (!isset($_GET['date'])) {
$url = 'http://sports.yahoo.com/nba/scoreboard';
} else {
$date = $_GET['date'];
$date = transform_date($date);
$url = 'http://sports.yahoo.com/nba/scoreboard?d=' . $date;
}
echo $url;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$regex = '/boxscore\?gid=(\d+)/';
preg_match_all($regex,$output,$match);

foreach ($match[1] as $value) {
	$date = substr($value,0,8);	
	check_game($value,$date);
	echo "Checking game: $value with date $date <br>";
}

function check_game($gameid,$date) 
{
$url = 'http://sports.yahoo.com/nba/boxscore?gid='.$gameid;
$boxscore = file($url);
$regex_player = "/<td class=\"player\" align=\"left/";

foreach ($boxscore as $line_num => $line) { 
if (preg_match($regex_player, $line))
{
        $ln = $line_num;

        #match line with player name and id
        $regex_name = "/nba\/players\/(\d+)\">(.+)</";
        $temp = $ln + 1;
        preg_match($regex_name, $boxscore[$temp], $matches);
        $id = $matches[1];
        $name = $matches[2];

        #compensate for additional position line
        $temp = $ln + 3;
        $regex_pos = "/[GCF]/";
        if(preg_match($regex_pos, $boxscore[$temp])) {
                        $ln++;
                        }

        #min
        $regex_min = "/>(.+)<\/td/";
        $temp = $ln + 4;
        preg_match($regex_min, $boxscore[$temp], $matches);
        $min = $matches[1];

        #skip the rest if DNP
        $regex_dnp = "/DNP/";
        if(!preg_match($regex_pos, $min)) {

        #FG
        $regex_fg = "/d>(\d+)-(\d+)<\/t/";
        $temp = $ln + 5;
        preg_match($regex_fg, $boxscore[$temp], $matches);
        $shots = $matches[2];
        $shots_made = $matches[1];

        #3PT
        $regex_three = "/d>(\d+)-(\d+)<\/t/";
        $temp = $ln + 6;
        preg_match($regex_three, $boxscore[$temp], $matches);
        $threes = $matches[2];
        $threes_made = $matches[1];

        #FT
        $regex_fts = "/d>(\d+)-(\d+)<\/t/";
        $temp = $ln + 7;
        preg_match($regex_fts, $boxscore[$temp], $matches);
        $fts = $matches[1];
        $fts_made = $matches[2];

        #plusminus
        $regex_pm = "/>(.+)<\/td/";
        $temp = $ln + 8;
        preg_match($regex_pm, $boxscore[$temp], $matches);
        $pm = $matches[1];

        #Off
        $regex_off = "/>(\d+)<\/td/";
        $temp = $ln + 9;
        preg_match($regex_off, $boxscore[$temp], $matches);
        $off = $matches[1];

        #Reb 
        $regex_reb = "/>(\d+)<\/td/";
        $temp = $ln + 10;
        preg_match($regex_reb, $boxscore[$temp], $matches);
        $reb = $matches[1];

        #Ast 
        $regex_ast = "/>(\d+)<\/td/";
        $temp = $ln + 11;
        preg_match($regex_ast, $boxscore[$temp], $matches);
        $ast = $matches[1];

        #TO 
        $regex_to = "/>(\d+)<\/td/";
        $temp = $ln + 12;
        preg_match($regex_to, $boxscore[$temp], $matches);
        $to = $matches[1];

        #Stl 
        $regex_stl = "/>(\d+)<\/td/";
        $temp = $ln + 13;
        preg_match($regex_stl, $boxscore[$temp], $matches);
        $stl = $matches[1];

        #BS 
        $regex_bs = "/>(\d+)<\/td/";
        $temp = $ln + 14;
        preg_match($regex_bs, $boxscore[$temp], $matches);
        $bs = $matches[1];

        #BA 
        $regex_ba = "/>(\d+)<\/td/";
        $temp = $ln + 15;
        preg_match($regex_ba, $boxscore[$temp], $matches);
        $ba = $matches[1];

        #PF 
        $regex_pf = "/>(\d+)<\/td/";
        $temp = $ln + 16;
        preg_match($regex_pf, $boxscore[$temp], $matches);
        $pf = $matches[1];

        #Pts 
        $regex_pts = "/>(\d+)&/";
        $temp = $ln + 17;
        preg_match($regex_pts, $boxscore[$temp], $matches);
        $pts = $matches[1];

	#need to check for player existence in players table
	check_player($id);
        echo "Player: $id, $name, $min, $shots, $shots_made, $threes, $threes_made, $fts, $fts_made, $pm, $off, $reb, $ast, $to, $stl, $bs, $ba, $pf, $pts";
        echo "<br>";

#mysql_select_db($mysql);


$query = "SELECT * FROM stats WHERE player = '$id' and game = '$gameid'";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
if (mysql_num_rows($result) == 0) {
$query = "INSERT INTO stats (player, game, pts, date, min, shots, shots_made, threes, threes_made, fts, fts_made, pm, off, reb, ast, turn, stl, bs, ba, pf) VALUES ('$id', '$gameid', '$pts','$date', '$min', '$shots', '$shots_made', '$threes', '$threes_made', '$fts', '$fts_made', '$pm', '$off', '$reb', '$ast', '$to', '$stl', '$bs', '$ba', '$pf')";

#mysql_query($query) or die('Error, insert query failed');
$value = mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
}
        }
} 
}
}



function check_player($id) {

$query = "SELECT * FROM players WHERE ID = '$id'";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

if(mysql_num_rows($result) == 0) {
        $player_url = "http://sports.yahoo.com/nba/players/$id";
        $player_html = file($player_url);
        $regex_name = "/player-name\">(.+)</";
        $regex_pos = "/position\">(.+)</";
        $regex_team = "/nba\/teams\/([a-z]+)\"/";

        foreach ($player_html as $line_num => $line) {
                if (preg_match($regex_name, $line, $matches)) {
                        $name = mysql_real_escape_string($matches[1]);
                }
                if (preg_match($regex_pos, $line, $matches)) {
                        $pos = $matches[1];
                }
                if(preg_match($regex_team, $line, $matches)) {
                        $team = $matches[1];
                }

        }
        echo "\nAdding new player to database: $name, $pos, $team<br>";
	$insert = "INSERT into players (id, name, team, pos) VALUES ('$id', '$name', '$team', '$pos')";
	mysql_query($insert) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
        }


}

function transform_date($date) {
$year = substr($date,0,4);
$month = substr($date,4,2); 
$date = substr($date,6,2);
$new_date = $year . "-" . $month . "-" . $date;
return $new_date;

}

include 'closedb.php';
?>
