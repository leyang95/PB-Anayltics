<?php
	$conn = new mysqli("localhost", "nishatech", "nishatech", "feedlot");
	if($conn->connect_errno){
		echo "Failed to connect to MySQL: (" . $conn->connect_errno .")". $conn->connect_error;
	}
?>