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
		$stmt = $conn->prepare("DELETE from feed_cost where lot_id = ? and cost_id=?");
		$stmt->bind_param('ss',$lot_id, $cost_id);

		$cost_id = $_POST['cost_id'];
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