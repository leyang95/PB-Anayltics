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
		$weight = $_POST['weight'];
		$type = $_POST['type'];
		$percent_weight = $_POST['percent_weight'];
		$lot_id = $_GET['lot_id'];

	  	$res = $conn->query("select percent_dry from feedtype where user_id=$user_id and type_name = '$type'");
	  	$res_head = $conn->query("select num_head from calculation where lot_id = $lot_id");
		$row_head = $res_head -> fetch_assoc();

	  	$row = $res->fetch_assoc();
	  	$percent_dry = $row['percent_dry'];

	if($loggedin){
		$stmt = $conn->prepare("INSERT INTO test_ration VALUES ('',?,?,?,?,?,?,'',?,'','')");
		$stmt->bind_param('sssssss',$lot_id, $user_id, $date_type, $type, $percent_weight, $percent_dry, $weight);

		
		if($stmt->execute()){
			echo "stmt working";
		}
		else{	
			echo "stmt not working";
		}
	}else{

	}
?>