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
			$stmt = $conn->prepare("UPDATE feed_load set ration_id=?, lot1=?, weight1=?, am_pm=? where load_id=? and random=?");
			$stmt->bind_param('ssssss', $ration_id, $name_lot, $actual_weight, $time, $load_id, $type_id);

		$name_lot = $_POST['lot_id'];
		$time = $_POST['time'];
		$ration_id = $_GET['id'];
		$weight = "weight_".$time;
		$load_id = $_POST['load_id'];
		$type_id = $_POST['type_id'];

		$res = $conn->query("select $weight from test where user_id = $user_id and name_lot='$name_lot'");
		$row = mysqli_fetch_row($res);

		$actual_weight=$row[0];

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{
			echo "stmt is not working";
		}
	}else{

	}
?>