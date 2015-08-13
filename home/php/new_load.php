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
			$stmt = $conn->prepare("INSERT INTO feed_load VALUES (?,?,?,?,?,?,'')");
			$stmt->bind_param('ssssss',$load_id, $user_id, $ration_id, $name_lot, $actual_weight, $time);

		$name_lot = $_POST['lot_id'];
		$time = $_POST['time'];
		$ration_id = $_GET['id'];
		$weight = "weight_".$time;
		$load_id = $_POST['load_id'];

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