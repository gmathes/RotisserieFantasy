<?php
session_start();
  if(!isset($_SESSION['is_logged_in'])) {
	header("location:login.php");
}

include 'head.html';
include 'library/config.php';
include 'library/opendb.php';

$query = "select * from totals, users where users.id = totals.suserid";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
echo "Totals:";
echo "<table border = '2' cellpadding ='5'>\n";
echo "<tr><th>User</th><th>FG %</th><th>Blocks</th><th>Steals</th><th>3s</th><th>Rebounds</th><th>Assists</th><th>Points</th></tr>";
while ($row = mysql_fetch_array($result)) {
echo "<tr>";
echo "<td><a href='home.php?user=$row[id]'>$row[username] </a></td>\n";
echo "<td>" . round($row[fg_per],3) . "</td>";
echo "<td>$row[bs]</td>";
echo "<td>$row[stl]</td>";
echo "<td>$row[threes_made]</td>";
echo "<td>$row[reb]</td>";
echo "<td>$row[ast]</td>";
echo "<td>$row[pts]</td>";

echo "</tr>";
} 
echo "</table>\n";
include 'foot.html';
?>
