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
		$stmt = $conn->prepare("INSERT INTO table_dryMatter VALUES ('',?,?,?,?,?,?)");
		$stmt->bind_param('ssssss',$lot_id, $date_type, $type, $dry, $total_dry, $dmi);

		$date_type = $_POST['date_type'];
		$dmi = $_POST['dmi'];
		$type = $_POST['type'];
		$dry = $_POST['dry'];
		$lot_id = $_GET['lot_id'];
		$total_dry = $_POST['total_dry'];
		
		if($stmt->execute()){
			echo "stmt working";
		}
		else{	
			echo "stmt not working";
		}
	}else{

	}
?>