<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id     = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	$nameOfLots = $_POST['nameOfLots'];
	$creationDate = $_POST['creationDate'];
	$source = $_POST['source'];
	$total_cost = $_POST['total_cost'];
	$purchase_weight= $_POST['purchase_weight'];
	$farm_weight = $_POST['farm_weight'];
	$numOfHead = $_POST['numOfHead'];
	$breed = $_POST['breed'];
	$gender = $_POST['gender'];
	$trucking = $_POST['trucking'];
	$cattleCondition = $_POST['cattleCondition'];
	$yardage = $_POST['yardage'];
	$interest =$_POST['interest'];

	if($nameOfLots != null && $creationDate != null && $source != null && $total_cost != null 
		&& $numOfHead != null && $breed != null && $gender != null && $trucking != null && $cattleCondition != null 
		&& $yardage != null && $interest != null){
		$stmt = $conn->prepare("INSERT INTO test VALUES (?,'',?,?,'',?,?,?,?,?,?,?,?,?,?,?,'','','','','','','','','')");
		$stmt->bind_param('ssssssssssssss', $user_id, $nameOfLots, $source, $total_cost, $creationDate, $numOfHead, $purchase_weight, $farm_weight, 
			$trucking, $breed, $gender,  $cattleCondition, $yardage, $interest);
		
		if($stmt->execute()){
			echo "stmt is working";
  		}
  		else{ 
  			echo "stmt is not working" . $stmt->error;
  		}
  	}
  	else{
  		echo"error";
	}
?>