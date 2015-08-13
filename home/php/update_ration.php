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
	$percent_dry = $_POST['percent_dry'];
	$type_id = $_POST['type_id'];
	$ration_id = $_GET['id'];

	$res = $conn->query("select type from feed_type where and user_id=$user_id and ration_id=$ration_id and type_id=$type_id");
	
	if(mysqli_num_rows($res) > 0){
		$stmt = $conn->prepare("update feed_type set type=?,percent_weight=?, percent_dry=? where user_id=? and ration_id=? and type_id=? ");
		$stmt->bind_param('ssssss' , $type, $percent_weight, $percent_dry, $user_id, $ration_id,$type_id);
	}
	else{
		$stmt = $conn->prepare("INSERT INTO feed_type VALUES ('',?,?,?,?,?,'')");
		$stmt->bind_param('sssss', $user_id, $type, $percent_weight, $percent_dry, $ration_id);
	}
		echo $res->num_rows;

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{
			echo "stmt not working";
		}

?>