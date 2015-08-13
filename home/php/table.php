<?php
	include "config.php";
	$id = $_GET['id'];
	$res = $conn->query("select * from test where id =" . $id);
	$res_cal = $conn->query("select num_head from calculation where lot_id = $id");
	if (!$res) {
    	echo 'Could not run query: ' . mysql_error();
    	exit;
	}

	$row = $res -> fetch_assoc();
	$row_cal = $res_cal -> fetch_assoc();
?>
	<div id="content2-left">
		<span id="nameLot"><?php echo $row['name_lot']; ?></span>
		<br/>
		<p style="font-size: 15px"><?php echo $row['seller'] . ", " . $row['location'] . " ". $row['date'] ;?></span>
		<hr style="height:1px;border:none;color:#333;background-color:#333;margin:0;margin-bottom:5px" />

		<p style="font-size: 12px"><b>At Purchase:</b><br/>
		<?php echo $row['num_head']?> Head | <?php echo $row['gender']?><br/>
			<?php echo $row['breed'] ?> |
			<?php echo $row['seller']?> | $2.25/lb</p>

		<p style="font-size: 12px"><b>Current Projection:</b><br/>
		Days on Feed: <?php echo $row['days_on_feed']+1;?> days<br/>
		Number of Head: <?php echo $row_cal['num_head']?><br/>
		Rate of Gain: <br/>
		Cost per Head:<br/>
		Deaths: </p>

		<p style="font-size: 12px"><b>At Sale Date:</b><br/>
		Days on Feed:<br/>	
		Weight: <br/>
		Rate of Gain:<br/>
		Cost per Head:<br/>
		Sale per Head:<br/>
		Deaths:<br/>
		Net Gain:</p>
		<button type="button" class="btn btn-primary" style="width:100px;"   data-toggle="modal" data-target="#myEditModal<?php echo $row['user_id']; ?>" >Edit Lot</button>

	</div>
	
	<div id="content2-right">
		<form onchange="determine_table()">
		<select id="ratio">
				<option selected value="Ration">Ration</option>
				<option value="feedStuffs">Feed Stuffs</option>
				<!-- <option value="DryMatter">Dry Matter</option> -->
				<option value="FeedCosts">Feed Costs</option>
				<option value="AddHead">Added Head</option>
				<option value="MinusHead">Minus Head</option>
				<option value="OtherCosts">Other Costs</option>
		</select>
		</form>
	</div>
	<div id="bigBossTable">
		<script type="text/javascript">
			function determine_table(){
				if($('#ratio').val() == "Ration")
					view_ration_table(<?php echo $id?>);
				else if($('#ratio').val() == "feedStuffs")
					view_feed_stuffs(<?php echo $id?>);
				else if($('#ratio').val() == "FeedCosts")
					view_feedCosts_table(<?php echo $id?>);
				else if($('#ratio').val() == "AddHead")
					view_AddHead_table(<?php echo $id?>);
				else if($('#ratio').val() == "MinusHead")
					view_MinusHead_table(<?php echo $id?>);
				else if($('#ratio').val() == "DryMatter")
					view_DryMatter_table(<?php echo $id?>);
				else
					view_OtherCosts_table(<?php echo $id?>);
			}

			function view_feed_stuffs(str){
				var id = str;
				$.ajax({
					type:"GET",
					url: "php/view_feed_stuffs.php?id="+id
				}).done(function(data){
					$('#bigBossTable').html(data);
				});
			}

			function view_ration_table(str){
				var id = str;
				$.ajax({
					type:"GET",
					url: "php/view_ration_table.php?id="+id
				}).done(function(data){
					$('#bigBossTable').html(data);
				});
			}

			function view_feedCosts_table(str){
				var id = str;
				$.ajax({
					type:"GET",
					url: "php/view_feedCosts_table.php?id="+id
				}).done(function(data){
					$('#bigBossTable').html(data);
				});
			}

			function view_AddHead_table(str){
				var id = str;
				$.ajax({
					type:"GET",
					url: "php/view_addHead_table.php?id="+id
				}).done(function(data){
					$('#bigBossTable').html(data);
				});
			}

			function view_MinusHead_table(str){
				var id = str;
				$.ajax({
					type:"GET",
					url: "php/view_MinusHead_table.php?id="+id
				}).done(function(data){
					$('#bigBossTable').html(data);
				});
			}

			function view_OtherCosts_table(str){
				var id = str;
				$.ajax({
					type:"GET",
					url: "php/view_otherCost_table.php?id="+id
				}).done(function(data){
					$('#bigBossTable').html(data);
				});
			}

			function view_DryMatter_table(str){
				var id = str;
				$.ajax({
					type:"GET",
					url: "php/view_dry_matter.php?id="+id
				}).done(function(data){
					$('#bigBossTable').html(data);
				});
			}

			view_ration_table(<?php echo $id?>);
		</script>
	</div>