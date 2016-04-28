<?php
include 'config.php';
include 'opendb.php';
$date = date("omd",time());
#$date = '20081127';
$query = "select * from rosters";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
while ($row = mysql_fetch_array($result)) {
print "inserting $row[playerid] as $row[userid]'s player on $date.<br>";
$ins = "insert into lineups (ruserid, rplayerid, rdate) values ($row[userid],$row[playerid],$date)";
$r_query = mysql_query($ins) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
}

?>
