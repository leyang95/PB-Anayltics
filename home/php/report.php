<?php
	session_start();

	include "config.php";
	$id = $_GET['id'];

	if (isset($_SESSION['user_id']))
	{
	    $user_id     = $_SESSION['user_id'];
		$loggedin = TRUE;
	}
	else $loggedin = FALSE;

	$res = $conn->query("select name_lot from test where id = $id");
	$row = mysqli_fetch_assoc($res);
?>
<table class="table table-hover table-bordered" style="width:80%;margin-left:10%" id="report_table">
	<thead>
		<tr>
			<th colspan="2"><?php echo $row['name_lot']?></th>
		</tr>
		<tr>
			<th colspan="2">Feedlot Tracker Summary</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Date Delivered</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Date Sold</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Cattle Received</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Cattle Sold</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Deaths</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Death Loss</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Average Days on Feed</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td>Starting Weight</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Finishing Weight</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Feeder Cost</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Market Receipt</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td>Lbs of Gain Per Head</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Total Lbs of Gain</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Consumption Per Head</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Total Lbs of Feed</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Average Daily Gain</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Feed Gain as Fed</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Feed/Gain Dry Matter</td>
			<td><!--TO DO--></td>
		</tr>
		<tr>
			<td>Feed Intake</td>
			<td><!--TO DO--></td>
		</tr>
	</tbody>
</table>