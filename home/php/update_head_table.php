<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	$res2 = $conn->query("select num_head from test where id=$id");

	$row = $res2 -> fetch_assoc();

	$num_head = $row['num_head'];

	if($loggedin){
		$stmt = $conn->prepare("update test set num_head=? where id = ?");
		$stmt->bind_param('ss', $num_head,$id);

		$amount = $_POST['amount'];
		$buy_sell = $_POST['buy_sell'];
		$id = $_GET['id'];

		if($buy_sell == 1)
			$num_head += $amount;
		else
			$num_head -= $amount;


		if($stmt->execute()){
		}
		else{
		}
	}else{
	}
?>