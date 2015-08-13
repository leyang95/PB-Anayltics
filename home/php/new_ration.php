<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	  $type = $_POST['type'];
		$percent_weight = $_POST['percent_weight'];
		$ration_id = $_GET['id'];

	  	$res = $conn->query("select percent_dry from feedtype where type_name='$type' and user_id=$user_id");
	  	$row = $res->fetch_assoc();
	  	$percent_dry = $row["percent_dry"];

	if($loggedin){
		$stmt = $conn->prepare("INSERT INTO feed_type VALUES ('',?,?,?,?,?,'')");
		$stmt->bind_param('sssss', $user_id, $type, $percent_weight, $percent_dry, $ration_id);

		
		if($stmt->execute()){
			echo "stmt is working";
		}
		else{
			echo "stmt is not working";
		}
	}else{

	}
?>