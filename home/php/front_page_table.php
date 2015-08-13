<?php
	session_start();
	include "config.php";
	 if (isset($_SESSION['user_id']))
	  {
	    $user_id     = $_SESSION['user_id'];
	    $loggedin = TRUE;
	  }
	  else $loggedin = FALSE;

if(!$loggedin)
	die("Please Log in to continue...");

	$id = $_GET['id'];
	$weight1 = $_POST['weight1'];
	$weight2 = $_POST['weight2'];
	$ration = $_POST['ration'];

	$res2 = $conn->query("select * from feed_type where user_id=$user_id AND ration_id=$ration");
	$res_head = $conn->query("select num_head from calculation where lot_id = $id");
	$row2 = null;
	$row_head = $res_head -> fetch_assoc();
?>		
<div style="">
	<?php
		echo "<div style=\"float:left;width:72%\" >";
		$total_dry = 0;
		while($row2 = $res2->fetch_assoc()){
			echo "<div style=\"margin-top:2px;float:left; width: 45px; text-align:center;\">
			<div style=\"text-decoration: underline; font-weight: bold;font-size:12px;height:30px\" >" . $row2['type'] . "</div>
			<div style=\"color:#990000;font-size:13px\"> ". (int)($row2['percent_weight']/100 * $weight1) . "</div>
			<div style=\"color:#990000;margin-top:13px\"> ". (int)($row2['percent_weight']/100 * $weight2) . "</div>
			</div>";
			$total_dry += $row2['percent_dry']/100 * ((int)($row2['percent_weight']/100 * $weight1) + (int)($row2['percent_weight']/100 * $weight2)) ;			
		}
		echo "</div>";
	?>
	<div style="width:100px; float:right;">
		<div id="weight_<?php echo $row['id']?>"
			class="btn btn-primary " style="font-size:14px;width:100%; height: 43px; margin-top: 5px;margin-right:0px;background-color:#505050;" id=""><b><?php echo number_format($weight1 + $weight2,0)?></b> <br><span style="font-size:11px"> Total lbs</span>
		</div>
		<div id="dmi_<?php echo $row['id']?>"
			class="btn btn-primary " style="font-size:14px;width:100%; height: 43px;margin-top: 5px;margin-right:0px;background-color:#505050;"><b><?php echo number_format(($total_dry)/$row_head['num_head'],2) ?></b> <br><span style="font-size:11px">  DMI/Day</span>
		</div>
	</div>
</div>
