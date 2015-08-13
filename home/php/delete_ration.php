<?php
	include "config.php";
	session_start();
	if (isset($_SESSION['user_id']))
	  {
	    $user_id  = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

	if(isset($_GET['id']) && $loggedin){
		$stmt = $conn->prepare("delete from feed_type where user_id=? AND ration_id=?");
		$stmt->bind_param('ss', $user_id, $id);
		
		$id = $_GET['id'];
		
		if($stmt->execute()){

		}
		else{
?>			
<?php
		}
	}
	else{
?>	
			
<?php
	}
?>