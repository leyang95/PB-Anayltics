<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	if(isset($_GET['type_name'])){
		$stmt = $conn->prepare("update feedtype set percent_dry=? where user_id=? AND type_name=?");
		$stmt->bind_param('sss', $percent_dry, $user_id, $type_name);
		

		$percent_dry = $_POST['percent_dry'];
		$type_name = $_GET['type_name'];

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{
			echo "stmt is not working";
		}
	}else{

	}
?>