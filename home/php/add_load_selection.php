<?php
	session_start();
	include "config.php";
	 if (isset($_SESSION['user_id']))
	  {
	    $user_id     = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;


	$id = $_GET['id'];
	$res2 = $conn->query("select id, name_lot from test where user_id = $user_id and ration_selected=$id" );
?>
<select name="select_option_load[]" class="form-contorl" style="width:150px;height:32px;">		
	<option value=""></option>
<?php
	while($row = $res2-> fetch_assoc())
		echo '<option value="'.$row['name_lot'].'">'.$row['name_lot'].'</option>';
	?>
</select>	
