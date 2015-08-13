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
		$dmi = $_POST['dmi'];
		$type = $_POST['type'];
		$dry = $_POST['dry'];
		$lot_id = $_GET['lot_id'];
		$total_dry = $_POST['total_dry'];
		$christmas = $date_type;
		$parts = explode('-',$christmas);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
		$date_type=$yyyy_mm_dd;

	$res = $conn->query("select type from table_dryMatter where date_type='$date_type' and lot_id=$lot_id and type = '$type'");

	if($res->num_rows > 0){
		$stmt = $conn->prepare("UPDATE table_dryMatter set dry_matter=?, dmi=?, total_dry=? where (lot_id=? and date_type=? and type=?)");
		$stmt->bind_param('ssssss',$dry, $dmi, $total_dry, $lot_id, $date_type, $type );

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{	
			echo "stmt not working";
		}
	}else{
		$stmt2 = $conn->prepare("INSERT INTO table_dryMatter VALUES ('',?,?,?,?,?,?)");
		$stmt2->bind_param('ssssss',$lot_id, $date_type, $type, $dry, $total_dry, $dmi);

		if($stmt2->execute()){
			echo "stmt2 is working";
		}
		else{	
			echo "stmt2 not working";
		}

	}
?>