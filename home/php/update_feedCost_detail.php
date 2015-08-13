<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;


		$date_type = $_POST['date_type'];
		$days_on_feed = $_POST['days_on_feed'];
		$type = $_POST['type'];
		$head_count = $_POST['head_count'];
		$lot_id = $_GET['lot_id'];
		$fed_per_hd = $_POST['fed_per_hd'];
		$ration_total = $_POST['ration_total'];
		$running_ration = $_POST['running_ration'];
		$cost = $_POST['cost'];

		$christmas = $date_type;
		$parts = explode('-',$christmas);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
		$date_type=$yyyy_mm_dd;

	$res = $conn->query("select type from table_feedCost where date_type='$date_type' and lot_id=$lot_id and type = '$type'");

	if($res->num_rows > 0){
		$stmt = $conn->prepare("UPDATE table_feedCost set days_on_feed=?, head_count=?, fed_per_hd=?, ration_total=?, running_ration=?, cost=? where (lot_id=? and date_type=? and type=?)");
		$stmt->bind_param('sssssssss',$days_on_feed, $head_count, $fed_per_hd, $ration_total, $running_ration, $cost, $lot_id, $date_type, $type );

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{	
			echo "stmt not working";
		}
	}else{
		$stmt2 = $conn->prepare("INSERT INTO table_feedCost VALUES ('',?,?,?,?,?,?,?,?,?)");
		$stmt2->bind_param('sssssssss',$lot_id, $days_on_feed, $head_count, $date_type, $fed_per_hd, $ration_total, $type, $running_ration, $cost);

		if($stmt2->execute()){
			echo "stmt2 is working";
		}
		else{	
			echo "stmt2 not working";
		}

	}
?>