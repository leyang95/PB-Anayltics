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
		$stmt = $conn->prepare("DELETE from table_minusHead where lot_id = ? and table_id=?");
		$stmt->bind_param('ss',$lot_id, $table_id);

		$table_id = $_POST['table_id'];
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