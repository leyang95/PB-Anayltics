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
		$stmt = $conn->prepare("INSERT INTO table_feedstuffs VALUES ('',?,?,?,?,?,?,?)");
		$stmt->bind_param('sssssss', $date_type, $type, $price, $dm, $unit, $lot_id, $user_id);

		$date_type = $_POST['date_type'];
		$type = $_POST['type'];
		$price = $_POST['price'];
		$lot_id = $_GET['lot_id'];
		$unit = "per ton";
		$dm = $_POST['dry'];
		
		if($stmt->execute()){
			echo "stmt working";
		}
		else{	
			echo "stmt not working";
		}
	}else{

	}
?>