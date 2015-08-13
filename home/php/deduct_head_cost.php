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
		$stmt = $conn->prepare("INSERT INTO head_cost VALUES (?,'',?,?,?,?,?,?,?)");
		$stmt->bind_param('ssssssss', $lot_id, $amount, $buyer, $date, $farm_weight, $sold_weight, $notes, $sb_id);

		$amount = $_POST['amount'];
		$buyer = $_POST['buyer'];
		$date = $_POST['date'];
		$farm_weight = $_POST['farm_weight'];
		$sold_weight = $_POST['sold_weight'];
		$notes = $_POST['notes'];
		$lot_id = $_GET['id'];
		$sb_id = "0";

		if($stmt->execute()){
			echo "stmt running";
		}
		else{
			echo "stmt error";
		}

	}
?>