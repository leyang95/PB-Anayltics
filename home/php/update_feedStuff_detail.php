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
		$type = $_POST['type'];
		$price = $_POST['price'];
		$lot_id = $_GET['lot_id'];
		$dm = $_POST['dry'];
		$unit = "per ton";
		$christmas = $date_type;
		$parts = explode('-',$christmas);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
		$date_type=$yyyy_mm_dd;

	$res = $conn->query("select type from table_feedStuffs where date='$date_type' and user_id=$user_id and lot_id=$lot_id and type = '$type'");

	if($res->num_rows > 0){
		$stmt = $conn->prepare("UPDATE table_feedStuffs set price=?, dm=?, unit=? where (lot_id=? and user_id=? and date=? and type=?)");
		$stmt->bind_param('sssssss',$price, $dm, $unit, $lot_id, $user_id, $date_type, $type );

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{	
			echo "stmt not working";
		}
	}else{
		$stmt2 = $conn->prepare("INSERT INTO table_feedstuffs VALUES ('',?,?,?,?,?,?,?)");
		$stmt2->bind_param('sssssss', $date_type, $type, $price, $dm, $unit, $lot_id, $user_id);

		if($stmt2->execute()){
			echo "stmt2 is working";
		}
		else{	
			echo "stmt2 not working";
		}

	}
?>