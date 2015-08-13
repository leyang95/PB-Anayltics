<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

		$date = $_POST['date'];
		$type = $_POST['type'];
		$cost_amount = $_POST['cost_amount'];
		$notes = $_POST['notes'];
		$lot_id = $_GET['lot_id'];

		$christmas = $date;
		$parts = explode('-',$christmas);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
		$date=$yyyy_mm_dd;


		$stmt = $conn->prepare("UPDATE feed_cost set type=?, cost_amount=?, notes=? where (lot_id=? and date=?)");
		$stmt->bind_param('sssss', $type, $cost_amount, $notes,$lot_id, $date);

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{	
			echo "stmt not working";
		}

?>