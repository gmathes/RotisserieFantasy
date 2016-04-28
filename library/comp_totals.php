<?php
include 'config.php';
include 'opendb.php';
$query = "delete from totals";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
$query = "select id from users";
$users = mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
while ($userrow = mysql_fetch_array($users)) {
$user = $userrow[id];
$tot_pts =0;
$tot_rbs = 0;
$tot_ast = 0;
$tot_bs = 0;
$tot_stl = 0;
$tot_shots = 0;
$tot_shots_made = 0;
$tot_threes_made = 0;
$query = "select * from lineups,stats where lineups.rdate = stats.date and lineups.rplayerid = stats.player and lineups.ruserid = '$user'";
$result= mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
while ($row = mysql_fetch_array($result)) {
$tot_pts += $row[pts];
$tot_rbs += $row[reb];
$tot_ast +=  $row[ast];
$tot_bs += $row[bs];
$tot_stl += $row[stl];
$tot_shots += $row[shots];
$tot_shots_made += $row[shots_made];
$tot_threes_made +=  $row[threes_made];
}
$fg_per = $tot_shots_made / $tot_shots;
print "user = $user";
print "Total pts: $tot_pts";
print "Total bds: $tot_rbs";
print "Total ast: $tot_ast";
print "Total bds: $tot_bs";
print "Total sts: $tot_stl";
print "Total shots: $tot_shots";
print "Total fgs: $tot_shots_made";
print "Total threes: $tot_threes_made";
print " FG per: $fg_per<BR>";
$query = "insert into totals (suserid, pts, reb, ast, bs, stl, shots, shots_made, threes_made, fg_per) values ('$user', '$tot_pts', '$tot_rbs', '$tot_ast', '$tot_bs', '$tot_stl', '$tot_shots', '$tot_shots_made', '$tot_threes_made', '$fg_per')";
$value = mysql_query($query) or die("A MySQL error has occurred.<br />Your Query: " . $query . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
}
?>
