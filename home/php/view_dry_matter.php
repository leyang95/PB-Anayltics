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
			<th width="125px">Date</th>
			<th width="90px">Total DMI</th>
			<th width="90px">Total Dry Matter</th>
			<?php 
				$type_name = array();
				$res2 = $conn->query("select type_name from feedtype where user_id=$user_id");
				$counter = 0;

				while($row2 = $res2->fetch_assoc()){
					$type_name[] = $row2['type_name'];
					echo "<th width=\"70px\">".$row2['type_name']."</th>";	
				}
			?>
		</tr>
	</thead>

	<tbody>
		<?php 	

			$date_res = $conn->query("select distinct date_type, dmi, total_dry from `table_drymatter` where lot_id=$id order by date_type asc");
			$row_count=0;

		while($row = $date_res->fetch_assoc()){
			$row_count++;
			$date = $row['date_type']; 
			$newDate = date("m-d-Y", strtotime($date));
			$result = $conn->query("select type, dry_matter from table_dryMatter where lot_id=$id and date_type='$date'");

			$dry_matter = array();
			$percent_weight = array();
			$type = array();


			while($row0 = $result->fetch_assoc()){
				$dry_matter[] = $row0['dry_matter'];
				$type[] = $row0['type'];
			}

				echo "<tr id=\"ration_row_$date\">";
				echo "<td class=\"checkBoxColumn\"style=\"border:none\"><input type=\"checkbox\" class=\"ration_edit ration_checkBox\" value=\"$date\" onchange=\"change_checkBox(this)\"></td>";
				echo "<td >". $newDate. "</td>";
				echo "<td >". $row['dmi'] ."</td>";
				// echo "<td contenteditable=\"true\">". $result->num_rows ."</td>";

				echo "<td >". $row['total_dry'] ."</td>";

				foreach($type_name as $typeNames){
					$check = false;
					for($i=0; $i<count($type); $i++){
						if($type[$i] == $typeNames){
							echo "<td >". $dry_matter[$i] ."</td>";
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
		echo "<td><input type=\"date\" class=\"form-control newRationData\"></td>";
		for($i=0; $i<count($type_name)+2; $i++){
			echo "<td ><input type=\"text\" class=\"form-control newRationData\"></td>";
		}
		echo "</tr>";
		
		for($i=0; $i<16-$row_count; $i++){
			echo "<tr>";
				echo '<td style="border:none"></td>';

			for($j=1; $j<count($type_name)+4; $j++){
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
		

			function add_row_dryMatter(str){
				var input = [];
				$('#ration-table .newRationData').each(function(){
					input.push($(this).val());
				});

				var date_type = input[0];
				var dmi = input[1];
				var total_dry = input[2];
				<?php $type_to_json=json_encode($type_name);
					echo "var type_name = ". $type_to_json .";\n";
				?>

				$.ajax({
					type: "POST",
					url: "php/delete_dryMatter_detail.php?lot_id="+str,
					data: "&date_type="+date_type
				}).done(function(data){
					for(i=3; i<input.length; i++){
					if(input[i] != ""){
						var datas = "&date_type="+date_type+"&dmi="+dmi+"&total_dry="+total_dry+"&type="+type_name[i-3]+
						"&dry="+input[i];
						// alert(datas);
						$.ajax({
								type: "POST",
								url: "php/new_dry_matter_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_DryMatter_table(str);
							});
					}
				}
			
				});
				// alert(type_name);
				
			}	
			
			function delete_row_dryMatter(str){
				var check = $(".ration_checkBox");
				for (i = 0; i < check.length; i++) {
			        if (check[i].checked == true) {
			        	var datas = "&date_type=" + check[i].value;
			          $.ajax({
								type: "POST",
								url: "php/delete_dryMatter_detail.php?lot_id="+str,
								data: datas
							}).done(function(data){
								// console.log(data);
								view_DryMatter_table(str);
								$(".ration_edit").show();
							});
			        }
   				}
			}

			function edit_row_dryMatter(str){
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

			        	// alert(input[2]);
			   			var date_type = input[0];
						var dmi = input[1];
						var total_dry = input[2];
						// alert(date_type+weight+dry);
						var previous_date = check[j].value;
			   			var result = date_type.split("-");
			   			date_type = result[2] +"-"+result[0]+"-"+result[1];
						// alert(date_type+weight+dry);
						if(previous_date != date_type){
							$.ajax({
							type: "POST",
							url: "php/delete_dryMatter_detail.php?lot_id="+str,
							data: "&date_type="+previous_date
						}).done(function(data){
							});
						}
						$.ajax({
							type: "POST",
							url: "php/delete_dryMatter_detail.php?lot_id="+str,
							data: "&date_type="+date_type
						}).done(function(data){
							// console.log(data);
							for(i=3; i<input.length; i++){
								if(input[i] != ""){
									var datas = "&date_type="+date_type+"&dmi="+dmi+"&total_dry="+total_dry+"&type="+type_name[i-3]+
									"&dry="+input[i];
									// alert(datas);
									$.ajax({
											type: "POST",
											url: "php/new_dry_matter_detail.php?lot_id="+str,
											data: datas
										}).done(function(data){
											// console.log(data);
											view_DryMatter_table(str);
										});
								}
							}
						});
						
			        }
			        // alert("loop exit");
   				}
			}

		</script>
	</tbody>
</table>
</div>