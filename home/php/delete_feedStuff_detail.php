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
		$stmt = $conn->prepare("DELETE from table_feedstuffs where lot_id = ? and user_id=? and date=?");
		$stmt->bind_param('sss',$lot_id, $user_id, $date_type);

		$date_type = $_POST['date_type'];
		$lot_id = $_GET['lot_id'];

		if($stmt->execute()){
			echo "stmt no prob";
		}
		else{
			echo "stmt got prob";
		}
	}else{

	}
?>