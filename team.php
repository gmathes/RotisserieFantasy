<?php
session_start();
  if(!isset($_SESSION['is_logged_in'])) {
	header("location:login.php");
}

include 'head.html';
include 'library/config.php';
include 'library/opendb.php';

$query = "select * from users";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
echo "<table border = '2' cellpadding ='5'>\n";
echo "<tr><th>User</th><th colspan='10'>Players</th></tr>";
while ($row = mysql_fetch_array($result)) {
$query = "select * from rosters, players, users where users.id = rosters.userid and players.id = rosters.playerid and users.id = '$row[id]'";
$rosters = mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
echo "<tr>";
echo "<td><a href='home.php?user=$row[id]'>$row[username] </a></td>\n";
while ($roster = mysql_fetch_array($rosters)) {
echo "<td><a href='http://sports.yahoo.com/nba/players/$roster[playerid]'>$roster[name]</td>\n";
}
echo "</tr>";
} 
echo "</table>\n";
include 'foot.html';
?>
