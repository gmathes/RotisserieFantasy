<?php
session_start();
  if(!isset($_SESSION['is_logged_in'])) {
        header("location:login.php");
}

include 'head.html';
include 'library/config.php';
include 'library/opendb.php';
if (isset($_GET['player'])) {
$query = "delete from rosters where playerid = $_GET[player] and userid = $_SESSION[userid]";
$value = mysql_query($query) or die("Cannot add player $playerid. Possibly already on roster.");
echo "Just dropped $_GET[player] from your roster.";
} else {
$query = "select * from players,rosters where userid = $_SESSION[userid] and id = playerid";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
echo "<table border = '2' cellpadding ='5'>\n";
echo "<tr><th>Players on Roster</th><th>Team</th><th>Position</th></tr>";
while ($row = mysql_fetch_array($result)) {
echo "<tr>";
echo "<td><a href='http://sports.yahoo.com/nba/players/$row[id]'>$row[name]</a></td>\n";
echo "<td>$row[team]</td>\n";
echo "<td>$row[pos]</td>\n";
echo "<td><a href='dropplayer.php?player=$row[id]'>Drop</td>\n";
echo "</tr>";
}
echo "</table>\n";
}
include 'foot.html';
?>
