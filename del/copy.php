<?php
die;
include 'library/config.php';
include 'library/opendb.php';
$tm = 1225080000;
$end = 1227668711;
while ($tm < $end) {
$date = date("omd",$tm);
$query = "select * from rosters";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
while ($row = mysql_fetch_array($result)) {
print "Totally gonna insert $row[playerid] as $row[userid]'s player on $date.<br>";
$ins = "insert into lineups (ruserid, rplayerid, rdate) values ($row[userid],$row[playerid],$date)";
$r_query = mysql_query($ins) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
}

$tm = $tm + 86400;
}
