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
		$source = $_POST['source'];
		$total_cost = $_POST['total_cost'];
		$total_weight = $_POST['total_weight'];
		$num_head = $_POST['num_head'];
		$breed = $_POST['breed'];
		$gender = $_POST['gender'];
		$trucking_cost = $_POST['trucking_cost'];
		$cattle_condition = $_POST['cattle_cond'];
		$cost_per_head = $_POST['cost_head'];
		$cost_per_pound = $_POST['cost_pound'];
		$lot_id = $_GET['lot_id'];
		$table_id = $_POST['table_id'];

		$christmas = $date;
		$parts = explode('-',$christmas);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
		$date=$yyyy_mm_dd;


		$stmt = $conn->prepare("UPDATE table_addhead set date=?, source=?, total_cost=?, total_weight=?, num_head=?, breed=?, gender=?, trucking_cost=?, cattle_condition=?, cost_per_head=?, cost_per_pound=? where (lot_id=? and table_id=?)");
		$stmt->bind_param('sssssssssssss',$date, $source, $total_cost, $total_weight, $num_head, $breed, $gender, $trucking_cost, $cattle_condition, $cost_per_head, $cost_per_pound, $lot_id, $table_id);

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{	
			echo "stmt not working";
		}

?>