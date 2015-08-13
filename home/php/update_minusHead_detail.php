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
		$num_head = $_POST['num_head'];
		$price = $_POST['price'];
		$move_to = $_POST['move_to'];
		$notes = $_POST['notes'];
		$lot_id = $_GET['lot_id'];
		$table_id = $_POST['table_id'];
		$christmas = $date;
		$parts = explode('-',$christmas);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
		$date=$yyyy_mm_dd;


		$stmt = $conn->prepare("UPDATE table_minusHead set date=?, type=?, num_head=?, price=?, move_to=?, notes=? where (lot_id=? and table_id=?)");
		$stmt->bind_param('ssssssss', $date, $type, $num_head, $price, $move_to, $notes,$lot_id, $table_id);

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{	
			echo "stmt not working";
		}

?>