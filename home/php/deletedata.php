<?php
	include "config.php";
	if(isset($_GET['id'])){
		$stmt = $conn->prepare("delete from test where id=?");
		$stmt->bind_param('s', $id);
		
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