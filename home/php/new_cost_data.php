<?php
	include "config.php";

	$type_of_cost = $_POST['type_of_cost'];
	$cost_date = $_POST['cost_date'];
	$cost_amount = $_POST['cost_amount'];
	$notes = $_POST['notes'];
	$lot_id = $_GET['id'];

		$stmt = $conn->prepare("INSERT INTO feed_cost VALUES ('',?,?,?,?,?)");
		$stmt->bind_param('sssss', $cost_date, $type_of_cost, $notes, $cost_amount, $lot_id);
		
		if($stmt->execute()){
			echo "stmt is fine";
  		}
  		else{
  			echo "stmt is not fine";
  		}
  

	
?>