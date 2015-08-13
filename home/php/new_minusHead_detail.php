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
		$stmt = $conn->prepare("INSERT INTO table_minusHead VALUES ('',?,?,?,?,?,?,?)");
		$stmt->bind_param('sssssss',$lot_id, $date, $type, $num_head, $price, $move_to, $notes);

		$date = $_POST['date'];
		$type = $_POST['type'];
		$num_head = $_POST['num_head'];
		$price = $_POST['price'];
		$move_to = $_POST['move_to'];
		$notes = $_POST['notes'];
		$lot_id = $_GET['lot_id'];

		if($stmt->execute()){
			echo "stmt working";
		}
		else{	
			echo "stmt not working";
		}
	}else{

	}
?>