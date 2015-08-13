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
		$stmt = $conn->prepare("INSERT INTO feed_cost VALUES ('',?,?,?,?,?)");
		$stmt->bind_param('sssss', $date, $type, $notes, $cost_amount, $lot_id);


		$date = $_POST['date'];
		$type = $_POST['type'];
		$cost_amount = $_POST['cost_amount'];
		$notes = $_POST['notes'];
		$lot_id = $_GET['lot_id'];


		if($stmt->execute()){
			echo "stmt working";
		}
		else{	
			echo "stmt not working";
			echo $stmt->error;
		}
	}else{

	}
?>