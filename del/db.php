<?php
include 'library/config.php';
include 'library/opendb.php';

$id = '2';
$name = 'Q, TEST2';
$pos = 'PF';
$team = 'DET';

mysql_select_db($mysql);
$query = "INSERT INTO players (id, name, pos, team) VALUES ('$id', '$name', '$pos', '$team')";

mysql_query($query) or die('Error, insert query failed');

$query = "FLUSH PRIVILEGES";
mysql_query($query) or die('Error, insert query failed');

include 'library/closedb.php';
?>
