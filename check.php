<?php
include 'library/config.php';
include 'library/opendb.php';

  session_start();


  if($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = mysql_real_escape_string($_POST['username']);
    $password = mysql_real_escape_string($_POST['password']);
    $result = mysql_query("SELECT id FROM users WHERE username='$username' AND password=md5('$password')");

    if(mysql_num_rows($result) > 0) {
      $_SESSION['is_logged_in'] = 1;
      $_SESSION['username'] = $username;
	$user = mysql_result($result,0);
	$_SESSION['userid'] = $user;
    }
  }
  if(!isset($_SESSION['is_logged_in'])) {
    header("location:login.php");
  } else {
    header("location:home.php");
  }
include 'library/closedb.php';
?>
