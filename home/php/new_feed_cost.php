<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	if($loggedin){
		$stmt = $conn->prepare("INSERT INTO feedtype VALUES ('',?,?,?,?)");
		$stmt->bind_param('ssss', $user_id, $type_name, $cost_per_ton, $percent_dry);

		$cost_per_ton = $_POST['cost_per_ton'];
		$type_name = $_POST['type_name'];
		$percent_dry = $_POST['percent_dry'];
		if($stmt->execute()){
			echo "stmt is working";
		}
		else{
			echo "stmt is not working";
		}
	}else{

	}
?>