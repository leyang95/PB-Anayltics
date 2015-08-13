<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	  	// $previous_date = $_POST['previous_date'];
		$date_type = $_POST['date_type'];
		$weight = $_POST['weight'];
		$type = $_POST['type'];
		$percent_weight = $_POST['percent_weight'];
		$percent_dry = 0;
		$lot_id = $_GET['lot_id'];
		$ration_id = 1;
		$total_dry = $_POST['dry'];

	$res = $conn->query("select type from test_ration where date_type='$date_type' and user_id=$user_id and lot_id=$lot_id and type = '$type'");
		
		echo $res->num_rows;
		
		if($res->num_rows > 0){
			// if($previous_date == $date_type){
				$stmt = $conn->prepare("UPDATE test_ration set percent_weight=?, percent_dry=?, ration_id=?, weight=?, total_dry=? where (lot_id=? and user_id=? and date_type=? and type=?)");
				$stmt->bind_param('sssssssss',$percent_weight, $percent_dry, $ration_id, $weight, $total_dry, $lot_id, $user_id, $date_type, $type );

				if($stmt->execute()){
					echo "stmt is working";
				}
				else{	
					echo "stmt not working";
				}
			// }
			// else{
			// 	$res = $conn->query("DELETE from test_ration where date_type='$previous_date' and user_id=$user_id and lot_id=$lot_id");
			// 	$res = $conn->query("DELETE from test_ration where date_type='$date_type' and user_id=$user_id and lot_id=$lot_id");

			// 	$stmt3 = $conn->prepare("REPLACE INTO test_ration VALUES ('',?,?,?,?,?,?,?,?,?)");
			// 	$stmt3->bind_param('sssssssss',$lot_id, $user_id, $date_type, $type, $percent_weight, $percent_dry, $ration_id, $weight, $total_dry);

			// 	if($stmt3->execute()){
			// 		echo "stmt3 is working";
			// 	}
			// 	else{	
			// 		echo "stmt3 not working";
			// 	}
			// }
		}else{
			$stmt2 = $conn->prepare("REPLACE INTO test_ration VALUES ('',?,?,?,?,?,?,?,?,?)");
			$stmt2->bind_param('sssssssss',$lot_id, $user_id, $date_type, $type, $percent_weight, $percent_dry, $ration_id, $weight, $total_dry);

			if($stmt2->execute()){
				echo "stmt2 is working";
			}
			else{	
				echo "stmt2 not working";
			}

		}
		
	
?>