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
?>
<div class="bigBossTable">
<table class="table table-hover table-bordered" id ="ration-table" style="table-layout: fixed;">
	<thead style="color:#fff; background-color:#8B0000;text-align:center">
		<tr>
			<th width="15px"></th>
			<th width="60px">Days on Feed</th>
			<th width="60px">Head Count</th>
			<th width="125px">Date</th>
			<th width="90px">As Fed Per Head</th>
			<th width="90px">Ration Totals</th>
			<th width="90px">Running Ration Totals</th>
			<?php 
				$res2 = $conn->query("select type_name from feedtype where user_id=$user_id");
				$counter = 0;
				$type_name = array();
				while($row2 = $res2->fetch_assoc()){
					$type_name[] = $row2['type_name'];
					echo "<th width=\"70px\">".$row2['type_name']."</th>";	
					$counter++;
				}
					
			?>
		</tr>
	</thead>

	<tbody>
		<?php 	
			$total_ration = 0;
			$date_res = $conn->query("select distinct days_on_feed, head_count,date_type, ration_total from `table_feedcost` where lot_id =$id order by date_type asc");
			$row_count=0;
		while($row = $date_res->fetch_assoc()){
			$row_count++;
			$date = $row['date_type']; 
			$newDate = date("m-d-Y", strtotime($date));
			$result = $conn->query("select type, fed_per_hd, cost from table_feedcost where lot_id=$id and date_type='$date' order by table_id asc");
			$cost = array();
			$type = array();

			while($row0 = $result->fetch_assoc()){
				$cost[] = $row0['cost'];
				$type[] = $row0['type'];
				$temp_fed_per_hd = $row0['fed_per_hd'];
			}

				echo "<tr id=\"ration_row_$date\">";
				echo "<td class=\"checkBoxColumn\"style=\"border:none\"><input type=\"checkbox\" class=\"ration_edit ration_checkBox\" value=\"$date\" onchange=\"change_checkBox(this)\"></td>";
				echo "<td >". $row['days_on_feed']. "</td>";
				echo "<td >". $row['head_count']. "</td>";
				echo "<td >". $newDate. "</td>";
				echo "<td >". $temp_fed_per_hd ."</td>";
				echo "<td >". $row['ration_total'] ."</td>";
				$total_ration += $row['ration_total']; 
				echo "<td >". $total_ration ."</td>";
				// echo "<td contenteditable=\"true\">". $result->num_rows ."</td>";

				// $total_dry = 0;
				// foreach($dry as $dries){
				// 	$total_dry += $dries;
				// }
				// echo "<td >". $row['total_dry'] ."</td>";

				foreach($type_name as $typeNames){
					$check = false;
					for($i=0; $i<count($type); $i++){
						if($type[$i] == $typeNames){
							echo "<td >". $cost[$i] ."</td>";
							$check=true;
							break;
						}		
					}
					if(!$check)
						echo "<td ></td>";
				}
				echo "</tr>";

		}
		echo "<tr class=\"ration_edit\">";
		echo "<td style=\"border:none\" ></td>";
		echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";		
		echo "<td><input type=\"date\" class=\"form-control newRationData\"></td>";
		for($i=1; $i<$counter+4; $i++){
			echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		}
		echo "</tr>";

		for($i=0; $i<16-$row_count; $i++){
			echo "<tr>";
				echo '<td style="border:none"></td>';

			for($j=1; $j<$counter+7; $j++){
				echo "<td></td>";
			}

			echo "</tr>";
		}
		?>
		
	</tbody>
	</div>
</table>
</div>

		<script type="text/javascript">
			if($(".not_ration_edit").is(':visible'))
				$(".ration_edit").hide();
			else
				$(".ration_edit").show();
			
			function add_row_feedCost(str){
				var input = [];
				$('#ration-table .newRationData').each(function(){
					input.push($(this).val());
				});

				var days_on_feed = input[0];
				var head_count = input[1];
				var date_type = input[2];
				var fed_per_hd = input[3];
				var ration_total = input[4];
				var running_ration = input[5];

				<?php $type_to_json=json_encode($type_name);
					echo "var type_name = ". $type_to_json .";\n";
				?>

				$.ajax({
					type: "POST",
					url: "php/delete_feedCost_detail.php?lot_id="+str,
					data: "&date_type="+date_type
				}).done(function(data){
					
				});

				for(i=6; i<input.length; i++){
					if(input[i] != ""){
						var datas = "&days_on_feed="+ days_on_feed+"&head_count="+head_count+"&date_type="+date_type+"&fed_per_hd="+fed_per_hd+"&ration_total="+ration_total+"&running_ration="+running_ration+"&type="+type_name[i-6]+
						"&cost="+input[i];
						// alert(datas);
						$.ajax({
								type: "POST",
								url: "php/new_feedCosts_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_feedCosts_table(str);
							});
					}
				}
				// alert(type_name);
				
			}	
			
			function delete_row_feedCost(str){
				var check = $(".ration_checkBox");
				for (i = 0; i < check.length; i++) {
			        if (check[i].checked == true) {
			        	var datas = "&date_type=" + check[i].value;
			          $.ajax({
								type: "POST",
								url: "php/delete_feedCost_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_feedCosts_table(str);
							});
			        }
   				}
			}

			function edit_row_feedCost(str){
				var check = $(".ration_checkBox");
				<?php $type_to_json=json_encode($type_name);
							echo "var type_name = ". $type_to_json .";\n";
						?>
				for (j = 0; j < check.length; j++) {
					var input = new Array();

			        if (check[j].checked == true) {
			        	$('.newRationData'+check[j].value).each(function(){
							input.push($(this).children().val());
						});

			        	var days_on_feed = input[0];
						var head_count = input[1];
						var date_type = input[2];
						var fed_per_hd = input[3];
						var ration_total = input[4];
						var running_ration = input[5];

						<?php $type_to_json=json_encode($type_name);
							echo "var type_name = ". $type_to_json .";\n";
						?>
						var previous_date = check[j].value;

			   			var result = date_type.split("-");
			   			date_type = result[2] +"-"+result[0]+"-"+result[1];
						// alert(date_type+weight+dry);
						if(previous_date != date_type){
							$.ajax({
							type: "POST",
							url: "php/delete_feedCost_detail.php?lot_id="+str,
							data: "&date_type="+previous_date
						}).done(function(data){
							});
						}
						$.ajax({
							type: "POST",
							url: "php/delete_feedCost_detail.php?lot_id="+str,
							data: "&date_type="+date_type
						}).done(function(data){
							// console.log(data);
							
						});

						for(i=6; i<input.length; i++){
							if(input[i] != ""){
								var datas = "&days_on_feed="+ days_on_feed+"&head_count="+head_count+"&date_type="+date_type+"&fed_per_hd="+fed_per_hd+"&ration_total="+ration_total+"&running_ration="+running_ration+"&type="+type_name[i-6]+
								"&cost="+input[i];
								// alert(datas);
								$.ajax({
										type: "POST",
										url: "php/new_feedCosts_detail.php?lot_id="+str,
										data: datas
									}).done(function(data){
										// console.log(data);
										view_feedCosts_table(str);
									});
								}
							}
						// alert(type_name);
						
			        }
			        // alert("loop exit");
   				}
			}

		</script>
	</tbody>
</table>
</div>