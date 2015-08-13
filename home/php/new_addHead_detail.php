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
		$purchase_weight = $_POST['purchase_weight'];
		$farm_weight = $_POST['farm_weight'];
		$num_head = $_POST['num_head'];
		$breed = $_POST['breed'];
		$gender = $_POST['gender'];
		$trucking_cost = $_POST['trucking_cost'];
		$cattle_condition = $_POST['cattle_cond'];
		$cost_per_head = $total_cost/$num_head;
		$cost_per_pound = $total_cost/$total_weight;
		$lot_id = $_GET['lot_id'];
	
	$res = $conn->query("select percent_dry from feedtype where user_id=$user_id and lot_id=$lot_id and type = '$type'");


	if($loggedin){
		$stmt = $conn->prepare("INSERT INTO table_addhead VALUES (?,'',?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param('sssssssssssss',$lot_id, $date, $source, $total_cost, $purchase_weight, $farm_weight, $num_head, $breed, $gender, $trucking_cost, $cattle_condition, $cost_per_head, $cost_per_pound);

		
		if($stmt->execute()){
			echo "stmt working";
		}
		else{	
			echo "stmt not working";
		}

	}else{

	}
?>