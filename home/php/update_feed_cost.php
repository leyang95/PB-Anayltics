<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	if(isset($_GET['type_name'])){
		$stmt = $conn->prepare("update feedType set cost_per_ton=? where user_id=? AND type_name=?");
		$stmt->bind_param('sss', $cost_per_ton, $user_id, $type_name);
		

		$cost_per_ton = $_POST['cost_per_ton'];
		$type_name = $_GET['type_name'];

		if($stmt->execute()){
?>
			
<?php
		}
		else{
?>			
<?php
		}
	}else{
?>		
		
<?php
	}
?>