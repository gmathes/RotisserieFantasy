<?php
session_start();
  if(!isset($_SESSION['is_logged_in'])) {
	header("location:login.php");
}

include 'head.html';
include 'library/config.php';
include 'library/opendb.php';
if (isset($_GET['user'])) {
$userid = $_GET['user'];
} else {
$userid = $_SESSION['userid'];
}
if (isset($_GET['date'])) {
$date = $_GET['date'];
} else {
$date = date("omd", time()-100800);
}
$prev_day = date("omd",strtotime($date)-76400); 
$next_day = date("omd",strtotime($date)+96400);
$today = date('n/j/y', strtotime($date));
echo "<a href='home.php?date=$prev_day&user=$userid'>Previous Day<a>\n";
echo "Date is $today. <a href='home.php?date=$next_day&user=$userid'>Next Day<a><br><br>\n";
$query = "select username from users where id = $userid";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
$usernm = mysql_result($result,0);
echo "<br>$usernm's team:<br>";
$query = "select * from lineups, players , stats where lineups . rplayerid = players . id and lineups . ruserid = '$userid' and lineups . rplayerid= stats . player and stats . date = '$date' and lineups.rdate = '$date'";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
echo "<table border = '2' cellpadding ='5'>\n";
echo "<tr><th>Name</th><th>Team</th><th>Position</th>";
echo "<th>Minutes</th><th>FG %</th><th>Blocks</th><th>Steals</th><th>3s</th><th>Rebounds</th><th>Assists</th><th>Points</th></tr>";
while ($row = mysql_fetch_array($result)) {
$total_shots += $row[shots];
$total_shotsm += $row[shots_made]; 
$total_ast += $row[ast];
$total_pts += $row[pts];
$total_reb += $row[reb];
$total_blk += $row[bs];
$total_stl += $row[stl];
$total_threem += $row[threes_made];
$fgper = round($row[shots_made] / $row[shots], 2);
echo "<tr>";
echo "<td><a href='http://sports.yahoo.com/nba/boxscore?gid=$row[game]'>$row[name] </a></td>\n";
echo "<td>$row[team]</td>\n";
echo "<td>$row[pos]</td>\n";
echo "<td>$row[min]</td>\n";
echo "<td>$fgper</td>\n";
echo "<td>$row[bs]</td>\n";
echo "<td>$row[stl]</td>\n";
echo "<td>$row[threes_made]</td>\n";
echo "<td>$row[reb]</td>\n";
echo "<td>$row[ast]</td>\n";
echo "<td>$row[pts]</td>\n";
echo "</tr>";
} 
if ($total_shots > 0) {
$total_fgper = round($total_shotsm / $total_shots, 2);
} else { $total_fgper = 0; }
echo "<tr><td>Totals</td><td></td><td></td><td></td><td>$total_fgper</td><td>$total_blk</td>\n";
echo "<td>$total_stl</td><td>$total_threem</td><td>$total_reb</td><td>$total_ast</td><td>$total_pts</td></tr>\n";
echo "</table>\n";
include 'foot.html';
?>
