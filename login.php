<?php 
session_start();
 if(isset($_SESSION['is_logged_in'])) {
    header("location:home.php");
  }
?>


<?php include 'head.html';?>
    <form method="POST" action="check.php">
      Username:<br />
      <input type="text" name="username" />
      <br /><br />
      Password:<br />
      <input type="password" name="password" />
      <br /><br />
      <input type="submit" id="subbut" value="Submit" />
    </form>
<?php include 'foot.html';?>
