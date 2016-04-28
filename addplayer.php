<?php
session_start();
  if(!isset($_SESSION['is_logged_in'])) {
	header("location:login.php");
}

include 'head.html';
include 'library/config.php';
include 'library/opendb.php';
if (isset($_GET['player'])) {
$query = "select * from rosters where userid = '$_SESSION[userid]'";
$pl_result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
$numrows = mysql_num_rows($pl_result);
if (mysql_num_rows($pl_result) < 10) {
$query = "insert into rosters (userid, playerid) values ($_SESSION[userid], $_GET[player])";
$value = mysql_query($query) or die("Cannot add player $playerid. Possibly already on roster.");
echo "Just added $_GET[player] to roster.";
} else {
echo "Too many players on your team.";
}
} else {
if (isset($_GET['page'])) {
$page = $_GET['page'];
$offset = $page * 30;
$prev_page = $page - 1;
$next_page = $page + 1;
} else {
$offset = 0;
$prev_page = 0;
$next_page = 1;
}
echo '<FORM METHOD=GET ACTION="addplayer.php">
Name: <INPUT NAME="name"><BR>
<INPUT TYPE=SUBMIT>
</FORM><BR>';
if (isset($_GET['name'])) {
$name = $_GET['name'];
$query = "select * from players where name like '%$name%' and players.id not in (select playerid from rosters) limit $offset,30";
} else {
$query = "select * from players where players.id not in (select playerid from rosters) limit $offset,30";
}
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
echo "<a href='addplayer.php?page=$prev_page'><<<a>\n";
echo "    <a href='addplayer.php?page=$next_page'>>><a><br><br>\n";
echo "<table border = '2' cellpadding ='5'>\n";
echo "<tr><th>Available Players</th><th>Team</th><th>Position</th></tr>";
while ($row = mysql_fetch_array($result)) {
echo "<tr>";
echo "<td><a href='http://sports.yahoo.com/nba/players/$row[id]'>$row[name]</a></td>\n";
echo "<td>$row[team]</td>\n";
echo "<td>$row[pos]</td>\n";
echo "<td><a href='addplayer.php?player=$row[id]'>Add</td>\n";
echo "</tr>";
} 
echo "</table>\n";
}
include 'foot.html';
?>
