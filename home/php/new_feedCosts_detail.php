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
		$stmt = $conn->prepare("INSERT INTO table_feedCost VALUES ('',?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param('sssssssss',$lot_id, $days_on_feed, $head_count, $date_type, $fed_per_hd, $ration_total, $type, $running_ration, $cost);

		$date_type = $_POST['date_type'];
		$cost = $_POST['cost'];
		$head_count = $_POST['head_count'];
		$fed_per_hd = $_POST['fed_per_hd'];
		$type = $_POST['type'];
		$ration_total = $_POST['ration_total'];
		$days_on_feed = $_POST['days_on_feed'];
		$running_ration = $_POST['running_ration'];
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