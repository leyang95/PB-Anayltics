<?php
	include "config.php";
	session_start();
	if(isset($_SESSION['user_id']))
	{
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	}
	else 
	  	$loggedin = FALSE;

		$stmt = $conn->prepare("INSERT INTO self_feed VALUES ('',?,?,?)");
		$stmt->bind_param('sss', $date, $weight, $lot_id);

		$stmt2 = $conn->prepare("UPDATE test set weight =?, weight_am=?, weight_pm=? where id=? and user_id =?");
		$stmt2->bind_param('sssss', $weight, $weight_am, $weight_pm, $lot_id, $user_id);

		$date = $_POST['date_id'];
		$weight = $_POST['weight'];
		$lot_id = $_GET['id'];
		$weight_am = 0;
		$weight_pm = 0;

		if($stmt2->execute()){

			echo "not stmt2 echo";
		}
		else{
			echo "stmt2 echo";
		}

		if($stmt->execute()){

			echo "not stmt echo";
		}
		else{
			echo "stmt echo";
		}

?>		
		