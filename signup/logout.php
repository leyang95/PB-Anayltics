<?php 
  require_once '../connectivity.php';
  

  if (isset($_SESSION['user_id']))
  {
    destroySession();
    $message = "You have been logged out. ";
  }
  else 
    $message = "You cannot log out because you are not logged in";

	die(header("Location:../index.html"));
?>