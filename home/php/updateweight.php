<?php
	include "config.php";
	if(isset($_GET['id'])){
		$stmt = $conn->prepare("update test set weight=?, weight_am=?, weight_pm=?, ration_selected=? where id=?");
		$stmt->bind_param('sssss', $weight, $weight1, $weight2, $ration, $id);

		$weight = $_POST['weight'];
		$id = $_GET['id'];
		$weight1 = $_POST['weight1'];
		$weight2 = $_POST['weight2'];
		$ration = $_POST['ration'];

		if($stmt->execute()){
			echo "stmt is working";
		}
		else{
			echo "stmt is not working" . $stmt->error;
		}
	}else{
		echo "no id";
	}
?>