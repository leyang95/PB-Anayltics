<?php
	include "config.php";
	if(isset($_GET['id'])){
		$stmt = $conn->prepare("update test set ration_selected=?  where id=?");
		$stmt->bind_param('ss', $selection, $id);

		$selection = $_POST['selection'];
		$id = $_GET['id'];


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