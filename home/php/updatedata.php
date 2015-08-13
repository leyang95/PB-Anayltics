<?php
	include "config.php";

	if(isset($_GET['id'])){
		$stmt = $conn->prepare("update test set name_lot=?, seller=?, total_cost=?, date=?, num_head=?, purchase_weight=?,farm_weight=?, trucking_cost=?, breed=?, gender=?, cattle_condition=?, yardage=?, interest=? where id=?");
		$stmt->bind_param('ssssssssssssss', $nameOfLots, $source, $total_cost, $creationDate, $numOfHead, $purchase_weight, $farm_weight, $trucking, $breed, $gender,  $cattleCondition, $yardage, $interest, $id);
		

		$nameOfLots = $_POST['nameOfLots'];
		$creationDate = $_POST['creationDate'];
		$source = $_POST['source'];
		$total_cost = $_POST['total_cost'];
		$purchase_weight = $_POST['purchase_weight'];
		$farm_weight = $_POST['farm_weight'];
		$numOfHead = $_POST['numOfHead'];
		$breed = $_POST['breed'];
		$gender = $_POST['gender'];
		$trucking = $_POST['trucking'];
		$cattleCondition = $_POST['cattleCondition'];
		$yardage = $_POST['yardage'];
		$interest =$_POST['interest'];
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